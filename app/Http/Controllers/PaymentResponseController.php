<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Facades\DB;

class PaymentResponseController extends Controller
{
    public function handlePaymentResponse(Request $request,$orderid,$status){
	   
        $order = Order::where('id' , $orderid)
                    // ->where('token' , $request->token)
                    ->where('ref_type' , 5)
                    ->where('status' , 0)
                    ->first();

        if(!$order) 
            return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '222'
                ] , 200);

    	if($status=='success') {

            $order->metadata = '{"status":"APPROVED"}';
    		$order->status=1;
    		$order->save();

            $user = User::find($order->ref_id);
            $wallet = $user->wallets()->first();
            
            if(!$wallet){
                $wallet = new UserWallet;
                $wallet->user_id = $user->id;
                $wallet->balance = $order->price;
                $wallet->save();
            }else{
                $wallet->balance += $order->price;
                $wallet->save();
            }
            
            return response([
                    'success' => true,
                    'message' => 'topup-added-successfully',
                    'order' => $order,
                    'wallet' => $wallet
            ] , 200);

            
    	}else {
            $responseData='{"status":"FAILED"}';
            $order->metadata = json_decode($responseData);
    		 $order->status=2;
    		$order->save();

            return response([
                        'success' => false,
                        'message' => 'payment-faild',
                        'order' => $order
                ] , 200);
        }
	    
	}
}
