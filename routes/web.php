<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\CourseController;
use App\Http\Controllers\Front\ExamController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\MailController;
use App\Http\Controllers\Front\PageController as FrontPageController;
use App\Http\Controllers\MuxController;
use App\Http\Controllers\PaypalCheckoutController;
use App\Http\Controllers\StripeCheckoutController;
use App\Http\Controllers\WalletPaymentTransactionController;
use App\Http\Controllers\ZoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/go-dashboard', [AuthController::class, 'redirectToDashboard'])->name('redirect.dashboard');

    Route::post('/email-check', [AuthController::class, 'emailCheck'])->name('auth.email-check');

    Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::get('/mux', [MuxController::class, 'index']);
    Route::get('/zoom', [ZoomController::class, 'index']);
    Route::post('/zoom', [ZoomController::class, 'meetingsdkSignature']);

    Route::get('/paypal/{id}', [PaypalCheckoutController::class, 'paypalRequest'])->name('paypal');
    Route::get('/paypal-response/{id}/{status}', [PaypalCheckoutController::class, 'paypalResponse'])->name('paypal-response');

    /*
    Route::get('/paypal/{id}','PaypalCheckoutController@paypalRequest')->name('paypal');
    Route::get('/paypal-response/{id}/{status}','PaypalCheckoutController@paypalResponse')->name('paypal-response');
    */

    // Stripe
    Route::prefix('stripe')->name('stripe.')->group(function () {
        Route::get('/{order_id}', [StripeCheckoutController::class, 'checkout'])->name('checkout');
        Route::get('/success', [StripeCheckoutController::class, 'success'])->name('success');
        Route::get('/cancel', [StripeCheckoutController::class, 'cancel'])->name('cancel');
    });

    Route::get('/payment-response/{id}/{status}', [WalletPaymentTransactionController::class, 'handlePaymentResponse'])->name('checkout-response');

    // site route
    Route::get('blog', [HomeController::class, 'blog'])->name('site.blog');
    Route::get('blog/{slug}', [HomeController::class, 'showBlog'])->name('site.showBlog');
    Route::get('pages/{slug}', [FrontPageController::class, 'show'])->name('site.pages.show');
    //  Route::get('contact_us', [HomeController::class, 'contact_us'])->name('site.contact_us');

    Route::get('send-mail', [MailController::class, 'send']);
    Route::get('contact', [MailController::class, 'contact'])->name('site.contact');
    Route::post('contact', [MailController::class, 'contact_data'])->name('site.contact_data');

    Route::get('online-group-classes', [HomeController::class, 'online_group_classes'])->name('site.online_group_classes');
    Route::get('group-class-details/{id}', [HomeController::class, 'groupClassDetails'])->name('site.group_class_details');
    Route::post('group-class-order/{id}', [HomeController::class, 'groupClassOrder'])->middleware('check_student')->name('site.group_class_order');

    Route::get('online_private_classes', [HomeController::class, 'online_private_classes'])->name('site.online_private_classes');
    Route::get('tutor_jinn/{id}', [HomeController::class, 'tutor_jinn'])->name('site.tutor_jinn');

    Route::post('private-lesson-order/{id}', [HomeController::class, 'privateLessonOrder'])->middleware('check_student')->name('site.private_lesson_order');
    Route::post('trial-lesson-order/{id}', [HomeController::class, 'trialLessonOrder'])->middleware('check_student')->name('site.trial_lesson_order');

    Route::get('take-exam-successful/{id}', [ExamController::class, 'success'])->name('site.take_exam_successful');

    // course
    Route::get('single-course/{id}', [CourseController::class, 'singlecourse'])->name('site.singlecourse');

    Route::middleware(['auth:web', 'check_student'])->group(function () {

        // exam
        Route::get('take-exam/{group_class_id}', [ExamController::class, 'index'])->name('site.take_exam');
        Route::post('take-exam/{group_class_id}', [ExamController::class, 'store'])->name('site.take_exam_store');
        Route::get('exam-result/{id}', [ExamController::class, 'show'])->name('site.exam_result');

        // checkout
        Route::get('checkout', [CheckoutController::class, 'checkout'])->name('checkout');
        Route::post('checkout/apply-discount', [CheckoutController::class, 'applyDiscountCode'])->name('checkout.applyDiscountCode');
        Route::post('checkout', [CheckoutController::class, 'checkout_store'])->name('checkout.checkout');
        Route::get('checkout-complete', [CheckoutController::class, 'checkoutComplete'])->name('checkout-complete');
        Route::get('checkout-response/{id}/{status}', [CheckoutController::class, 'handlePaymentResponse'])->name('checkout-response-get');

        // course
        Route::post('course/{id}/book', [CourseController::class, 'bookCourse'])->name('site.bookCourse');

        // Local test payment (for development)
        Route::get('local-payment-test', function (Request $request) {
            $referenceId = $request->get('reference_id');
            $transaction = \App\Models\WalletPaymentTransaction::where('reference_id', $referenceId)->first();

            if (! $transaction) {
                abort(404, 'Transaction not found');
            }

            return view('front.local-payment-test', compact('transaction'));
        })->name('local-payment-test');

        Route::post('local-payment-test/complete', function (Request $request) {
            $referenceId = $request->get('reference_id');
            $transaction = \App\Models\WalletPaymentTransaction::where('reference_id', $referenceId)->first();

            if (! $transaction) {
                return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
            }

            // Process payment through LocalTestService
            $service = new \App\Services\Payment\LocalTestService;
            $result = $service->success($request);
            if ($result->getData()->success ?? false) {
                return redirect()->route('checkout-response-get', [
                    'id' => $transaction->id,
                    'status' => 'success',
                ]);
            }

            return redirect()->route('checkout')
                ->with('error', 'Payment processing failed');
        })->name('local-payment-test-complete');
    });
});

