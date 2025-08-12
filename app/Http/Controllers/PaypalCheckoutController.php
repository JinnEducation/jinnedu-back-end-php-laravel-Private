<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BraincertController;
use App\Http\Controllers\ConferenceController;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Conference;

use App\Models\GroupClass;
use App\Models\GroupClassDate;
use App\Models\GroupClassOutline;
use App\Models\GroupClassLang;
use App\Models\GroupClassStudent;

use App\Models\OurCourse;
use App\Models\OurCourseDate;
use App\Models\OurCourseLevel;
use App\Models\OurCourseLang;

use App\Models\User;
use App\Models\UserWallet;

class PaypalCheckoutController extends Controller
{
    public $clientId;
    public $secret;
    public $base_url;
    public $curl_options;
    
    function __construct() {
        $this->clientId = "ARNxuAT7U5q0QCmwPttBFYY1czRwM-tl5ubhK06g0C5rT_6pJ0bIuAX2ZbM8MjGU_Um_CZkDGaKF1kWi";
        $this->secret = "EOE4E9HvjYNNvQJZgEGW_KUakDmFCBi2gk3ONkQzWU13smYdJYuR8Yasr8lHJTqNrFJPuW6e9ZToNaFK";
        $this->base_url='https://api.sandbox.paypal.com/';
        $this->curl_options = array(
        	CURLOPT_RETURNTRANSFER => true,     // return web page
        	CURLOPT_HEADER         => false,    // don't return headers
        	CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        	CURLOPT_ENCODING       => "",       // handle all encodings
        	CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 10.0; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0", // who am i
        	CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        	CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        	CURLOPT_TIMEOUT        => 120,      // timeout on response
        	CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        	CURLOPT_SSL_VERIFYPEER => false,     // Disabled SSL Cert checks
        	//CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        	//CURLOPT_PROXY => "0.0.0.0",
        	//CURLOPT_PROXYPORT => "80",
        );
    }
    
