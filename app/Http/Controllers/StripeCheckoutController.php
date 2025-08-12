<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Facades\DB;

class StripeCheckoutController extends Controller
{
    public function checkout($order_id){

        $order=Order::where('id' , $order_id)->where('ref_type' , 5)->where('status' , 0)->first();
        if(!$order) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '222'
        ] , 200);
        
        $amount=round($order->price*$order->currency_exchange,2);
        
        if($amount<=0) return response([
                'success' => false,
                'message' => 'amount-not-vaild',
                'msg-code' => '333'
        ] , 200);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $order->currency_code,
                        'unit_amount' => $amount * 100,
                        'product_data' => [
                            'name' => 'Order Payment',
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => 'https://jinntest.jinnedu.com/checkout-response/'.$order->id.'/success',
            'cancel_url' =>'https://jinntest.jinnedu.com/checkout-response/'.$order->id.'/failed'
        ]);

        $order->token = $session->id;
        $order->payment = 'stripe';
        $order->save();
        return redirect($session->url);
        return response([
            'success' => true,
            'session_url' => $session->url
        ], 200);
    }

    public function success(Request $request){
        
        try{

            DB::beginTransaction();

            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

            $session_id = $request->get('session_id');

            $session = \Stripe\Checkout\Session::retrieve($session_id);
            
            if(! $session){
                return response([
                        'success' => false,
                        'message' => 'item-dose-not-exist',
                        'msg-code' => '222'
                ] , 200);
            }

            $order = Order::where('token' , $session->id)->where('ref_type' , 5)->where('status' , 0)->first();
            if(!$order) return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '222'
            ] , 200);
            $order->status = 1;
            $order->save();

            // charge user wallet
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
            

            DB::commit();

            return response([
                    'success' => true,
                    'message' => 'topup-added-successfully',
                    'order' => $order,
                    'wallet' => $wallet
            ] , 200);
        }catch (\Throwable $th) {
           
            DB::rollBack();
    
            return response([
                    'success' => false,
                    'message' => $th->getMessage(),
                    'msg-code' => $th->getCode()
            ] , 200);

        }
            
    }

    public function cancel(){

    }
}
