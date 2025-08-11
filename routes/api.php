<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\VerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/





Route::post('/forgot-password', [AuthController::class,'forgotPassword'])->name('password.forgot');
Route::post('/reset-password', [AuthController::class,'passwordUpdate'])->name('password.update');

Route::post('/register', [AuthController::class,'register'])->name('register');
Route::post('/check_mail', [AuthController::class,'checkMail']);
Route::post('/login', [AuthController::class,'login'])->name('login');
Route::post('/social_login', [AuthController::class,'socialLogin'])->name('social_login');
Route::get('/locales/lang/{lang}', [LocalController::class,'localesLang'])->name('localesLang');
Route::get('/locales/langs', [LocalController::class,'localesLangs'])->name('localesLangs');

Route::get('/tlocales/lang/{lang}', function () {
    echo '123';
    exit;
});

Route::get('/locall/lang/{lang}', [LocalController::class,'localesLang'])->name('localesLang');
Route::get('/locall/langs', [LocalController::class,'localesLangs'])->name('localesLangs');

Route::middleware(['auth:sanctum','single_login_session'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class,'logout']);
    Route::post('/change-password', [AuthController::class,'changePassword'])->name('password.change');
    Route::post('/change-email', [AuthController::class,'changeEmail'])->name('email.change');
    Route::post('/change-avatar', [AuthController::class,'changeAvatar'])->name('avatar.change');
    Route::post('/change-name', [AuthController::class,'changeName'])->name('name.change');
    Route::get('/profile', [AuthController::class,'profile']);
    //Route::post('/navigation',[NavigationController::class,'navigation']);
    Route::get('/navigation', [MenuController::class,'navigation'])->name('navigation');
    Route::get('/menu-abilities/{id}', [MenuController::class,'abilities'])->name('menu-bilities');
    //======================================================================

    Route::post('/update-profile', [AuthController::class,'updateProfile'])->name('profile.update');

    Route::delete('/delete-account', [AuthController::class,'deleteAccount'])->name('account.delete');

});

Route::group(['middleware' => ['auth:sanctum']], function () {

    /**
    * Verification Routes
    */
    Route::get('/email/verify', [VerificationController::class,'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class,'verify'])->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class,'resend'])->name('verification.resend');


    /**
     *  Verify email for mobile
     */
    Route::get('/send_code', [AuthController::class,'sendCode']);
    Route::post('/verify/email', [AuthController::class,'verifyEmail']);
});


include 'api-admin.php';
include 'api-tutor.php';
include 'api-front.php';
