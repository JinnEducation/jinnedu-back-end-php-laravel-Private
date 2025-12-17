<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\WalletPaymentTransaction;
use App\Models\UserWallet;
use App\Enums\TransactionPaymentStatus;
use App\Enums\TransactionStatus;
use Illuminate\Support\Facades\Log;
class PayPalService implements PaymentInterface
{
    protected $clientId;
    protected $secret;
    protected $baseUrl;
    protected $accessToken;

    public function __construct()
    {
        $this->clientId = config('services.paypal.client_id',env('PAYPAL_CLIENT_ID'));
        $this->secret = config('services.paypal.secret',env('PAYPAL_SECRET'));
        $this->baseUrl = config('services.paypal.mode',env('PAYPAL_MODE')) === 'live'
            ? 'https://api.paypal.com'
            : 'https://api.sandbox.paypal.com';

        $this->accessToken = $this->generateAccessToken();
    }

    protected function generateAccessToken()
    {
        $response = Http::withBasicAuth($this->clientId, $this->secret)
            ->asForm()
            ->post("{$this->baseUrl}/v1/oauth2/token", [
                'grant_type' => 'client_credentials'
            ]);

        return $response->json('access_token');
    }

    public function createPayment(array $data)
    {
        $response = Http::withToken($this->accessToken)
            ->post("{$this->baseUrl}/v2/checkout/orders", [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    "reference_id" => $data['reference_id'],
                    'amount' => [
                        'currency_code' => $data['currency'] ?? 'USD',
                        'value' => $data['amount']
                    ],
                    'description' => $data['description'] ?? 'PayPal Payment'
                ]],
                'application_context' => [
                    'return_url' => $data['success_url'],
                    'cancel_url' => $data['cancel_url']
                ]
            ]);

        return $response->json();
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        $eventType = $payload['event_type'] ?? null;
        $resource = $payload['resource'] ?? [];
        $orderId = $resource['id'] ?? null;
        $transactionReferenceId = $resource['purchase_units'][0]['reference_id'] ?? null;

        Log::info('PayPal Webhook received', ['event_type' => $payload['event_type']]);

        if (!$transactionReferenceId) {
            Log::warning('Missing transaction reference ID.');
            return response()->json(['success' => false, 'message' => 'missing_reference'], 400);
        }

        $transaction = WalletPaymentTransaction::where('reference_id', $transactionReferenceId)->first();

        if (!$transaction) {
            Log::warning("Transaction not found for reference: {$transactionReferenceId}");
            return response()->json(['success' => false, 'message' => '"Transaction not found'], 404);
        }

        if ($payload['event_type'] === 'CHECKOUT.ORDER.APPROVED') {
    
            if ($transaction->payment_status !== TransactionPaymentStatus::COMPLETED) {
                
                $captureResponse = $this->captureOrder($orderId);

                $transaction->payment_status = TransactionPaymentStatus::CREATED;
                $transaction->response = json_encode($payload);
                $transaction->save();
            } 

        }elseif ($payload['event_type'] === 'CHECKOUT.ORDER.COMPLETED') {
            
            $transaction->transaction_id = $orderId;
            $transaction->payment_status = TransactionPaymentStatus::COMPLETED;
            $transaction->status = TransactionStatus::ACTIVE;
            $transaction->response = json_encode($payload);
            $transaction->save();

            // Update user wallet balance
            $userWallet = UserWallet::where('user_id', $transaction->user_id)->first();
            if ($userWallet) {
                $userWallet->balance += $transaction->amount;
                $userWallet->save();
            }
            
        }elseif ($payload['event_type'] === 'CHECKOUT.ORDER.DECLINED') {
    
            $transaction->payment_status = TransactionPaymentStatus::CANCELED;
            $transaction->response = json_encode($payload);
            $transaction->save();
        
        }

        return response()->json(['status' => 'success']);
    }

    public function success(Request $request, )
    {
        $orderId = $request->get('token');

        $captureResponse = $this->captureOrder($orderId);

        $status = $captureResponse['status'] ?? null;
        $success = $status === 'COMPLETED';

        return response()->json([
            'success' => true,
            'message' => $success ? 'Payment captured successfully.' : "Payment status: $status",
            'data' => $captureResponse,
        ]);
    }
    
    public function cancel(Request $request)
    {
        $referenceId = $request->get('reference_id');
        
        if($referenceId) {
            $transaction = WalletPaymentTransaction::where('reference_id', $referenceId)->first();
            if($transaction) {
                $transaction->payment_status = TransactionPaymentStatus::CANCELED;
                $transaction->save();
            }
        }
        return response()->json([
            'success' => false,
            'message' => 'payment-faild'
        ]);
    }

    public function captureOrder(string $orderId)
    {
        $response = Http::withToken($this->accessToken)
            ->post("{$this->baseUrl}/v2/checkout/orders/{$orderId}/capture");

        return $response->json();
    }
}
