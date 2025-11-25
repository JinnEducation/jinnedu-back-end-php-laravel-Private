<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    //
    public function checkout()
    {
        $user = Auth::user() ?? null;
        if($user) {
            $user = User::find($user->id);
        }else{
            return redirect()->route('home');
        }

        // Available Countries
        $countries = [
            'palestine' => 'Palestine',
            'jordan' => 'Jordan',
            'egypt' => 'Egypt',
            'saudi' => 'Saudi Arabia',
            'uae' => 'United Arab Emirates',
        ];

        // Payment Gateways Configuration
        // Each gateway has: name, image_path, and countries array (which countries it's available in)
        // Use 'all' in countries array to make gateway available for all countries
        $paymentGateways = [
            'my-wallet' => [
                'name' => 'My Wallet',
                'image_path' => asset('front/assets/imgs/payment/my-wallet.jpg'),
                'countries' => ['all'], // Available in all countries - use 'all' instead of listing all countries
            ],
            'paypal' => [
                'name' => 'PayPal',
                'image_path' => asset('front/assets/imgs/payment/paypal.jpg'),
                'countries' => ['all'], // Available in all countries
            ],
            'jawal-pay' => [
                'name' => 'Jawal Pay',
                'image_path' => asset('front/assets/imgs/payment/jawal-pay.jpg'),
                'countries' => ['palestine', 'jordan'], // Available only in specific countries
            ],
            'palpay' => [
                'name' => 'PalPay',
                'image_path' => asset('front/assets/imgs/payment/palpay.jpg'),
                'countries' => ['palestine'], // Available only in Palestine
            ],
            // You can add more gateways here easily
            // 'stripe' => [
            //     'name' => 'Stripe',
            //     'image_path' => 'front/assets/imgs/payment/stripe.jpg',
            //     'countries' => ['saudi', 'uae'], // Specific countries
            // ],
            // 'visa' => [
            //     'name' => 'Visa',
            //     'image_path' => 'front/assets/imgs/payment/visa.jpg',
            //     'countries' => ['all'], // All countries
            // ],
        ];

        return view('front.checkout', compact('user', 'countries', 'paymentGateways'));
    }

    public function checkout_store(Request $request)
    {
        return view('front.complete_checkout');
    }
}