    public function paypalGetAccessToken(){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSLVERSION , 6); //NEW ADDITION
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_USERPWD, $this->clientId.":".$this->secret);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        
        $result = curl_exec($ch);
        
        curl_close($ch); //THIS CODE IS NOW WORKING!
        
        //dd($result);
        
        if(empty($result)) die("Error: No response.");
        else
        {
            $json = json_decode($result);
            //print_r($json->access_token);
            return $json->access_token;
        }
        
    }
    
    public function paypalRequest($id){
       //dd('hyperpayRequest');
       
       //dd($access_token);
        //$user=Sentinel::getUser();
	    //if(!$user) return redirect(route('login'));
	   
        $order=Order::where('id' , $id)->where('status' , 0)->first();
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
        
        //$paymentway = $this->posts_select_by_type(-40,'full')->where('post.id',860)->first();
        //if(!$paymentway) return redirect(route('orders'));
        
        //$addresse=Addresses::where('userid' , $user->id)->where('id' , $order->ship_to)->first();
        //if(!$paymentway) return redirect(route('orders'));
       
       
        
       
	   if(true){
           $access_token = $this->paypalGetAccessToken();
            
           $url = "https://api-m.sandbox.paypal.com/v2/checkout/orders";
	       $data=[
                     "intent" => "CAPTURE",
                     "purchase_units" => [[
                         "reference_id" => $order->id,
                         "amount" => [
                             "value" => $amount,
                             "currency_code" => $order->currency_code
                         ]
                     ]],
                     "application_context" => [
                          "cancel_url" => url('/').'/payment-response/'.$order->id.'/cancel',
                          "return_url" => url('/').'/payment-response/'.$order->id.'/success'
                     ] 
                 ];

        
        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $url);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                           'Authorization:Bearer '.$access_token,
                           'Content-Type: application/json'));
        	curl_setopt($ch, CURLOPT_POST, 1);
        	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	$responseData = curl_exec($ch);
        	if(curl_errno($ch)) {
        		return curl_error($ch);
        	}
        	curl_close($ch);
        	//dd( $responseData );
    		$json = json_decode($responseData);
    		$order->token=$json->id;
    		$order->payment='paypal';
    		$order->save();
    		//=============================================================
    		//$url = "https://api.sandbox.paypal.com/v2/checkout/orders/".$json->id;
    		$url = "https://www.sandbox.paypal.com/checkoutnow?token=".$json->id;
            //return redirect($url);
            return view('paypal.order', compact('json','order') );
    		//return view('front-teachmearabic.paypal', compact('responseData','id','order') );
	   }else return dd('error 2');
		
    }
    
    public function paypalResponse($orderid,$status){
     
        //dd('lll');
        $access_token = $this->paypalGetAccessToken();
        
        /*
        $user=Sentinel::getUser();
	    if(!$user) return redirect(route('login'));
	   */
	   
        $order=Order::where('id' , $orderid)->whereIn('status',[0,2])->first();
        if(!$order) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '222'
        ] , 200);
 
        if(false){
        	$url = "https://api.sandbox.paypal.com/v2/checkout/orders/".$order->token;
        
        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                               'Authorization:Bearer '.$access_token,
                               'Content-Type: application/json'));
        	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	$responseData = curl_exec($ch);
        	if(curl_errno($ch)) {
        		return curl_error($ch);
        	}
        	curl_close($ch);
        }else{
            if($status=='success') $responseData='{"status":"APPROVED"}';
            else $responseData='{"status":"FAILED"}';
        }
    	//dd($responseData);
    	$json = json_decode($responseData);
    	
    	if($order->status==0){
        	$order->metadata=$responseData;
    		if(isset($json->status) && $json->status=='APPROVED') $order->status=1;
    		else $order->status=2;
    		$order->save();
    	}
    	if(isset($json->status) && $json->status=='APPROVED') {
    	    //=====================================================================
    	    $time = strtotime(date('Y-m-d'));
            $end_date = date("Y-m-d", strtotime("+1 month", $time));
            
            if($order->ref_type==1){
                $groupClass = GroupClass::find($order->ref_id);
                $dates = $groupClass->dates()->get();
                $conferences = Conference::where('ref_id',$groupClass->id)->where('ref_type',0)->where('order_id',0)->get();
                //dd($dates);
                
                $gcStudent=new GroupClassStudent;
        	    $gcStudent->student_id = $order->user_id;
        	    $gcStudent->class_id = $groupClass->id;
        	    $gcStudent->order_id=$order->id;
        	    $gcStudent->save();
                 
            }else if($order->ref_type==2){
                $conference = new ConferenceController;
    	        $conferences= $conference->createOurCourseConferences($order);
            }else if($order->ref_type==4){
                $conference = new ConferenceController;
    	        $conferences= $conference->createPrivateLessonConference($order);
            }else if($order->ref_type==5){
                
                if(true){
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
                }
            }
            
    	   
    	    //=====================================================================
    	    
    	    //=====================================================================
    		/*Mail::send('front-teachmearabic.paypal-invoice',$data, function($message) use ($user) {
			      $message->from('noreply@teachmearabic.org', 'Paypal Payment');
			      $message->to($user->email);
			      $message->cc('noreply@teachmearabic.org');
			      //$message->bcc('mostafa.elamree@gmail.com');
			      $message->subject('Paypal Payment');
			  });*/
			//====================================================================== 
		    return response([
                    'success' => true,
                    'message' => 'item-added-successfully',
                    'order' => $order,
                    'conferences' => $conferences
            ] , 200);
    	}else return response([
                    'success' => false,
                    'message' => 'payment-faild',
                    'order' => $order
            ] , 200);
	    
	}

    public function sendPayout($recipientEmail, $amount, $currency = 'USD')
    {
        $access_token = $this->paypalGetAccessToken();
        $url = $this->base_url . "v1/payments/payouts";

        // Create payout data
        $data = [
            "sender_batch_header" => [
                "sender_batch_id" => uniqid(),
                "email_subject" => "You have a payout!",
            ],
            "items" => [
                [
                    "recipient_type" => "EMAIL",
                    "amount" => [
                        "value" => $amount,
                        "currency" => $currency,
                    ],
                    "receiver" => $recipientEmail,
                    "note" => "Thanks for your service!",
                    "sender_item_id" => uniqid(),
                ]
            ]
        ];

        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL and get the response
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        curl_close($ch);

        // Decode the response
        $responseData = json_decode($response);
        return $responseData;
    }
}