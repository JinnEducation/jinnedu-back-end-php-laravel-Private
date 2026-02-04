<?php

use App\Http\Controllers\Api\AccessTokensController;
use App\Http\Controllers\Api\Admin\CourseAdminController;
use App\Http\Controllers\Api\Admin\CourseDiscountController;
use App\Http\Controllers\Api\Admin\CourseItemController;
use App\Http\Controllers\Api\Admin\CourseSectionController;
use App\Http\Controllers\Api\Admin\UploadController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CateqBlogController;
use App\Http\Controllers\Api\ChatBlockedWordController;
use App\Http\Controllers\Api\DiscountCodeController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\Student\CourseCatalogController;
use App\Http\Controllers\Api\Student\EnrollmentController;
use App\Http\Controllers\Api\Student\PlayerController;
use App\Http\Controllers\Api\Student\ProgressController;
use App\Http\Controllers\Api\Student\ReviewController;
use App\Http\Controllers\Api\Student\StudentCourseController;
use Illuminate\Support\Facades\Route;

Route::apiResource('blog', BlogController::class);
Route::apiResource('chat-blocked-words', ChatBlockedWordController::class);
Route::post('chat-blocked-words/check-word', [ChatBlockedWordController::class, 'checkWord'])->name('chat-blocked-words.check_word');
Route::post('discount_codes/check-code', [DiscountCodeController::class, 'checkCode'])->name('discount_codes.check_code');
Route::apiResource('discount_codes', DiscountCodeController::class);

// Route::get('/blogs/{blog}', [BlogController::class, 'show']);
// Route::put('blog/{id}', [BlogController::class, 'update']);
// Route::patch('blog/{id}', [BlogController::class, 'update']);
Route::apiResource('cateqblog', CateqBlogController::class);
Route::apiResource('slider', SliderController::class);

Route::get('/menus/patents', [MenuController::class, 'parents']); // p_id = 0
Route::apiResource('menus', MenuController::class);

Route::post('auth/access-tokens', [AccessTokensController::class, 'store'])
    ->middleware('guest:sanctum');
Route::delete('auth/access-tokens/{token?}', [AccessTokensController::class, 'destroy'])
    ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('instructors', [CourseAdminController::class, 'instructors']);
    // ================== ADMIN ==================
    Route::prefix('admin')->group(function () {

        // Courses
        Route::apiResource('courses', CourseAdminController::class);
        Route::post('courses/{course}/publish', [CourseAdminController::class, 'publish']);
        Route::post('courses/{course}/unpublish', [CourseAdminController::class, 'unpublish']);

        // Discounts (table منفصل)
        Route::get('courses/{course}/discounts', [CourseDiscountController::class, 'index']);
        Route::post('courses/{course}/discounts', [CourseDiscountController::class, 'store']);
        Route::put('discounts/{discount}', [CourseDiscountController::class, 'update']);
        Route::delete('discounts/{discount}', [CourseDiscountController::class, 'destroy']);

        // Sections
        Route::get('courses/{course}/sections', [CourseSectionController::class, 'index']);
        Route::post('courses/{course}/sections', [CourseSectionController::class, 'store']);
        Route::put('sections/{section}', [CourseSectionController::class, 'update']);
        Route::delete('sections/{section}', [CourseSectionController::class, 'destroy']);
        Route::post('courses/{course}/sections/sort', [CourseSectionController::class, 'sort']);

        // Items
        Route::get('courses/{course}/items', [CourseItemController::class, 'index']);
        Route::post('courses/{course}/items', [CourseItemController::class, 'store']);
        Route::post('zoom/check-availability', [CourseItemController::class, 'checkAvailability']);
        Route::put('items/{item}', [CourseItemController::class, 'update']);
        Route::delete('items/{item}', [CourseItemController::class, 'destroy']);
        Route::post('courses/{course}/items/sort', [CourseItemController::class, 'sort']);

        // Upload (video / image ..)
        Route::post('upload/video', [UploadController::class, 'video']);
        Route::delete('upload/video', [UploadController::class, 'delete']);


        // ================== STUDENT ==================
        Route::prefix('student')->group(function () {

            // Catalog (list + single)
            Route::get('courses', [CourseCatalogController::class, 'index']);
            Route::get('courses/my-courses', [StudentCourseController::class, 'myCourses']);
            Route::get('courses/{course}', [CourseCatalogController::class, 'show']);


            // Enrollment
            Route::post('courses/{course}/enroll', [EnrollmentController::class, 'enroll']); // free or paid(order_id)

            // Player data (sections/items + access)
            Route::get('courses/{course}/player', [PlayerController::class, 'player']);

            // Progress
            Route::post('items/{item}/progress', [ProgressController::class, 'update']);

            // Reviews
            Route::get('courses/{course}/reviews', [ReviewController::class, 'index']);
            Route::post('courses/{course}/reviews', [ReviewController::class, 'store']);
        });
    });
});
