<?php
use App\Http\Controllers\TutorController;
use App\Http\Controllers\UserCertificationController;
use App\Http\Controllers\UserEducationController;
use App\Http\Controllers\UserLanguageController;
use App\Http\Controllers\UserAvailabilityController;
use App\Http\Controllers\PrivateCourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('tutor')->name('tutor.')->group(function () {
    $routeController = TutorController::class;
    $routeModel='Tutor';
    //include 'rest_inc.php';
    Route::post('/signup', [TutorController::class, 'signup'])->name('signup');
    //======================================================================
    Route::middleware(['auth:sanctum','single_login_session'])->group(function(){
        Route::post('/about', [TutorController::class, 'setAbout'])->name('set-about');
        Route::get('/about', [TutorController::class, 'getAbout'])->name('get-about');
        
        Route::post('/description', [TutorController::class, 'setDescription'])->name('set-description');
        Route::get('/description', [TutorController::class, 'getDescription'])->name('get-description');
        
        Route::post('/photo', [TutorController::class, 'uploadPhoto'])->name('upload-photo');
        Route::get('/photo', [TutorController::class, 'photoUrl'])->name('photo-url');
        
        Route::post('/video', [TutorController::class, 'setVideo'])->name('set-video');
        Route::get('/video', [TutorController::class, 'getVideo'])->name('get-video');
        
        Route::post('/hourly-price', [TutorController::class, 'setHourlyPrice'])->name('set-hourly-price');
        Route::get('/hourly-price', [TutorController::class, 'getHourlyPrice'])->name('get-hourly-price');

        Route::post('/tutor_certifications', [TutorController::class, 'setCertification'])->name('set-certifications');
        Route::get('/tutor_certifications', [TutorController::class, 'getCertification'])->name('get-certifications');

        Route::post('/tutor_availabilities/{id?}', [TutorController::class, 'setAvailability'])->name('set-availabilities');
        Route::get('/tutor_availabilities', [TutorController::class, 'getAvailability'])->name('get-availabilities');
        Route::delete('/tutor_availabilities/{id}', [TutorController::class, 'deleteAvailability'])->name('delete-availabilities');
        Route::get('/tutor_availabilities/{tutor_id}', [TutorController::class, 'getAvailabilityByTutorId'])->name('get-availability-by-tutor-id');

        Route::get('/tutor_reviews/{tutor_id}', [TutorController::class, 'getTutorReviews'])->name('get-tutor-reviews');

        Route::prefix('certifications')->name('certifications.')->group(function () {
            $routeController = UserCertificationController::class;
            $routeModel='UserCertification';
            include 'rest_inc_without_permissions.php';
        });
        
        Route::prefix('educations')->name('educations.')->group(function () {
            $routeController = UserEducationController::class;
            $routeModel='UserEducation';
            include 'rest_inc_without_permissions.php';
        });
        
        Route::prefix('languages')->name('languages.')->group(function () {
            $routeController = UserLanguageController::class;
            $routeModel='UserLanguage';
            include 'rest_inc_without_permissions.php';
        });
        
        Route::prefix('availabilities')->name('availabilities.')->group(function () {
            $routeController = UserAvailabilityController::class;
            $routeModel='UserAvailability';
            include 'rest_inc_without_permissions.php';
        });
        
        //========================================================================
    
        
        
    });
});
?>