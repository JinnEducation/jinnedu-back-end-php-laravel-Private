<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\VerificationController;


use App\Http\Controllers\Api\Chat\ChatContactsController;
use App\Http\Controllers\Api\Chat\ChatMessagesController;
use App\Http\Controllers\Api\Chat\ChatStatusController;

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





Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('api.password.forgot');
Route::post('/reset-password', [AuthController::class, 'passwordUpdate'])->name('api.password.update');

Route::post('/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/check_mail', [AuthController::class, 'checkMail']);
Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/social_login', [AuthController::class, 'socialLogin'])->name('social_login');
Route::get('/locales/lang/{lang}', [LocalController::class, 'localesLang'])->name('localesLang');
Route::get('/locales/langs', [LocalController::class, 'localesLangs'])->name('localesLangs');

Route::post('/auth/check-token', [AuthController::class, 'check_token'])->name('api.check_token');

Route::get('/tlocales/lang/{lang}', function () {
    echo '123';
    exit;
});

Route::get('/locall/lang/{lang}', [LocalController::class, 'localesLang'])->name('localesLang');
Route::get('/locall/langs', [LocalController::class, 'localesLangs'])->name('localesLangs');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change');
    Route::post('/change-email', [AuthController::class, 'changeEmail'])->name('email.change');
    Route::post('/change-avatar', [AuthController::class, 'changeAvatar'])->name('avatar.change');
    Route::post('/change-name', [AuthController::class, 'changeName'])->name('name.change');
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::get('/profileUser', [AuthController::class, 'profileUser']);
    //Route::post('/navigation',[NavigationController::class,'navigation']);
    Route::get('/navigation', [MenuController::class, 'navigation'])->name('navigation');
    Route::get('/menu-abilities/{id}', [MenuController::class, 'abilities'])->name('menu-bilities');
    //======================================================================

    Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::delete('/delete-account', [AuthController::class, 'deleteAccount'])->name('account.delete');
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    /**
     * Verification Routes
     */
    Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');


    /**
     *  Verify email for mobile
     */
    Route::get('/send_code', [AuthController::class, 'sendCode']);
    Route::post('/verify/email', [AuthController::class, 'verifyEmail']);
});



Route::get('chat/blocked-words', function () {
    return \App\Models\ChatBlockedWord::where('is_active', true)
        ->pluck('word');
});

Route::middleware('auth:sanctum')->prefix('chat')->group(function () {

    // Contacts (sidebar)
    Route::get('contacts', [ChatContactsController::class, 'index']);
    Route::get('contacts/{id}', [ChatContactsController::class, 'show']);

    // Messages
    Route::get('{id}/messages', [ChatMessagesController::class, 'index']); // id = contactId
    Route::post('messages', [ChatMessagesController::class, 'store']);
    Route::delete('messages/{id}', [ChatMessagesController::class, 'destroy']);
    Route::post('messages/{id}/favorite', [ChatMessagesController::class, 'favorite']);

    // Status
    Route::post('{id}/seen', [ChatStatusController::class, 'seen']);       // id = contactId
    Route::post('{id}/typing', [ChatStatusController::class, 'typing']);   // id = contactId

    Route::get('users', [ChatContactsController::class, 'users']);
    Route::post('online', [ChatStatusController::class, 'online']);
});

include 'api-admin.php';
include 'api-tutor.php';
include 'api-front.php';
include 'api-dash.php';


Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
