<?php

namespace App\Services\Payment;

use App\Enums\TransactionPaymentStatus;
use App\Enums\TransactionStatus;
use App\Http\Controllers\WalletController;
use App\Models\Order;
use App\Models\Setting;
use App\Models\WalletPaymentTransaction;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class StripeService implements PaymentInterface
{
    /**
     * Create Stripe Checkout Session
     * Mirrors LocalTestService flow, except payment page is external (Stripe).
     */
    public function createPayment(array $data)
    {
        // Stripe::setApiKey(apiKey: config('services.stripe.secret', env('STRIPE_SECRET')));
         $referenceId = $data['reference_id'] ?? null;

        Stripe::setApiKey(
    Setting::valueOf(
        'stripe_secret',
        config('services.stripe.secret', env('STRIPE_SECRET'))
    )
);

        if (! $referenceId) {
            throw new \Exception('reference_id is required for Stripe payment');
        }

        $transaction = WalletPaymentTransaction::where('reference_id', $referenceId)->firstOrFail();
        $responseData = json_decode($transaction->response, true);

        $session = StripeSession::create([
            'mode' => 'payment',

            'line_items' => [[
                'price_data' => [
                    'currency' => $transaction->currency ?? 'USD',
                    'unit_amount' => (int) ($transaction->amount * 100),
                    'product_data' => [
                        'name' => 'Checkout Payment',
                    ],
                ],
                'quantity' => 1,
            ]],

            // Pass SAME metadata LocalTest depends on
            'payment_intent_data' => [
                'metadata' => [
                    'reference_id' => $transaction->reference_id,
                    'order_ids' => json_encode($responseData['order_ids'] ?? []),
                    'type' => $responseData['type'] ?? 'stripe',
                ],
            ],

            // IMPORTANT:
            // Stripe must return to FINAL PAGE, not the API endpoint
            'success_url' => route('checkout-response-get', [
                'id' => $transaction->id,
                'status' => 'success',
            ]).'?session_id={CHECKOUT_SESSION_ID}',

            'cancel_url' => route('checkout-response-get', [
                'id' => $transaction->id,
                'status' => 'cancel',
            ]),
        ]);

        return [
            'type' => 'stripe',
            'url' => $session->url,
        ];
    }

    /**
     * Handle webhook
     */
    public function handleWebhook(Request $request)
    {
        // No webhook needed for local test
        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful Stripe payment
     * EXACT same responsibilities as LocalTestService::success()
     */
    public function success(Request $request)
    {
        // Stripe::setApiKey(env('STRIPE_SECRET'));

        Stripe::setApiKey(
    Setting::valueOf(
        'stripe_secret',
        config('services.stripe.secret', env('STRIPE_SECRET'))
    )
);


        $sessionId = $request->get('session_id');
        $reference_id = $request->get('reference_id') ;
        if (! $sessionId) {
            return response()->json([
                'success' => false,
                'message' => 'session-id-required',
            ], 400);
        }

        $session = StripeSession::retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'payment-not-completed',
            ], 400);
        }

        $metadata = $session->metadata ?? [];
        $referenceId = $reference_id ?? ($metadata['reference_id'] ?? null);

        if (! $referenceId) {
            return response()->json([
                'success' => false,
                'message' => 'reference-id-missing',
            ], 400);
        }

        $transaction = WalletPaymentTransaction::where('reference_id', $referenceId)->first();

        if (! $transaction) {
            return response()->json([
                'success' => false,
                'message' => 'transaction-not-found',
            ], 404);
        }

        // Prevent double execution
        if ($transaction->payment_status === TransactionPaymentStatus::COMPLETED) {
            return response()->json([
                'success' => true,
                'transaction' => $transaction,
            ]);
        }

        // === SAME LOGIC AS LocalTest ===
        $transaction->payment_status = TransactionPaymentStatus::COMPLETED;
        $transaction->status = TransactionStatus::ACTIVE;
        $transaction->transaction_id = 'stripe-'.$session->payment_intent;
        $transaction->response = json_encode([
            'stripe_session_id' => $sessionId,
            'payment_intent' => $session->payment_intent,
            'completed_at' => now()->toDateTimeString(),
            'type' => $metadata['type'] ?? 'stripe',
            'order_ids' => json_decode($metadata['order_ids'] ?? '[]', true),
        ]);
        $transaction->save();

        // Complete orders exactly like LocalTest
        if (! empty($metadata['order_ids'])) {
            $this->completeOrders(
                json_decode($metadata['order_ids'], true),
                $transaction
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment completed successfully (Stripe)',
            'transaction' => $transaction,
        ]);
    }

    /**
     * Handle canceled Stripe payment
     * EXACT same behavior as LocalTestService::cancel()
     */
    public function cancel(Request $request)
    {
        $referenceId = $request->get('reference_id');

        if ($referenceId) {
            $transaction = WalletPaymentTransaction::where('reference_id', $referenceId)->first();
            if ($transaction) {
                $transaction->payment_status = TransactionPaymentStatus::CANCELED;
                $transaction->save();
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment canceled (Stripe)',
        ]);
    }

    /**
     * Complete orders after successful payment
     * SAME implementation as LocalTestService
     */
    private function completeOrders($orderIds, $transaction)
    {
        $orders = Order::whereIn('id', $orderIds)
            ->where('user_id', $transaction->user_id)
            ->whereIn('status', [0, 2])
            ->get();

        foreach ($orders as $order) {
            $order->status = 1; // completed
            $order->payment = 'stripe';
            $order->save();

            $walletController = new WalletController();
            $walletController->addTutorFinance($order, $order->ref_id, $order->ref_type);


            WalletTransaction::create([
                'user_id' => $transaction->user_id,
                'order_id' => $order->id,
                'type' => 'debit',
                'amount' => $order->price,
                'description' => 'Payment for order #'.$order->id.' (Stripe)',
            ]);
        }
    }
}
