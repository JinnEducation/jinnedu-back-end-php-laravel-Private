<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payout\PayoutRequest;
use App\Http\Requests\Payout\UpdatePayoutRequest;
use App\Models\Payout;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    private function normalizeNote(Request $request): ?string
    {
        return $request->note ?? $request->notes;
    }

    private function applyPaidStatusAndDeductWallet(Payout $payout, ?string $note = null): void
    {
        if ($payout->status === 'P') {
            throw new \RuntimeException('this-payout-has-been-paid');
        }

        $tutor = Tutor::find($payout->tutor_id);
        if (! $tutor) {
            throw new \RuntimeException('tutor-dose-not-exist');
        }

        $wallet = $tutor->wallets()->lockForUpdate()->first();
        if (! $wallet) {
            throw new \RuntimeException('you-don\'t-have-a-balance');
        }

        if ($wallet->balance < $payout->amount) {
            throw new \RuntimeException('you-don\'t-have-enough-balance');
        }

        $wallet->balance -= $payout->amount;
        $wallet->save();

        $payout->status = 'P';
        if ($note !== null) {
            $payout->note = $note;
        }
        $payout->save();
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $limit = setDataTablePerPageLimit($request->limit);

        $query = Payout::with('tutor');

        if ($user->type != 0) {
            $query->where('tutor_id', $user->id);
        }

        if (! empty($request->q)) {
            $query->whereHas('tutor', function ($query) use ($request) {
                $query->whereRaw(filterTextDB('name').' like ?', ['%'.filterText($request->q).'%']);
            });
        }

        $items = $query->paginate($limit);

        foreach ($items as $item) {
            $response = json_decode($item->response);
            if ($response) {
                $item->paypal_status = $response->batch_status;
            }
            if (! $item->payout_status) {
                $item->payout_status = 'pending';
            }
            unset($item->response);
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items,
        ], 200);
    }

    //
    public function store(PayoutRequest $request)
    {
        $user = Auth::user();

        $tutor = Tutor::find($user->id);
        if (! $tutor) {
            return response([
                'success' => false,
                'message' => 'tutor-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        $wallet = $tutor->wallets()->first();

        if (! $wallet) {
            return response([
                'success' => false,
                'message' => 'you-don\'t-have-a-balance',
                'msg-code' => '223',
            ], 200);
        }

        if ($request->amount > $wallet->balance) {
            return response([
                'success' => false,
                'message' => 'you-don\'t-have-enough-balance',
                'msg-code' => '223',
            ], 200);
        }

        $payout = new Payout;
        $payout->tutor_id = $user->id;
        $payout->amount = $request->amount;
        $payout->method = $request->method;
        $payout->status = 'R';
        if ($request->method == 'bank') {
            $payout->bank_name = $request->bank_name;
            $payout->bank_account_name = $request->bank_account_name;
            $payout->iban = $request->iban;
            $payout->swift_code = $request->swift_code;
            $payout->country = $request->country;
        }
        if ($request->method == 'paypal') {
            $payout->paypal_account = $request->paypal_account;
        }
        $payout->payout_status = 'pending';
        $payout->save();

        return response([
            'success' => true,
            'message' => 'item-added-successfully',
            'result' => $payout,
        ], 200);

    }

    public function update(UpdatePayoutRequest $request, $id)
    {

        $payout = Payout::where('id', $id)->first();
        if (! $payout) {
            return response([
                'success' => false,
                'message' => 'payout-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        // if($payout->status == 'P') {
        //     return response([
        //             'success' => false,
        //             'message' => 'this-payout-has-been-paid',
        //             'msg-code' => '444'
        //     ], 400);
        // }

        try {

            DB::beginTransaction();

            if ($request->status == 'P') {
                $this->applyPaidStatusAndDeductWallet($payout, $this->normalizeNote($request));
            } else {
                $payout->status = $request->status;
                $payout->note = $this->normalizeNote($request);
                $payout->save();
            }

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();

            return response([
                'success' => false,
                'message' => $th->getMessage(),
                'msg-code' => '444',
            ], 200);
        }

        return response([
            'success' => true,
            'message' => 'payout-updated-successfully',
            'result' => $payout,
        ], 200);
    }

    public function approve(Request $request, $id)
    {
        $payout = Payout::where('id', $id)->first();

        if (! $payout) {
            return response([
                'success' => false,
                'message' => 'payout-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        if ($payout->status == 'P') {
            return response([
                'success' => false,
                'message' => 'this-payout-has-been-paid',
                'msg-code' => '224',
            ], 200);
        }

        try {
            DB::beginTransaction();
            $this->applyPaidStatusAndDeductWallet($payout, $this->normalizeNote($request));
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response([
                'success' => false,
                'message' => $th->getMessage(),
                'msg-code' => '444',
            ], 200);
        }

        return response([
            'success' => true,
            'message' => 'Payout approved successfully',
        ], 200);
    }

    public function updatePayoutStatus(Request $request, $id)
    {
        $request->validate([
            'payout_status' => ['required', 'in:pending,transferred'],
        ]);

        $payout = Payout::where('id', $id)->first();

        if (! $payout) {
            return response([
                'success' => false,
                'message' => 'payout-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        $payout->payout_status = $request->payout_status;
        $payout->save();

        return response([
            'success' => true,
            'message' => 'payout-status-updated-successfully',
            'result' => $payout,
        ], 200);
    }

    public function transfer(Request $request, $id)
    {

        $payout = Payout::where('id', $id)->first();

        if (! $payout) {
            return response([
                'success' => false,
                'message' => 'payout-dose-not-exist',
                'msg-code' => '222',
            ], 200);
        }

        if ($payout->method != 'paypal') {
            return response([
                'success' => false,
                'message' => 'you-cant-transfer-this-payout',
                'msg-code' => '223',
            ], 200);
        }

        if ($payout->status == 'P') {
            return response([
                'success' => false,
                'message' => 'this-payout-has-been-paid',
                'msg-code' => '224',
            ], 200);
        }

        $paypalController = new PaypalCheckoutController;
        $response = $paypalController->sendPayout($payout->paypal_account, $payout->amount);

        // Check the response
        if ($response && isset($response->batch_header)) {
            $responseData = $response->batch_header;

            try {
                DB::beginTransaction();
                $payout->response = json_encode($responseData);
                $payout->save();
                $this->applyPaidStatusAndDeductWallet($payout, $this->normalizeNote($request));
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();

                return response([
                    'success' => false,
                    'message' => $th->getMessage(),
                    'msg-code' => '444',
                ], 200);
            }

            return response([
                'success' => true,
                'message' => 'transfered successfully',
                'data' => [
                    'status' => $responseData->batch_status,
                ],
            ], 200);

        } else {
            return response()->json(['message' => 'Payout failed']);
        }
    }
}
