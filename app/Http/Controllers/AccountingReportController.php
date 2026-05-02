<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AccountingReportController extends Controller
{
    public function studentTransactions(Request $request)
    {
        $user = Auth::user();
        if (! $user || (int) $user->type !== 0) {
            return response([
                'success' => false,
                'message' => 'unauthorized',
            ], 403);
        }

        $limit = setDataTablePerPageLimit($request->limit);
        $query = $this->baseQuery($request)
            ->with([
                'user:id,name,email',
                'user.profile:id,user_id,first_name,last_name',
                'order:id,ref_type,ref_id,status,payment,created_at',
                'paymentTransaction:id,payment_status,status,payment_channel,transaction_id,reference_id',
            ])
            ->latest('wallet_transactions.created_at');

        $transactions = paginate($query, $limit);

        foreach ($transactions as $transaction) {
            $transaction->student = $transaction->user;
            $transaction->display_transaction_type = $transaction->transaction_type
                ?: ($transaction->type === 'credit' ? 'legacy_credit' : 'legacy_debit');
            $transaction->display_status = $transaction->status ?: 'completed';
            $transaction->display_payment_gateway = $transaction->payment_gateway
                ?: $transaction->paymentTransaction?->payment_channel
                ?: $transaction->order?->payment
                ?: '-';
        }

        return response([
            'success' => true,
            'message' => 'student-accounting-report-listed-successfully',
            'result' => $transactions,
            'stats' => $this->stats($request),
        ], 200);
    }

    private function baseQuery(Request $request, bool $includeStatusFilter = true)
    {
        $query = WalletTransaction::query()->select('wallet_transactions.*');

        if ($request->transaction_type) {
            $query->where('transaction_type', $request->transaction_type);
        }

        if ($request->payment_gateway) {
            $query->where('payment_gateway', $request->payment_gateway);
        }

        if ($includeStatusFilter && $request->status) {
            if ($request->status === 'completed') {
                $query->where(function ($statusQuery) {
                    $statusQuery->where('status', 'completed')->orWhereNull('status');
                });
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->from_date) {
            $query->whereDate('wallet_transactions.created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('wallet_transactions.created_at', '<=', $request->to_date);
        }

        if (! empty($request->q)) {
            $query->whereHas('user', function ($userQuery) use ($request) {
                $userQuery->whereRaw(filterTextDB('name').' like ?', ['%'.filterText($request->q).'%'])
                    ->orWhereRaw(filterTextDB('email').' like ?', ['%'.filterText($request->q).'%']);
            });
        }

        return $query;
    }

    private function stats(Request $request): array
    {
        $completed = $this->baseQuery($request, false)
            ->where(function ($query) {
                $query->where('status', 'completed')->orWhereNull('status');
            });

        $lastMonthStart = Carbon::now()->subMonth();

        return [
            'total_completed_amount' => (float) (clone $completed)->sum('amount'),
            'last_month_completed_amount' => (float) (clone $completed)
                ->where('wallet_transactions.created_at', '>=', $lastMonthStart)
                ->sum('amount'),
            'wallet_topups_amount' => (float) (clone $completed)
                ->where('transaction_type', 'wallet_topup')
                ->sum('amount'),
            'purchases_amount' => (float) (clone $completed)
                ->where('type', 'debit')
                ->whereIn('transaction_type', [
                    'group_class_purchase',
                    'private_lesson_purchase',
                    'course_purchase',
                    'wallet_package_purchase',
                    'order_payment',
                ])
                ->sum('amount'),
            'pending_amount' => (float) $this->baseQuery($request, false)
                ->where('status', 'pending')
                ->sum('amount'),
        ];
    }
}
