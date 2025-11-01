<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuxController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ZoomController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\PageController as FrontPageController;
use App\Http\Controllers\PaypalCheckoutController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\PaymentResponseController;
use App\Http\Controllers\WalletPaymentTransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => '{locale?}',
    'where' => ['locale' => '[a-zA-Z]{2}(?:-[a-zA-Z0-9]{2,4})?'],
], function () {
    Route::get('/',[HomeController::class,'index'])->name('home');

    Route::get('/go-dashboard', [AuthController::class, 'redirectToDashboard'])
        ->name('redirect.dashboard');


    Route::get('/reset-password/{token}',[AuthController::class,'resetPassword'])->name('password.reset');
    Route::get('/mux',[MuxController::class,'index']);
    Route::get('/zoom',[ZoomController::class,'index']);
    Route::post('/zoom',[ZoomController::class,'meetingsdkSignature']);


    Route::get('/paypal/{id}',[PaypalCheckoutController::class,'paypalRequest'])->name('paypal');
    Route::get('/paypal-response/{id}/{status}',[PaypalCheckoutController::class,'paypalResponse'])->name('paypal-response');

    /*
    Route::get('/paypal/{id}','PaypalCheckoutController@paypalRequest')->name('paypal');
    Route::get('/paypal-response/{id}/{status}','PaypalCheckoutController@paypalResponse')->name('paypal-response');
    */

    //Stripe
    Route::prefix('stripe')->name('stripe.')->group(function () {
        Route::get('/{order_id}', [StripeCheckoutController::class,'checkout'])->name('checkout');
        Route::get('/success', [StripeCheckoutController::class,'success'])->name('success');
        Route::get('/cancel', [StripeCheckoutController::class,'cancel'])->name('cancel');
    });

    Route::get('/payment-response/{id}/{status}',[WalletPaymentTransactionController::class,'handlePaymentResponse'])->name('checkout-response');

    //site route
    Route::get('blog', [HomeController::class, 'blog'])->name('site.blog');
    Route::get('blog/{slug}', [HomeController::class, 'showBlog'])->name('site.showBlog');
    Route::get('pages/{slug}', [FrontPageController::class, 'show'])->name('site.pages.show');
});







