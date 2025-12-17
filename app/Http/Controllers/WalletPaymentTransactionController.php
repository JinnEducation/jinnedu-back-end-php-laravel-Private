<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletPaymentTransaction;
use App\Http\Controllers\Constants\CurrencyController;
use App\Services\Payment\PaymentManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class WalletPaymentTransactionController extends Controller
{
    public $currencyResponse;

    public function __construct()
    {
        $this->currencyResponse = [
            'success' => false,
            'message' => 'currency-dose-not-exist',
            'msg-code' => '111'
        ];
    }
    
    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);

        $items = WalletPaymentTransaction::select('id', 'user_id', 'amount', 'payment_status', 'status', 'payment_channel');

        if($request->user_id){
            $items = $items->where('user_id', $request->user_id);
        }

        if($request->status){
            $items = $items->where('status', $request->status);
        }

        if($request->payment_status){
            $items = $items->where('payment_status', $request->payment_status);
        }

        if($request->payment_channel){
            $items = $items->where('payment_channel', $request->payment_channel);
        }

        if($request->from_date){
            $items = $items->whereDate('created_at', '>=', $request->from_date);
        }
        
        if($request->to_date){
            $items = $items->whereDate('created_at', '<=', $request->to_date);
        }

        $items = paginate($items, $limit);

        foreach($items as $item){
            $item->user;
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items
        ], 200);
    }

    public function charge(Request $request)
    {
        $user = Auth::user();
        if(!$user) {
            return response([
                    'success' => false,
                    'message' => 'user-dose-not-exist',
                    'msg-code' => '111'
            ], 200);
        }

        if(!empty($request->currency_id)) {
            $currency = new CurrencyController();
            $this->currencyResponse = $currency->latestExchange($request->currency_id, false);
            if(!$this->currencyResponse['success']) {
                return response($this->currencyResponse, 200);
            }
        }

        $user_wallet = $user->wallets()->first();

        $transaction = new WalletPaymentTransaction();
        $transaction->user_id = $user->id;
        $transaction->payment_channel = $request->payment_channel;
        $transaction->amount = $request->amount;
        $transaction->current_wallet = $user_wallet ? $user_wallet->balance : 0;
        $transaction->reference_id = (string) Str::uuid();

        if($this->currencyResponse['success']) {
            $transaction->amount = $request->amount / $this->currencyResponse['result']->exchange;
            $transaction->currency_id = $this->currencyResponse['result']->currency_id;
            $transaction->currency_exchange = $this->currencyResponse['result']->exchange;
            $transaction->currency_code = $this->currencyResponse['result']->currency_code;
        }

        $transaction->save();

        $gateway = PaymentManager::driver($request->payment_channel);

        $response = $gateway->createPayment([
            'reference_id' => $transaction->reference_id,
            'amount' => $request->amount,
            'currency' =>  $transaction->currency_code ?? 'USD',
            'description' => 'Wallet Charge',
            'success_url' => url('/') . '/payment-response/' . $transaction->id . '/success',
            'cancel_url' => url('/') . '/payment-response/' . $transaction->id . '/cancel',
        ]);
        

        if ($request->payment_channel === 'paypal') {
            $approveUrl = collect($response['links'])->firstWhere('rel', 'approve')['href'] ?? null;
        
            if ($approveUrl) {
                $url = $approveUrl;
                // return redirect()->away($approveUrl); 
            }
        } elseif ($request->payment_channel === 'stripe') {
            //return $response['url'];
            // return redirect()->away($response['url']);
            $url = $response['url'];
        }
        
        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'url' => $url ?? ''
        ], 200);

    }

    public function handlePaymentResponse(Request $request,$locale, $transactionId, $status)
    {
        if (!in_array($status, ['success', 'cancel'])) {
            abort(400, 'Invalid payment status');
        }

        $transaction = WalletPaymentTransaction::findOrFail($transactionId);
        
        $gateway = PaymentManager::driver($transaction->payment_channel);

        return $status === 'success'
            ? $gateway->success($request)
            : $gateway->cancel($request);
    }

}