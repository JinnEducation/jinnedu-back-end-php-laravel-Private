<?php

use App\Http\Controllers\Front\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MuxController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ZoomController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\MailController;
use App\Http\Controllers\PaypalCheckoutController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\PaymentResponseController;
use App\Http\Controllers\WalletPaymentTransactionController;
use App\Http\Controllers\Front\PageController as FrontPageController;

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
    require __DIR__.'/fortify.php';
    
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
    //  Route::get('contact_us', [HomeController::class, 'contact_us'])->name('site.contact_us');

    Route::get('send-mail', [MailController::class, 'send']);
    Route::get('contact', [MailController::class, 'contact'])->name('site.contact');
    Route::post('contact', [MailController::class, 'contact_data'])->name('site.contact_data');
    
    Route::get('online-group-classes', [HomeController::class, 'online_group_classes'])->name('site.online_group_classes');
    Route::get('group-class-details/{id}', [HomeController::class, 'groupClassDetails'])->name('site.group_class_details');
    Route::post('group-class-order/{id}', [HomeController::class, 'groupClassOrder'])->name('site.group_class_order');


    // checkout
    Route::get('checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('checkout', [CheckoutController::class, 'checkout_store'])->name('checkout.checkout');
});







