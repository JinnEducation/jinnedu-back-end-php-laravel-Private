<?php

namespace App\Http\Controllers\Front;

use App\Enums\TransactionPaymentStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\WalletController;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

                $totalAmount = $orders->sum('price');
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

        // Handle discount code if provided
        $discountAmount = 0;
        if ($request->has('discount_code') && ! empty($request->discount_code)) {
            $discountResult = $this->calculateDiscount($request->discount_code, $request->amount);
            if($discountResult['valid']){
                $discountAmount = $discountResult['discount'];
            }else{
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
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'no-orders-found',
            ], 404);
        }

        // // Filter only pending (0) or failed (2) orders for payment
        // $validOrders = $orders->filter(function($order) {
        //     return in_array($order->status, [0, 2]);
        // });

        // if($validOrders->isEmpty()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'no-valid-orders-found',
        //         'details' => 'Orders are already paid or have invalid status'
        //     ], 422);
        // }

        // Use only valid orders
        // $orders = $orders;
        $totalAmount = $orders->sum('price');
        $finalAmount = max(0, $totalAmount - $discountAmount);

        // If paying from wallet
        if ($request->payment_gateway === 'my-wallet') {
            return $this->payFromWallet($user, $orders, $finalAmount);
        }

        // Create payment transaction
        $transaction = \App\Models\WalletPaymentTransaction::create([
            'user_id' => $user->id,
            'amount' => $finalAmount,
            'payment_channel' => $request->payment_gateway,
            'current_wallet' => $user->wallets()->first()?->balance ?? 0,
            'reference_id' => (string) \Illuminate\Support\Str::uuid(),
            'response' => json_encode(['order_ids' => $orderIds, 'type' => 'pay', 'payment_gateway' => $request->payment_gateway]), // Store order IDs in response field
        ]);

        // Process payment gateway
        return $this->processPaymentGateway($transaction, $request, 'pay');
    }

    /**
     * Pay directly from wallet
     */
    private function payFromWallet($user, $orders, $amount)
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

        // Deduct from wallet
        $wallet->balance -= $amount;
        $wallet->save();

        // Update orders status
        foreach ($orders as $order) {
            $order->status = 1; // completed
            $order->payment = 'wallet';
            $order->save();

            $walletController = new WalletController();
            $walletController->addTutorFinance($order,$order->ref_id, $order->ref_type);

            // Create wallet transaction record
            \App\Models\WalletTransaction::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'type' => 'debit',
                'amount' => $order->price,
                'description' => 'Payment for order #'.$order->id,
            ]);
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

                    return redirect()->route('checkout')
                        ->with('error', $resultData['message'] ?? 'Payment processing failed');
                }
            } catch (\Exception $e) {
                \Log::error('Payment Response Error: '.$e->getMessage());

                // Mark transaction as failed
                $transaction->payment_status = TransactionPaymentStatus::CANCELED;
                $transaction->save();

                // throw $e;

                return redirect()->route('checkout')
                    ->with('error', 'Payment processing failed: '.$e->getMessage());
            }
        } else {
            $transaction->payment_status = TransactionPaymentStatus::CANCELED;
            $transaction->save();

            return redirect()->route('checkout')
                ->with('error', 'Payment was canceled');
        }
    }

    /**
     * Complete transaction and update wallet
     */
    private function completeTransaction($transaction, $type)
    {
        // Update transaction status
        $transaction->payment_status = TransactionPaymentStatus::COMPLETED;
        $transaction->status = TransactionStatus::ACTIVE;
        $transaction->save();

        // Add amount to wallet
        $wallet = $transaction->user->wallets()->first();
        if (! $wallet) {
            $wallet = UserWallet::create([
                'user_id' => $transaction->user_id,
                'balance' => 0,
            ]);
        }

        if ($type == 'topup') {
            $wallet->update([
                'balance' => $wallet->balance + $transaction->amount,
            ]);
        }

        // Create wallet transaction record
        WalletTransaction::create([
            'user_id' => $transaction->user_id,
            'type' => 'credit',
            'amount' => $transaction->amount,
            'description' => 'Wallet top-up via '.$transaction->payment_channel,
        ]);

        // If this was a payment transaction (not topup), complete orders
        if ($transaction->response) {
            $metadata = json_decode($transaction->response, true);
            if (isset($metadata['order_ids'])) {
                $this->completeOrders($metadata['order_ids'], $transaction);
            }
        }
    }

    /**
     * Complete orders after successful payment
     */
    private function completeOrders($orderIds, $transaction)
    {
        $orders = Order::whereIn('id', $orderIds)
            ->where('user_id', $transaction->user_id)
            ->whereIn('status', [0, 2])
            ->get();

        foreach ($orders as $order) {
            $order->status = 1; // completed
            $order->payment = $transaction->payment_channel;
            $order->save();

            // Create wallet transaction record
            \App\Models\WalletTransaction::create([
                'user_id' => $transaction->user_id,
                'order_id' => $order->id,
                'type' => 'debit',
                'amount' => $order->price,
                'description' => 'Payment for order #'.$order->id,
            ]);
        }
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

        $gateways['jawal-pay'] = [
            'name' => 'Jawal Pay',
            'image_path' => asset('front/assets/imgs/payment/jawal-pay.jpg'),
            'countries' => ['palestine', 'jordan'],
        ];

        $gateways['palpay'] = [
            'name' => 'PalPay',
            'image_path' => asset('front/assets/imgs/payment/palpay.jpg'),
            'countries' => ['palestine'],
        ];

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
