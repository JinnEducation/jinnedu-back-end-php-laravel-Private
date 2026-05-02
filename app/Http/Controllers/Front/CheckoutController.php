<?php

namespace App\Http\Controllers\Front;

use App\Enums\TransactionPaymentStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletPaymentTransaction;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     * Supports two modes:
     * 1. topup - Add money to wallet
     * 2. pay - Pay for existing orders
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('home');
        }

        // Get user with wallet
        $user = User::find($user->id);

        // Determine checkout type (topup or pay)
        $checkoutType = trim($request->get('type', 'topup')); // default is topup

        // Initialize variables
        $orders = collect();
        $payableOrderIds = [];
        $nonPayableOrderIds = [];
        $hasPayableOrders = false;
        $totalAmount = 0;

        // If checkout type is 'pay', get orders
        if ($checkoutType === 'pay' && $request->has('order_ids')) {
            $orderIds = explode(',', $request->get('order_ids'));
            $orderIds = array_filter(array_map('intval', $orderIds)); // Clean and validate IDs

            if (! empty($orderIds)) {
                // Get orders for the user (show all orders, filter by status only when paying)
                $orders = Order::whereIn('id', $orderIds)
                    ->where('user_id', $user->id)
                    ->get();

                $payableOrders = $orders->whereIn('status', [0, 2]);
                $payableOrderIds = $payableOrders->pluck('id')->all();
                $nonPayableOrderIds = $orders->whereNotIn('status', [0, 2])->pluck('id')->all();
                $hasPayableOrders = ! empty($payableOrderIds);
                $totalAmount = $payableOrders->sum('price');
            }
        }

        // Get wallet balance
        $wallet = $user->wallets()->first();
        $walletBalance = $wallet ? $wallet->balance : 0;

        // Available Countries
        $countries = [
            'palestine' => 'Palestine',
            'jordan' => 'Jordan',
            'egypt' => 'Egypt',
            'saudi' => 'Saudi Arabia',
            'uae' => 'United Arab Emirates',
        ];

        // Payment Gateways Configuration
        $paymentGateways = $this->getPaymentGateways($checkoutType);

        // Debug: Log payment gateways (remove after testing)
        // \Log::info('Checkout Debug', [
        //     'checkoutType' => $checkoutType,
        //     'paymentGateways' => array_keys($paymentGateways),
        //     'orders_count' => $orders->count(),
        //     'totalAmount' => $totalAmount
        // ]);

        return view('front.checkout', compact(
            'user',
            'countries',
            'paymentGateways',
            'checkoutType',
            'orders',
            'payableOrderIds',
            'nonPayableOrderIds',
            'hasPayableOrders',
            'totalAmount',
            'walletBalance'
        ));
    }

    /**
     * Process checkout payment
     */
    public function checkout_store(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'user-not-authenticated',
            ], 401);
        }

        // Validation
        $request->validate([
            'type' => 'required|in:topup,pay',
            'payment_gateway' => 'required|string',
            'country' => 'required|string',
        ]);

        $checkoutType = $request->type;
        $paymentGateway = $request->payment_gateway;

        $availableGateways = array_keys($this->getPaymentGateways($checkoutType));
        if (! in_array($paymentGateway, $availableGateways, true)) {
            return response()->json([
                'success' => false,
                'message' => 'unsupported-payment-gateway',
            ], 422);
        }

        // Handle discount code if provided
        $discountAmount = 0;
        if ($request->has('discount_code') && ! empty($request->discount_code)) {
            $discountResult = $this->calculateDiscount($request->discount_code, $request->amount);
            if ($discountResult['valid']) {
                $discountAmount = $discountResult['discount'];
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $discountResult['message'],
                ], 422);
            }
        }
        // Route to appropriate handler
        if ($checkoutType === 'topup') {
            return $this->handleTopup($request, $user, $discountAmount);
        } else {
            return $this->handlePayment($request, $user, $discountAmount);
        }
    }

    /**
     * Handle wallet top-up
     */
    private function handleTopup(Request $request, $user, $discountAmount)
    {
        $amount = $request->amount ?? 0;
        $finalAmount = max(0, $amount - $discountAmount);
        if ($finalAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'invalid-amount',
            ], 422);
        }
        // Cannot use wallet to top-up wallet
        if ($request->payment_gateway === 'my-wallet') {
            return response()->json([
                'success' => false,
                'message' => 'cannot-use-wallet-for-topup',
            ], 422);
        }

        // Create payment transaction
        $transaction = \App\Models\WalletPaymentTransaction::create([
            'user_id' => $user->id,
            'amount' => $finalAmount,
            'payment_channel' => $request->payment_gateway,
            'current_wallet' => $user->wallets()->first()?->balance ?? 0,
            'reference_id' => (string) \Illuminate\Support\Str::uuid(),
            'response' => json_encode(['type' => 'topup', 'payment_gateway' => $request->payment_gateway]),
        ]);

        $this->createPendingWalletTopupTransaction($transaction);

        // Process payment gateway
        return $this->processPaymentGateway($transaction, $request, 'topup');
    }

    /**
     * Handle payment for orders
     */
    private function handlePayment(Request $request, $user, $discountAmount)
    {
        $orderIds = $request->order_ids ?? [];
        if (! is_array($orderIds)) {
            $orderIds = explode(',', $orderIds);
        }
        $orderIds = array_filter(array_map('intval', $orderIds));

        if (empty($orderIds)) {
            return response()->json([
                'success' => false,
                'message' => 'no-orders-found',
            ], 404);
        }

        $orders = Order::whereIn('id', $orderIds)
            ->where('user_id', $user->id)
            ->whereIn('status', [0, 2])
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'no-valid-orders-found',
                'details' => 'No payable orders found. Orders may already be paid.',
            ], 422);
        }

        $payableOrderIds = $orders->pluck('id')->all();
        $totalAmount = $orders->sum('price');
        $finalAmount = max(0, $totalAmount - $discountAmount);

        if ($finalAmount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'invalid-amount',
            ], 422);
        }

        // If paying from wallet
        if ($request->payment_gateway === 'my-wallet') {
            return $this->payFromWallet($user, $orders, $finalAmount, $discountAmount);
        }

        // Create payment transaction
        $transaction = \App\Models\WalletPaymentTransaction::create([
            'user_id' => $user->id,
            'amount' => $finalAmount,
            'payment_channel' => $request->payment_gateway,
            'current_wallet' => $user->wallets()->first()?->balance ?? 0,
            'reference_id' => (string) \Illuminate\Support\Str::uuid(),
            'response' => json_encode([
                'order_ids' => $payableOrderIds,
                'type' => 'pay',
                'payment_gateway' => $request->payment_gateway,
                'discount_amount' => $discountAmount,
                'original_total' => $totalAmount,
            ]),
        ]);

        $this->createPendingOrderDebitTransactions($orders, $finalAmount, $transaction, $discountAmount);

        // Process payment gateway
        return $this->processPaymentGateway($transaction, $request, 'pay');
    }

    /**
     * Pay directly from wallet
     */
    private function payFromWallet($user, $orders, $amount, $discountAmount = 0)
    {
        $wallet = $user->wallets()->first();
        if (! $wallet) {
            $wallet = UserWallet::create([
                'user_id' => $user->id,
                'balance' => 0,
            ]);
        }

        if ($wallet->balance < $amount) {
            $shortage = $amount - $wallet->balance;

            return response()->json([
                'success' => false,
                'message' => 'insufficient-wallet-balance',
                'required' => $amount,
                'available' => $wallet->balance,
                'shortage' => $shortage,
                'topup_url' => route('checkout', ['type' => 'topup']),
                'message_text' => 'Your wallet balance ($'.number_format($wallet->balance, 2).') is insufficient. You need $'.number_format($shortage, 2).' more. Please top up your wallet first.',
            ], 422);
        }

        try {
            DB::transaction(function () use ($wallet, $orders, $amount, $user, $discountAmount) {
                $this->ensurePrivateLessonSlotsAvailable($orders);

                $balanceBefore = (float) $wallet->balance;
                $wallet->balance -= $amount;
                $wallet->save();
                $balanceAfter = (float) $wallet->balance;

                foreach ($orders as $order) {
                    $order->status = 1;
                    $order->payment = 'wallet';
                    $order->save();
                }

                $this->createPaidOrderSideEffects($orders);
                $this->createOrderDebitTransactions($orders, $amount, $user->id, 'wallet', $discountAmount, null, $balanceBefore, $balanceAfter);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'payment-completed-successfully',
            'orders' => $orders,
            'wallet_balance' => $wallet->balance,
            'redirect_url' => route('checkout-complete'),
        ]);
    }

    /**
     * Process payment through gateway
     */
    private function processPaymentGateway($transaction, Request $request, $type)
    {
        try {
            $gateway = \App\Services\Payment\PaymentManager::driver($transaction->payment_channel);
            $response = $gateway->createPayment([
                'reference_id' => $transaction->reference_id,
                'amount' => $transaction->amount,
                'currency' => 'USD',
                'type' => $type,
                'description' => $transaction->payment_channel === 'topup'
                    ? 'Wallet Top-up'
                    : 'Order Payment',
                'success_url' => route('checkout-response', ['id' => $transaction->id, 'status' => 'success']),
                'cancel_url' => route('checkout-response', ['id' => $transaction->id, 'status' => 'cancel']),
            ]);

            // Extract payment URL based on gateway
            $url = $this->extractPaymentUrl($transaction->payment_channel, $response);

            if (! $url) {
                $this->markPaymentLedgerTransactions($transaction, 'failed');

                return response()->json([
                    'success' => false,
                    'message' => 'payment-gateway-url-not-found',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'redirecting-to-payment-gateway',
                'url' => $url,
                'transaction_id' => $transaction->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Payment Gateway Error: '.$e->getMessage());
            $this->markPaymentLedgerTransactions($transaction, 'failed');

            return response()->json([
                'success' => false,
                'message' => 'payment-gateway-error',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

    /**
     * Extract payment URL from gateway response
     */
    private function extractPaymentUrl($gateway, $response)
    {
        if ($gateway === 'paypal') {
            return collect($response['links'] ?? [])->firstWhere('rel', 'approve')['href'] ?? null;
        } elseif ($gateway === 'stripe') {
            return $response['url'] ?? null;
        } elseif ($gateway === 'local-test') {
            return $response['url'] ?? null;
        }

        // Add more gateways as needed
        return null;
    }

    /**
     * Handle payment response from gateway
     */
    public function handlePaymentResponse(Request $request, $locale, $transactionId, $status)
    {
        if (! in_array($status, ['success', 'cancel'])) {
            abort(400, 'Invalid payment status');
        }

        $transaction = \App\Models\WalletPaymentTransaction::findOrFail($transactionId);
        $type = json_decode($transaction->response, true)['type'];
        if ($status === 'success') {
            try {
                $gateway = \App\Services\Payment\PaymentManager::driver($transaction->payment_channel);

                // Add reference_id to request for gateways that need it (like local-test)
                $request->merge(['reference_id' => $transaction->reference_id]);

                $result = $gateway->success($request);

                // Convert JsonResponse to array
                $resultData = $result instanceof \Illuminate\Http\JsonResponse
                    ? $result->getData(true)
                    : (is_array($result) ? $result : []);

                // If payment succeeded, complete transaction
                if (isset($resultData['success']) && $resultData['success']) {
                    $this->completeTransaction($transaction, $type);

                    // dd('success',Auth::user()->wallets()->first());
                    return redirect()->route('checkout-complete')
                        ->with('success', $resultData['message'] ?? 'Payment completed successfully');
                } else {
                    // Payment failed
                    $transaction->payment_status = TransactionPaymentStatus::CANCELED;
                    $transaction->save();
                    $this->markPaymentLedgerTransactions($transaction, 'failed');

                    return redirect()->route('checkout')
                        ->with('error', $resultData['message'] ?? 'Payment processing failed');
                }
            } catch (\Exception $e) {
                \Log::error('Payment Response Error: '.$e->getMessage());

                // Mark transaction as failed
                $transaction->payment_status = TransactionPaymentStatus::CANCELED;
                $transaction->save();
                $this->markPaymentLedgerTransactions($transaction, 'failed');

                // throw $e;

                return redirect()->route('checkout')
                    ->with('error', 'Payment processing failed: '.$e->getMessage());
            }
        } else {
            $transaction->payment_status = TransactionPaymentStatus::CANCELED;
            $transaction->save();
            $this->markPaymentLedgerTransactions($transaction, 'canceled');

            return redirect()->route('checkout')
                ->with('error', 'Payment was canceled');
        }
    }

    /**
     * Complete transaction and update wallet
     */
    private function completeTransaction($transaction, $type)
    {
        DB::transaction(function () use ($transaction, $type) {
            $transaction = WalletPaymentTransaction::query()
                ->lockForUpdate()
                ->findOrFail($transaction->id);

            $metadata = json_decode($transaction->response, true) ?: [];

            if (! empty($metadata['checkout_finalized_at'])) {
                return;
            }

            $transaction->payment_status = TransactionPaymentStatus::COMPLETED;
            $transaction->status = TransactionStatus::ACTIVE;

            $wallet = $transaction->user->wallets()->first();
            if (! $wallet) {
                $wallet = UserWallet::create([
                    'user_id' => $transaction->user_id,
                    'balance' => 0,
                ]);
            }

            if ($type === 'topup') {
                $balanceBefore = (float) $wallet->balance;
                $wallet->update([
                    'balance' => $wallet->balance + $transaction->amount,
                ]);
                $balanceAfter = (float) $wallet->fresh()->balance;

                $this->completePendingWalletTopupTransaction($transaction, $balanceBefore, $balanceAfter);
            }

            if ($type === 'pay' && ! empty($metadata['order_ids'])) {
                $discountAmount = (float) ($metadata['discount_amount'] ?? 0);
                $this->completeOrders($metadata['order_ids'], $transaction, $discountAmount);
            }

            $metadata['checkout_finalized_at'] = now()->toDateTimeString();
            $metadata['checkout_finalized_version'] = 'v1';
            $transaction->response = json_encode($metadata);
            $transaction->save();
        });
    }

    /**
     * Complete orders after successful payment
     */
    private function completeOrders($orderIds, $transaction, float $discountAmount = 0): void
    {
        $orders = Order::whereIn('id', $orderIds)
            ->where('user_id', $transaction->user_id)
            ->whereIn('status', [0, 2])
            ->get();

        if ($orders->isEmpty()) {
            return;
        }

        $finalPaidAmount = max(0, (float) $orders->sum('price') - $discountAmount);

        foreach ($orders as $order) {
            $order->status = 1; // completed
            $order->payment = $transaction->payment_channel;
            $order->save();
        }

        $this->createPaidOrderSideEffects($orders);
        $this->createOrderDebitTransactions($orders, $finalPaidAmount, $transaction->user_id, $transaction->payment_channel, $discountAmount, $transaction, $transaction->current_wallet, $transaction->current_wallet);
    }

    private function createPaidOrderSideEffects($orders): void
    {
        $this->ensurePrivateLessonSlotsAvailable($orders);

        foreach ($orders as $order) {
            if ((int) $order->ref_type !== 4) {
                continue;
            }

            $conferenceExists = Conference::where('order_id', $order->id)->exists();
            if ($conferenceExists) {
                continue;
            }

            (new ConferenceController)->createPrivateLessonConference($order);
        }
    }

    private function ensurePrivateLessonSlotsAvailable($orders): void
    {
        foreach ($orders as $order) {
            if ((int) $order->ref_type !== 4) {
                continue;
            }

            $dates = json_decode($order->dates);
            if (! $dates?->start_date_time) {
                continue;
            }

            $startDateTime = $dates->start_date_time;
            $endDateTime = $dates->end_date_time ?: date('Y-m-d H:i:s', strtotime($startDateTime.' +60 minutes'));

            $conflict = Conference::where('tutor_id', $order->ref_id)
                ->where('order_id', '!=', $order->id)
                ->whereNotNull('start_date_time')
                ->whereNotNull('end_date_time')
                ->where('start_date_time', '<', $endDateTime)
                ->where('end_date_time', '>', $startDateTime)
                ->first();

            if ($conflict) {
                throw new \Exception('This time was just booked. Please choose another time.');
            }
        }
    }

    private function createPendingWalletTopupTransaction(WalletPaymentTransaction $transaction): void
    {
        WalletTransaction::create([
            'user_id' => $transaction->user_id,
            'wallet_payment_transaction_id' => $transaction->id,
            'type' => 'credit',
            'transaction_type' => 'wallet_topup',
            'payment_gateway' => $transaction->payment_channel,
            'status' => 'pending',
            'amount' => $transaction->amount,
            'balance_before' => $transaction->current_wallet,
            'currency_code' => $transaction->currency_code ?? 'USD',
            'description' => 'transaction.wallet_topup',
            'metadata' => [
                'translation_key' => 'transaction.wallet_topup',
                'reference_id' => $transaction->reference_id,
            ],
        ]);
    }

    private function completePendingWalletTopupTransaction(WalletPaymentTransaction $transaction, float $balanceBefore, float $balanceAfter): void
    {
        $ledger = WalletTransaction::where('wallet_payment_transaction_id', $transaction->id)
            ->where('transaction_type', 'wallet_topup')
            ->first();

        if (! $ledger) {
            $this->createPendingWalletTopupTransaction($transaction);
            $ledger = WalletTransaction::where('wallet_payment_transaction_id', $transaction->id)
                ->where('transaction_type', 'wallet_topup')
                ->first();
        }

        $ledger->update([
            'status' => 'completed',
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'metadata' => array_merge($ledger->metadata ?? [], [
                'completed_at' => now()->toDateTimeString(),
            ]),
        ]);
    }

    private function createPendingOrderDebitTransactions($orders, float $finalAmount, WalletPaymentTransaction $transaction, float $discountAmount = 0): void
    {
        $distributedAmounts = $this->distributeFinalAmountAcrossOrders($orders, $finalAmount);

        foreach ($orders as $order) {
            $debitAmount = (float) ($distributedAmounts[$order->id] ?? 0);
            if ($debitAmount <= 0) {
                continue;
            }

            WalletTransaction::create([
                'user_id' => $transaction->user_id,
                'order_id' => $order->id,
                'wallet_payment_transaction_id' => $transaction->id,
                'type' => 'debit',
                'transaction_type' => $this->transactionTypeForOrder($order),
                'payment_gateway' => $transaction->payment_channel,
                'status' => 'pending',
                'amount' => $debitAmount,
                'balance_before' => $transaction->current_wallet,
                'balance_after' => $transaction->current_wallet,
                'currency_code' => $transaction->currency_code ?? 'USD',
                'description' => 'transaction.'.$this->transactionTypeForOrder($order),
                'metadata' => [
                    'translation_key' => 'transaction.'.$this->transactionTypeForOrder($order),
                    'reference_id' => $transaction->reference_id,
                    'order_ref_type' => (int) $order->ref_type,
                    'discount_amount' => $discountAmount,
                ],
            ]);
        }
    }

    private function createOrderDebitTransactions($orders, float $finalAmount, int $userId, string $channel, float $discountAmount = 0, ?WalletPaymentTransaction $paymentTransaction = null, ?float $balanceBefore = null, ?float $balanceAfter = null): void
    {
        $distributedAmounts = $this->distributeFinalAmountAcrossOrders($orders, $finalAmount);

        foreach ($orders as $order) {
            $debitAmount = (float) ($distributedAmounts[$order->id] ?? 0);

            if ($debitAmount <= 0) {
                continue;
            }

            $transactionType = $this->transactionTypeForOrder($order);

            if ($paymentTransaction) {
                $ledger = WalletTransaction::where('wallet_payment_transaction_id', $paymentTransaction->id)
                    ->where('order_id', $order->id)
                    ->first();

                if ($ledger) {
                    $ledger->update([
                        'status' => 'completed',
                        'amount' => $debitAmount,
                        'transaction_type' => $transactionType,
                        'payment_gateway' => $channel,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceAfter,
                        'metadata' => array_merge($ledger->metadata ?? [], [
                            'completed_at' => now()->toDateTimeString(),
                            'discount_amount' => $discountAmount,
                        ]),
                    ]);

                    continue;
                }
            }

            WalletTransaction::create([
                'user_id' => $userId,
                'order_id' => $order->id,
                'type' => 'debit',
                'transaction_type' => $transactionType,
                'payment_gateway' => $channel,
                'status' => 'completed',
                'amount' => $debitAmount,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => 'transaction.'.$transactionType,
                'metadata' => [
                    'translation_key' => 'transaction.'.$transactionType,
                    'order_ref_type' => (int) $order->ref_type,
                    'discount_amount' => $discountAmount,
                ],
            ]);
        }
    }

    private function markPaymentLedgerTransactions(WalletPaymentTransaction $transaction, string $status): void
    {
        WalletTransaction::where('wallet_payment_transaction_id', $transaction->id)
            ->where('status', 'pending')
            ->update(['status' => $status]);
    }

    private function transactionTypeForOrder(Order $order): string
    {
        return match ((int) $order->ref_type) {
            1 => 'group_class_purchase',
            2 => 'course_purchase',
            4 => 'private_lesson_purchase',
            7 => 'wallet_package_purchase',
            default => 'order_payment',
        };
    }

    private function distributeFinalAmountAcrossOrders($orders, float $finalAmount): array
    {
        $result = [];
        $count = $orders->count();

        if ($count === 0 || $finalAmount <= 0) {
            foreach ($orders as $order) {
                $result[$order->id] = 0.00;
            }

            return $result;
        }

        $totalOriginal = (float) $orders->sum('price');
        if ($totalOriginal <= 0) {
            $share = round($finalAmount / $count, 2);
            $allocated = 0;

            foreach ($orders->values() as $index => $order) {
                if ($index === $count - 1) {
                    $result[$order->id] = round($finalAmount - $allocated, 2);
                } else {
                    $result[$order->id] = $share;
                    $allocated += $share;
                }
            }

            return $result;
        }

        $allocated = 0;
        foreach ($orders->values() as $index => $order) {
            if ($index === $count - 1) {
                $amount = round($finalAmount - $allocated, 2);
            } else {
                $ratio = ((float) $order->price) / $totalOriginal;
                $amount = round($finalAmount * $ratio, 2);
                $allocated += $amount;
            }

            $result[$order->id] = max(0, $amount);
        }

        return $result;
    }

    /**
     * Apply discount code (called from route)
     */
    public function applyDiscountCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $result = $this->calculateDiscount($request->code, $request->amount);

        if ($result['valid']) {
            return response()->json([
                'success' => true,
                'discount' => $result['discount'],
                'percentage' => $result['percentage'],
                'message' => $result['message'],
            ]);
        }

        return response()->json([
            'success' => false,
            'discount' => 0,
            'message' => $result['message'],
        ], 422);
    }

    /**
     * Calculate discount amount (internal method)
     */
    private function calculateDiscount(string $code, float $amount): array
    {
        $discount = DiscountCode::where('code', strtoupper($code))->first();

        if (! $discount || ! $discount->isValid()) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => 'Invalid or expired discount code',
            ];
        }

        $discountAmount = round($amount * ($discount->percentage / 100), 2);

        return [
            'valid' => true,
            'percentage' => $discount->percentage,
            'discount' => $discountAmount,
            'message' => 'Discount applied successfully',
        ];
    }

    /**
     * Get payment gateways configuration
     */
    private function getPaymentGateways($checkoutType = 'topup')
    {
        $gateways = [];

        // Normalize checkout type
        $checkoutType = trim(strtolower($checkoutType));

        // Add my-wallet only for payment mode (not for topup)
        if ($checkoutType === 'pay') {
            $gateways['my-wallet'] = [
                'name' => 'My Wallet',
                'image_path' => asset('front/assets/imgs/payment/my-wallet.jpg'),
                'countries' => ['all'],
            ];
        }

        // Add other payment gateways
        $gateways['paypal'] = [
            'name' => 'PayPal',
            'image_path' => asset('front/assets/imgs/payment/paypal.png'),
            'countries' => ['all'],
        ];

        $gateways['stripe'] = [
            'name' => 'Stripe',
            'image_path' => asset('front/assets/imgs/payment/stripe.png'),
            'countries' => ['all'],
        ];

        // Hidden until backend drivers are implemented in PaymentManager.

        // إضافة Local Test فقط في بيئة التطوير
        if (config('app.env') === 'local' || config('app.env') === 'development' || config('app.debug')) {
            $gateways['local-test'] = [
                'name' => 'Local Test Payment',
                'image_path' => asset('front/assets/imgs/payment/ك.jpg'), // استخدام صورة موجودة مؤقتاً
                'countries' => ['all'],
            ];
        }

        return $gateways;
    }

    /**
     * Show checkout complete page
     */
    public function checkoutComplete()
    {
        return view('front.complete_checkout');
    }
}
