<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use App\Models\WalletPaymentTransaction;
use App\Models\UserWallet;
use App\Enums\TransactionPaymentStatus;
use App\Enums\TransactionStatus;
use Illuminate\Support\Facades\Log;
class StripeService implements PaymentInterface
{
    public function createPayment(array $data)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.stripe_secret'));

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $data['currency'] ?? 'USD',
                        'unit_amount' => $data['amount'] * 100,
                        'product_data' => [
                            'name' => 'Wallet Charge',
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'payment_intent_data' => [  
                'metadata' => [
                    'reference_id' => $data['reference_id']
                ],
            ],
            'mode' => 'payment',
            'success_url' => $data['success_url'] . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $data['cancel_url']
        ]);

        return $session;
    }

    public function handleWebhook(Request $request)
    {
        $endpointSecret = config('services.stripe.stripe_webhook_secret');
        $payload = $request->getContent();
        $sigHeader = $request->server('HTTP_STRIPE_SIGNATURE');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            abort(400, 'Webhook Error');
        }

        if($event->type === 'payment_intent.created') {
            $paymentIntent = $event->data->object;
            $metadata = $paymentIntent->metadata;
            $transactionId = $paymentIntent->id;
            
            $transaction = WalletPaymentTransaction::where('reference_id', $metadata['reference_id'])->first();
    
            if ($transaction && $transaction->payment_status !== TransactionPaymentStatus::COMPLETED) {
                $transaction->transaction_id = $transactionId;
                $transaction->payment_status = TransactionPaymentStatus::CREATED;
                $transaction->response = json_encode($event->data->object);
                $transaction->save();
            } else {
                \Log::warning("Transaction ID not found" . $transactionId);
            }

        }elseif ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;
            $metadata = $paymentIntent->metadata;
            $transactionId = $paymentIntent->id;
            
            $transaction = WalletPaymentTransaction::where('reference_id', $metadata['reference_id'])->first();
    
            if ($transaction) {
                $transaction->transaction_id = $transactionId;
                $transaction->payment_status = TransactionPaymentStatus::COMPLETED;
                $transaction->status = TransactionStatus::ACTIVE;
                $transaction->response = json_encode($event->data->object);
                $transaction->save();

                $user_wallet = UserWallet::where('user_id', $transaction->user_id)->first();
                if ($user_wallet) {
                    $user_wallet->balance += $transaction->amount;
                    $user_wallet->save();
                }
                
            } else {
                \Log::warning("Transaction ID not found" . $metadata['transaction_id']);
            }
        }elseif ($event->type === 'payment_intent.canceled' || $event->type === 'payment_intent.payment_failed') {
            $paymentIntent = $event->data->object;
            $metadata = $paymentIntent->metadata;
            $transactionId = $paymentIntent->id;
            
            $transaction = WalletPaymentTransaction::where('reference_id', $metadata['reference_id'])->first();
    
            if ($transaction) {
                $transaction->transaction_id = $transactionId;
                $transaction->payment_status = TransactionPaymentStatus::CANCELED;
                $transaction->response = json_encode($event->data->object);
                $transaction->save();
            } else {
                \Log::warning("Transaction ID not found" . $metadata['transaction_id']);
            }
        }
        return response()->json(['status' => 'success']);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        if (!$sessionId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Session ID is required.'
            ], 400);
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.stripe_secret'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
            $status = $paymentIntent->status;

            return response()->json([
                'status' => true,
                'message' => $status === 'succeeded' ? 'Payment captured successfully.' : "Payment status: $status",
                'data' => $paymentIntent
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve payment status: ' . $e->getMessage()
            ], 500);
        }
    }

    
    public function cancel(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'payment-faild'
        ]);
    }
}
