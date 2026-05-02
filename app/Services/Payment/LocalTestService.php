<?php

namespace App\Services\Payment;

use App\Enums\TransactionPaymentStatus;
use App\Enums\TransactionStatus;
use App\Models\WalletPaymentTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'reference_id' => $data['reference_id'],
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
        try {
            $referenceId = $request->get('reference_id');
            if (! $referenceId) {
                return response()->json([
                    'success' => false,
                    'message' => 'reference-id-required',
                ], 400);
            }

            $transaction = WalletPaymentTransaction::where('reference_id', $referenceId)->first();

            if (! $transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'transaction-not-found',
                ], 404);
            }

            $prev = json_decode($transaction->response, true) ?: [];

            // Update transaction status without dropping checkout metadata used later.
            $transaction->payment_status = TransactionPaymentStatus::COMPLETED;
            $transaction->status = TransactionStatus::ACTIVE;
            $transaction->transaction_id = 'local-test-'.$referenceId;
            $transaction->response = json_encode(array_merge($prev, [
                'test_mode' => true,
                'completed_at' => now()->toDateTimeString(),
                'type' => $prev['type'] ?? 'local-test',
            ]));
            $transaction->save();

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

        if ($referenceId) {
            $transaction = WalletPaymentTransaction::where('reference_id', $referenceId)->first();
            if ($transaction) {
                $transaction->payment_status = TransactionPaymentStatus::CANCELED;
                $transaction->save();
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment canceled (Test Mode)',
        ]);
    }
}