Route::get('/bridge-login/{token}', function ($token) {
    $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

    if (! $tokenModel) {
        abort(403);
    }

    $request = request();
    $userNew = $tokenModel->tokenable;

    // لو في مستخدم حالي ومختلف
    if (Auth::check() && Auth::id() !== $userNew->id) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    // تسجيل دخول المستخدم الجديد (حتى لو كان نفس المستخدم)
    Auth::login($userNew, true);
    $request->session()->regenerate();

    // احذف التوكن بعد الاستخدام (ONE-TIME TOKEN)
    $tokenModel->delete();

    if ($request->has('redirect') && $request->redirect === 'profile') {
        return redirect()->route('profile.edit');
    }

    return redirect()->route('bridge-login-check');
});

Route::get('/bridge-logout/{token}', function ($token) {
    $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
    if (! $tokenModel) {
        abort(403);
    }

    // 1. حذف التوكن (Sanctum)
    $tokenModel->delete();

    // 2. تسجيل خروج الويب
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('bridge-login-check');
});

Route::get('/bridge-login-check', function () {
    return view('bridge-login-check');
})->name('bridge-login-check');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/edit', [AuthController::class, 'editProfileStore'])->name('profile.edit.store');
});

Route::get('/show-video', function (\Illuminate\Http\Request $request) {
    $path = $request->query('path');

    if (! $path) {
        abort(404);
    }

    $type = $request->query('type');

    $pathStorage = '';
    if ($type == 'user') {
        $pathStorage = 'video_files/';
    } elseif ($type == 'course') {
        $pathStorage = 'courses/videos/';
    }

    // حماية بسيطة: لازم يكون داخل courses/videos
    if (! str_starts_with($path, $pathStorage)) {
        abort(403);
    }

    $url = asset('storage/'.$path);

    return view('show_video', [
        'videoUrl' => $url,
    ]);
})->name('show_video');

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
