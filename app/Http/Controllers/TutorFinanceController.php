<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tutor;
use App\Models\TutorFinance;
use App\Models\GroupClass;
use App\Models\Order;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TutorFinanceController extends Controller
{
    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);

        $tutor_finances = TutorFinance::query();

        if ($request->tutor_id) {
            $tutor_finances->where('tutor_id', $request->tutor_id);
        }

        if ($request->ref_type) {
            $tutor_finances->where('ref_type', $request->ref_type);
        }

        if ($request->status) {
            $tutor_finances->where('status', $request->status);
        }

        if ($request->from_date) {
            $tutor_finances->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $tutor_finances->whereDate('created_at', '<=', $request->to_date);
        }

        if (!empty($request->q)) {
            $tutor_finances->whereHas('tutor', function ($query) use ($request) {
                $query->whereRaw(filterTextDB('name') . ' like ?', ['%' . filterText($request->q) . '%']);
            });
        }
        $tutor_finances = paginate($tutor_finances, $limit);
        foreach ($tutor_finances as $item) {
            $tutor_id = $item->tutor_id;
            if ($tutor_id) {
                $item->tutor = User::where('id', $tutor_id)->first();
            }
            if($item->ref_type == 4){
                $order = Order::where('id', $item->order_id)->first();
                $item->student = User::where('id', $order->user_id)->first();
                $item->date = json_decode($order->dates,true);
            }
            if($item->ref_type == 1){
                $item->group_class = GroupClass::where('id', $item->ref_id)->first();
            }
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $tutor_finances
        ], 200);
    }

    public function myIndex(Request $request)
    {

        $user = Auth::user();

        $limit = setDataTablePerPageLimit($request->limit);

        $tutor_finances = TutorFinance::where(['tutor_id' => $user->id]);

        $tutor_finances = paginate($tutor_finances, $limit);

        foreach ($tutor_finances as $tutor_finance) {

            if ($tutor_finance->ref_type == 1) {
                $tutor_finance->group_class = GroupClass::select('name')->where('id', $tutor_finance->ref_id)->first();
            }
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $tutor_finances
        ], 200);
    }

    public function update(Request $request, $id)
    {

        $tutor_finance = TutorFinance::where('id', $id)->first();
        if (!$tutor_finance) {
            return response([
                'success' => false,
                'message' => 'tutor-finance-dose-not-exist',
                'msg-code' => '222'
            ], 200);
        }
        try {

            DB::beginTransaction();
            if ($request->status == 'transferred') {
                $tutor = User::with('wallets')->find($tutor_finance->tutor_id);
                $wallet = $tutor->wallets()->first();
                if(!$wallet){
                    $wallet = UserWallet::create([
                        'user_id' => $tutor->id,
                        'balance' => 0,
                    ]);
                }
                $wallet->balance += $tutor_finance->fee;
                $wallet->save();
            }

            $tutor_finance->status = $request->status;
            $tutor_finance->note = $request->note;
            $tutor_finance->save();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return response([
                'success' => false,
                'message' => $th->getMessage(),
                'msg-code' => '444'
            ], 200);
        }

        return response([
            'success' => true,
            'message' => 'tutor_finance-updated-successfully',
            'result' => $tutor_finance
        ], 200);
    }

    // public function transfer(Request $request, $id)
    // {

    //     $tutor_finance = TutorFinance::where('id', $id)->first();

    //     if (!$tutor_finance) {
    //         return response([
    //             'success' => false,
    //             'message' => 'tutor-finance-dose-not-exist',
    //             'msg-code' => '222'
    //         ], 200);
    //     }

    //     if ($tutor_finance->method != 'paypal') {
    //         return response([
    //             'success' => false,
    //             'message' => 'you-cant-transfer-this-payout',
    //             'msg-code' => '223'
    //         ], 200);
    //     }

    //     if ($tutor_finance->status == 'P') {
    //         return response([
    //             'success' => false,
    //             'message' => 'this-tutor_finance-has-been-paid',
    //             'msg-code' => '224'
    //         ], 200);
    //     }

    //     $paypalController = new PaypalCheckoutController();
    //     $response = $paypalController->sendPayout($tutor_finance->paypal_account, $tutor_finance->amount);

    //     // Check the response
    //     if ($response) {
    //         $responseData = $response->batch_header;
    //         $tutor_finance->response = json_encode($responseData);
    //         $tutor_finance->status = 'P';
    //         $tutor_finance->save();

    //         return response([
    //             'success' => true,
    //             'message' => 'transfered successfully',
    //             'data' => [
    //                 'status' => $responseData->batch_status
    //             ]
    //         ], 200);
    //     } else {
    //         return response()->json(['message' => 'tutor_finance failed']);
    //     }
    // }
}
