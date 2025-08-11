<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Payout\PayoutRequest;
use App\Http\Requests\Payout\UpdatePayoutRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Payout;
use App\Models\Tutor;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PaypalCheckoutController;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $limit = setDataTablePerPageLimit($request->limit);
    
        $query = Payout::with('tutor');
    
        if ($user->type != 0) {
            $query->where('tutor_id', $user->id);
        }
    
        if (!empty($request->q)) {
            $query->whereHas('tutor', function ($query) use ($request) {
                $query->whereRaw(filterTextDB('name') . ' like ?', ['%' . filterText($request->q) . '%']);
            });
        }
    
        $items = $query->paginate($limit);
        
        foreach($items as $item){
            $response = json_decode($item->response);
            if($response){
                $item->paypal_status = $response->batch_status;
            }
            unset($item->response);
        }
    
        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items
        ], 200);
    }
    

    //
    public function store(PayoutRequest $request)
    {
        $user = Auth::user();

        $tutor = Tutor::find($user->id);
        if(!$tutor) {
            return response([
                    'success' => false,
                    'message' => 'tutor-dose-not-exist',
                    'msg-code' => '222'
            ], 200);
        }

        $wallet = $tutor->wallets()->first();

        if(!$wallet) {
            return response([
                'success' => false,
                'message' => 'you-don\'t-have-a-balance',
                'msg-code' => '223'
            ], 200);
        }

        if($request->amount > $wallet->balance) {
            return response([
                'success' => false,
                'message' => 'you-don\'t-have-enough-balance',
                'msg-code' => '223'
            ], 200);
        }

        $payout = new Payout();
        $payout->tutor_id = $user->id;
        $payout->amount = $request->amount;
        $payout->method = $request->method;
        if($request->method == 'bank') {
            $payout->bank_name = $request->bank_name;
            $payout->account_no = $request->account_no;
        }
        if($request->method == 'paypal') {
            $payout->paypal_account = $request->paypal_account;
        }
        $payout->save();

        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $payout
        ], 200);

    }

    public function update(UpdatePayoutRequest $request, $id)
    {

        $payout = Payout::where('id', $id)->first();
        if(!$payout) {
            return response([
                    'success' => false,
                    'message' => 'payout-dose-not-exist',
                    'msg-code' => '222'
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

            if($request->status == 'P') {
                $tutor = Tutor::find($payout->tutor_id);
                $wallet = $tutor->wallets()->first();
                $wallet->balance -= $payout->amount;
                $wallet->save();
            }

            $payout->status = $request->status;
            $payout->note = $request->note;
            $payout->save();

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
                'message' => 'payout-updated-successfully',
                'result' => $payout
        ], 200);
    }

    public function transfer(Request $request, $id) {

        $payout = Payout::where('id', $id)->first();

        if(!$payout) {
            return response([
                    'success' => false,
                    'message' => 'payout-dose-not-exist',
                    'msg-code' => '222'
            ], 200);
        }

        if($payout->method != 'paypal'){
            return response([
                    'success' => false,
                    'message' => 'you-cant-transfer-this-payout',
                    'msg-code' => '223'
            ], 200);
        }
        
        if($payout->status == 'P'){
            return response([
                    'success' => false,
                    'message' => 'this-payout-has-been-paid',
                    'msg-code' => '224'
            ], 200);
        }

        $paypalController = new PaypalCheckoutController();
        $response = $paypalController->sendPayout($payout->paypal_account, $payout->amount);

        // Check the response
        if ($response) {
            $responseData = $response->batch_header;
            $payout->response = json_encode($responseData);
            $payout->status = 'P';
            $payout->save();
            
            return response([
                    'success' => true,
                    'message' => 'transfered successfully',
                    'data' => [
                        'status'=> $responseData->batch_status
                    ]
            ], 200);
            
        } else {
            return response()->json(['message' => 'Payout failed']);
        }
    }
}