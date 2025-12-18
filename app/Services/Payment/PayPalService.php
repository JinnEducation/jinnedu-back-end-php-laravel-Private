<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\WalletPaymentTransaction;
use App\Models\WalletTransaction;
use App\Models\Order;
use App\Http\Controllers\Front\WalletController;
use App\Enums\TransactionPaymentStatus;
use App\Enums\TransactionStatus;

class PayPalService implements PaymentInterface
{
    private string $clientId;
    private string $clientSecret;
    private string $baseUrl;

    public function __construct()
    {
        // IMPORTANT: fallback to env if config missing (as requested)
        $this->clientId = config('services.paypal.client_id', env(key: 'PAYPAL_CLIENT_ID'));
        $this->clientSecret = config('services.paypal.client_secret', env(key: 'PAYPAL_CLIENT_SECRET'));

        $mode = config('services.paypal.mode', env(key: 'PAYPAL_MODE'));
        $mode = $mode ?: 'sandbox';

        $this->baseUrl = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Create PayPal order and return approval URL.
     * Mirrors LocalTest/Stripe pattern:
     * - CheckoutController stays unchanged
     * - Gateway handles its own transaction/order completion in success()
     */
    public function createPayment(array $data)
    {
        // LocalTest-style contract: reference_id is the primary key
        $referenceId = $data['reference_id'] ?? null;
        if (!$referenceId) {
            throw new \InvalidArgumentException('reference_id is required');
        }

        $transaction = WalletPaymentTransaction::where('reference_id', $referenceId)->firstOrFail();
        $responseData = json_decode($transaction->response, true) ?: [];

        // PayPal must return to a FINAL PAGE (not JSON endpoint).
        // CheckoutController currently passes success_url/cancel_url that may point to /payment-response.
        // We convert those to checkout-response-get using the transaction id embedded in the URL.
        $returnUrl = $this->toFinalReturnUrl($data['success_url'] ?? '', $transaction->id, 'success');
        $cancelUrl = $this->toFinalReturnUrl($data['cancel_url'] ?? '', $transaction->id, 'cancel');

        $payload = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $referenceId,
                    'description'  => $data['description'] ?? 'Checkout Payment',
                    'amount' => [
                        'currency_code' => $data['currency'] ?? ($transaction->currency ?? 'USD'),
                        'value' => number_format((float) $data['amount'], 2, '.', ''),
                    ],
                    // Store metadata for traceability (PayPal supports custom_id / invoice_id)
                    'custom_id' => $referenceId,
                ],
            ],
            'application_context' => [
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
                'user_action' => 'PAY_NOW',
                'shipping_preference' => 'NO_SHIPPING',
            ],
        ];

        $token = $this->getAccessToken();

        $res = Http::withToken($token)
            ->acceptJson()
            ->post($this->baseUrl . '/v2/checkout/orders', $payload);

        if (!$res->successful()) {
            return [
                'success' => false,
                'message' => 'paypal-create-order-failed',
                'error' => $res->body(),
            ];
        }

        $body = $res->json();

        // Persist PayPal order id into transaction response for debugging/trace
        $transaction->response = json_encode(array_merge($responseData, [
            'paypal_order_id' => $body['id'] ?? null,
            'type' => $responseData['type'] ?? ($data['type'] ?? 'paypal'),
        ]));
        $transaction->save();

        // Return raw body so CheckoutController::extractPaymentUrl can grab the approve URL from links
        return $body;
    }

    /**
     * Handle successful payment.
     * EXACT same responsibilities as LocalTestService::success():
     * - update WalletPaymentTransaction
     * - complete orders (if any)
     * - return JSON
     */
    public function success(Request $request)
    {
        $referenceId = $request->get('reference_id');
        if (!$referenceId) {
            return response()->json([
                'success' => false,
                'message' => 'reference-id-required',
            ], 400);
        }

        $transaction = WalletPaymentTransaction::where('reference_id', $referenceId)->first();
        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'transaction-not-found',
            ], 404);
        }

        // Prevent double execution
        if ($transaction->payment_status === TransactionPaymentStatus::COMPLETED) {
            return response()->json([
                'success' => true,
                'message' => 'already-processed',
                'transaction' => $transaction,
            ]);
        }

        // PayPal returns "token" query param (order id)
        $paypalOrderId = $request->get('token');
        if (!$paypalOrderId) {
            return response()->json([
                'success' => false,
                'message' => 'paypal-token-required',
            ], 400);
        }

        $token = $this->getAccessToken();

        $captureRes = Http::withToken($token)
            ->acceptJson()
            ->post($this->baseUrl . '/v2/checkout/orders/' . $paypalOrderId . '/capture');

        if (!$captureRes->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'paypal-capture-failed',
                'error' => $captureRes->body(),
            ], 500);
        }

        $capture = $captureRes->json();
        $status = $capture['status'] ?? null;

        if ($status !== 'COMPLETED') {
            return response()->json([
                'success' => false,
                'message' => 'paypal-payment-not-completed',
                'paypal_status' => $status,
            ], 400);
        }

        $prev = json_decode($transaction->response, true) ?: [];
        $orderIds = $prev['order_ids'] ?? [];

        // === SAME LOGIC AS LocalTest (transaction updates) ===
        $transaction->payment_status = TransactionPaymentStatus::COMPLETED;
        $transaction->status = TransactionStatus::ACTIVE;
        $transaction->transaction_id = 'paypal-' . $paypalOrderId;
        $transaction->response = json_encode(array_merge($prev, [
            'paypal_order_id' => $paypalOrderId,
            'paypal_capture'  => [
                'id' => $capture['id'] ?? null,
                'status' => $status,
            ],
            'completed_at' => now()->toDateTimeString(),
            'type' => $prev['type'] ?? 'paypal',
        ]));
        $transaction->save();

        // === SAME LOGIC AS LocalTest (complete orders) ===
        if (!empty($orderIds)) {
            $this->completeOrders($orderIds, $transaction);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment completed successfully (PayPal)',
            'transaction' => $transaction,
        ]);
    }

    /**
     * Handle canceled payment.
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
            'message' => 'Payment canceled (PayPal)',
        ]);
    }

    /**
     * Handle webhook
     */
    public function handleWebhook(Request $request)
    {
        // Optional: implement later if needed.
        return response()->json(['status' => 'success']);
    }

    /**
     * Complete orders after successful payment
     * SAME implementation as LocalTestService / StripeService
     */
    private function completeOrders($orderIds, $transaction)
    {
        $orders = Order::whereIn('id', $orderIds)
            ->where('user_id', $transaction->user_id)
            ->whereIn('status', [0, 2])
            ->get();

        foreach ($orders as $order) {
            $order->status = 1; // completed
            $order->payment = 'paypal';
            $order->save();

            if ($order->ref_type == 4) {
                $walletController = new WalletController();
                $walletController->addTutorFinance($order, $order->ref_id, 4);
            }

            // Create wallet transaction record
            WalletTransaction::create([
                'user_id' => $transaction->user_id,
                'order_id' => $order->id,
                'type' => 'debit',
                'amount' => $order->price,
                'description' => 'Payment for order #' . $order->id . ' (PayPal)',
            ]);
        }
    }

    /**
     * PayPal OAuth token
     */
    private function getAccessToken(): string
    {
        if (!$this->clientId || !$this->clientSecret) {
            throw new \RuntimeException('PayPal credentials are missing (client_id/client_secret)');
        }

        $res = Http::asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->acceptJson()
            ->post($this->baseUrl . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (!$res->successful()) {
            throw new \RuntimeException('PayPal token request failed: ' . $res->body());
        }

        $json = $res->json();
        return $json['access_token'] ?? '';
    }

    /**
     * Convert controller-provided URLs to a FINAL PAGE URL (checkout-response-get).
     * If the provided URL is empty/unusable, fallback to direct route construction.
     */
    private function toFinalReturnUrl(string $incomingUrl, int $transactionId, string $status): string
    {
        // Preferred: always use final page route
        // If your project uses a different name, adjust only here.
        try {
            return route('checkout-response-get', [
                'id' => $transactionId,
                'status' => $status,
            ]);
        } catch (\Throwable $e) {
            // Fallback to whatever controller gave us
            return $incomingUrl;
        }
    }
}
