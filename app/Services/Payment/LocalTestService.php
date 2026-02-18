<?php

namespace App\Services\Payment;

use App\Http\Controllers\WalletController;
use Exception;
use Illuminate\Http\Request;
use App\Models\WalletPaymentTransaction;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use App\Enums\TransactionPaymentStatus;
use App\Enums\TransactionStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LocalTestService implements PaymentInterface
{
    /**
     * Create a test payment
     * Returns a local test page URL instead of real payment gateway
     */
    public function createPayment(array $data)
    {
        // In development/test mode, return local test URL
        return [
            'type' => $data['type'],
            'url' => route('local-payment-test', ['reference_id' => $data['reference_id']]),
            'reference_id' => $data['reference_id']
        ];
    }

    /**
     * Handle webhook (not needed for local test)
     */
    public function handleWebhook(Request $request)
    {
        // No webhook needed for local test
        return response()->json(['status' => 'success']);
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        DB::beginTransaction();
        try{
            $referenceId = $request->get('reference_id');
            if(!$referenceId) {
                return response()->json([
                    'success' => false,
                    'message' => 'reference-id-required'
                ], 400);
            }

            $transaction = WalletPaymentTransaction::where('reference_id', $referenceId)->first();

            if(!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'transaction-not-found'
                ], 404);
            }

            // Update transaction status
            $transaction->payment_status = TransactionPaymentStatus::COMPLETED;
            $transaction->status = TransactionStatus::ACTIVE;
            $transaction->transaction_id = 'local-test-' . $referenceId;
            $transaction->response = json_encode([
                'test_mode' => true,
                'order_ids' => json_decode($transaction->response, true)['order_ids'],
                'completed_at' => now()->toDateTimeString(),
                'type' => json_decode($transaction->response, true)['type']
            ]);
            $transaction->save();

            // If this was a payment transaction (not topup), complete orders
            if($transaction->response) {
                $metadata = json_decode($transaction->response, true);
                if(isset($metadata['order_ids'])) {
                    $this->completeOrders($metadata['order_ids'], $transaction);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Payment completed successfully (Test Mode)',
                'transaction' => $transaction,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle canceled payment
     */
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
            'message' => 'Payment canceled (Test Mode)'
        ]);
    }

    /**
     * Complete orders after successful payment
     */
    private function completeOrders($orderIds, $transaction)
    {
        $orders = \App\Models\Order::whereIn('id', $orderIds)
            ->where('user_id', $transaction->user_id)
            ->whereIn('status', [0, 2])
            ->get();

        foreach($orders as $order) {
            $order->status = 1; // completed
            $order->payment = 'local-test';
            $order->save();

            $walletController = new WalletController();
            // $walletController->addTutorFinance($order, $order->ref_id, $order->ref_type);

            // Create wallet transaction record
            WalletTransaction::create([
                'user_id' => $transaction->user_id,
                'order_id' => $order->id,
                'type' => 'debit',
                'amount' => $order->price,
                'description' => 'Payment for order #' . $order->id . ' (Test Mode)'
            ]);
        }
    }
}

