<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReviewController;

use App\Http\Controllers\Constants\ContactUsController;

use App\Http\Controllers\Localizations\LabelController;
use App\Http\Controllers\Constants\CountryController;
use App\Http\Controllers\Constants\LevelController;
use App\Http\Controllers\Constants\CurrencyController;
use App\Http\Controllers\Constants\SpecializationController;
use App\Http\Controllers\Constants\SubjectController;
use App\Http\Controllers\Constants\ExperienceController;
use App\Http\Controllers\Constants\SituationController;
use App\Http\Controllers\Constants\DegreeTypeController;
use App\Http\Controllers\Constants\WorldTimezoneController;
use App\Http\Controllers\Constants\WeekDayController;
use App\Http\Controllers\Constants\OutlineController;
use App\Http\Controllers\Constants\FrequencyController;
use App\Http\Controllers\Constants\PriceController;
use App\Http\Controllers\Constants\SortByTutorController;

use App\Http\Controllers\StatisticsController;

use App\Http\Controllers\Localizations\LanguageController;

use App\Http\Controllers\Sections\DepartmentController;
use App\Http\Controllers\Sections\CategoryController;
use App\Http\Controllers\Sections\CourseController;
use App\Http\Controllers\Sections\NavigationController;

use App\Http\Controllers\Contents\PackageController;
use App\Http\Controllers\Contents\PostController;
use App\Http\Controllers\Contents\PageController;
use App\Http\Controllers\Contents\LinkController;
use App\Http\Controllers\Contents\EventController;
use App\Http\Controllers\Contents\HelpController;
use App\Http\Controllers\Contents\DocumentController;
use App\Http\Controllers\Contents\ImageController;
use App\Http\Controllers\Contents\VideoController;
use App\Http\Controllers\Contents\AdvertisementController;

use App\Http\Controllers\GroupClassController;
use App\Http\Controllers\OurCourseController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\InviteController;

use App\Http\Controllers\PaypalCheckoutController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PaymentResponseController;

use App\Http\Controllers\WalletPaymentTransactionController;

use App\Services\Payment\PayPalService;
use App\Services\Payment\StripeService;

Route::prefix('front')->name('front.')->group(function () {
    
    Route::prefix('contact-us')->name('contact-us.')->group(function () {
        Route::post('/create', [ContactUsController::class, 'store'])->name('create');
    });

    Route::prefix('sections')->name('sections.')->group(function () {
        Route::get('/categories/{id?}', [CategoryController::class, 'index'])->name('categories');
        Route::get('/navigations/{id?}', [NavigationController::class, 'index'])->name('navigations');
        Route::get('/courses/{id?}', [CourseController::class, 'index'])->name('courses');
        Route::get('/departments/{id?}', [DepartmentController::class, 'index'])->name('departments');
    });
    //========================================================================
    Route::middleware(['auth:sanctum','single_login_session'])->prefix('invite')->name('invite.')->group(function () {
        Route::post('/', [InviteController::class, 'addInvite'])->name('add');
    });
    //========================================================================

    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/{slug}', [PageController::class, 'showPage'])->name('slug-show');
    });

    Route::prefix('contents')->name('contents.')->group(function () {
        Route::get('/packages/{id?}', [PackageController::class, 'index'])->name('packages');
        Route::get('/posts/{id?}', [PostController::class, 'index'])->name('posts');
        Route::get('/pages/{id?}', [PageController::class, 'index'])->name('pages');
        Route::get('/links/{id?}', [LinkController::class, 'index'])->name('links');
        Route::get('/documents/{id?}', [DocumentController::class, 'index'])->name('documents');
        Route::get('/events/{id?}', [EventController::class, 'index'])->name('events');
        Route::get('/helps/{id?}', [HelpController::class, 'index'])->name('helps');
        Route::get('/images/{id?}', [ImageController::class, 'index'])->name('images');
        Route::get('/videos/{id?}', [VideoController::class, 'index'])->name('videos');
        Route::get('/advertisements/{id?}', [AdvertisementController::class, 'index'])->name('advertisements');
    });
    //========================================================================

    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/all', [ReviewController::class, 'getReviews'])->name('all');
    
        Route::middleware(['auth:sanctum', 'single_login_session'])->group(function () {
            $routeController = ReviewController::class;
            $routeModel = 'Review';
            include 'rest_inc.php';
    
            Route::get('/list/{type}/{ref_id}', [ReviewController::class, 'listByType'])->name('list');
        });
    });
    //========================================================================

    Route::middleware(['auth:sanctum','single_login_session'])->prefix('chats')->name('chats.')->group(function () {
        $routeController = ChatController::class;
        $routeModel = 'Chat';
        //include 'rest_inc.php';

        Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send-message')->middleware('can:send-message,App\\Models\\' . $routeModel);
        Route::get('/messages-list/{id}', [ChatController::class, 'messagesList'])->name('messages-list')->middleware('can:messages-list,App\\Models\\' . $routeModel);
        Route::get('/chat-contacts/{id?}', [ChatController::class, 'contacts'])->name('chat-contacts')->middleware('can:chat-contacts,App\\Models\\' . $routeModel);
        Route::get('/typing-status/{contact_id}/{status}', [ChatController::class, 'typingStatus'])->name('typing-status')->middleware('can:send-message,App\\Models\\' . $routeModel);
        Route::get('/online-status', [ChatController::class, 'onlineStatus'])->name('online-status')->middleware('can:send-message,App\\Models\\' . $routeModel);
    });

    //========================================================================

    Route::prefix('constants')->name('constants.')->group(function () {
        Route::get('/labels/setlabels', [LabelController::class, 'setLabels'])->name('labels-setlabels');
        Route::get('/countries/setlabels', [CountryController::class, 'setCountriesLabels'])->name('countries-setlabels');
        Route::get('/countries/{id?}', [CountryController::class, 'index'])->name('countries');

        Route::get('/levels/{id?}', [LevelController::class, 'index'])->name('levels');
        Route::get('/currencies/{id?}', [CurrencyController::class, 'index'])->name('currencies');
        Route::get('/specializations/{id?}', [SpecializationController::class, 'index'])->name('specializations');
        Route::get('/subjects/{id?}', [SubjectController::class, 'index'])->name('subjects');
        Route::get('/experiences/{id?}', [ExperienceController::class, 'index'])->name('experiences');
        Route::get('/situations/{id?}', [SituationController::class, 'index'])->name('situations');
        Route::get('/degree-types/{id?}', [DegreeTypeController::class, 'index'])->name('degree-types');
        Route::get('/world-timezones/{id?}', [WorldTimezoneController::class, 'index'])->name('world-timezones');
        Route::get('/week-days/{id?}', [WeekDayController::class, 'index'])->name('week-days');
        Route::get('/outlines/{id?}', [OutlineController::class, 'index'])->name('outlines');
        Route::get('/frequencies/{id?}', [FrequencyController::class, 'index'])->name('frequencies');
        Route::get('/prices/{id?}', [PriceController::class, 'index'])->name('prices');
        Route::get('/sort-by-tutors/{id?}', [SortByTutorController::class, 'index'])->name('sort-by-tutors');

        Route::get('/languages', [LanguageController::class, 'index'])->name('languages');
        Route::get('/languages/setlabels', [LanguageController::class, 'setLanguagesLabels'])->name('languages-set-labels');
    });

    //========================================================================

    Route::prefix('tutors')->name('tutors.')->group(function () {
        Route::get('/search', [UserController::class, 'tutorsSearch'])->name('tutors');
        Route::get('/show/{id}', [UserController::class, 'tutorShow'])->name('tutor');
        Route::get('/most-popular', [TutorController::class, 'mostPopular'])->name('most-popular');
    });

    //========================================================================

    Route::prefix('group-classes')->name('group-classes.')->group(function () {
        Route::get('/{id?}', [GroupClassController::class, 'getAssignedGroupClass'])->name('list');
        // Route::get('/{id?}', [GroupClassController::class, 'index'])->name('list');
        Route::get('/most-popular/{type}', [GroupClassController::class, 'mostPopular'])->name('most-popular');
    });

    //========================================================================

    Route::prefix('our-courses')->name('our-courses.')->group(function () {
        Route::get('/{slug?}', [OurCourseController::class, 'publishedCourses'])->name('list');
        // Route::get('/most-popular/{type}', [OurCourseController::class, 'mostPopular'])->name('most-popular');
        Route::get('/most-popular', [OurCourseController::class, 'mostPopular'])->name('most-popular');
    });

    //========================================================================

    Route::prefix('statistics')->name('statistics.')->group(function () {
        Route::get('/home', [StatisticsController::class,'home'])->name('home');
    });

    //========================================================================

    Route::middleware(['auth:sanctum','single_login_session'])->prefix('orders')->name('orders.')->group(function () {
        Route::post('/group-class/{id}', [OrderController::class, 'groupClass'])->name('group-class');
        Route::post('/our-course/{id}', [OrderController::class, 'ourCourse'])->name('our-course');
        Route::post('/trial-lesson/{id}', [OrderController::class, 'trialLesson'])->name('trial-lesson');
        Route::post('/private-lesson/{id}', [OrderController::class, 'privateLesson'])->name('private-lesson');
        Route::post('/package/{id}', [OrderController::class, 'package'])->name('package');
        Route::post('/top-up/{id}', [OrderController::class, 'topUp'])->name('top-up');

    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::post('/tutor-dates/{id}', [OrderController::class, 'tutorDates'])->name('tutor-dates');
    });
    //=============================================================================

    Route::get('/paypal-response/{id}/{status}', [PaypalCheckoutController::class,'paypalResponse'])->name('paypal-response');

    Route::post('/add-support', [SupportController::class,'add'])->name('add-support');

    Route::get('/latest-currency-exchange/{id}', [CurrencyController::class,'latestExchange'])->name('latest-currency-exchange');

    Route::middleware(['auth:sanctum','single_login_session'])->group(function(){
        Route::get('tutors-favorites', [UserController::class, 'tutorFavorite'])->name('tutors-Favorites');
    });
    
    Route::get('/payment-response/{id}/{status}',[PaymentResponseController::class,'handlePaymentResponse'])->name('checkout-response');

    Route::prefix('wallet')->name('wallet.')->group(function () {
    
        Route::middleware(['auth:sanctum', 'single_login_session'])->group(function () {
            Route::post('/charge', [WalletPaymentTransactionController::class, 'charge'])->name('charge');
        });

        Route::prefix('paypal')->name('paypal.')->group(function () {
            Route::get('/{id}/success', [PayPalService::class, 'success'])->name('success');
            Route::get('/{id}/cancel', [PayPalService::class, 'cancel'])->name('cancel');
            Route::post('/webhook', [PayPalService::class, 'handleWebhook'])->name('handleWebhook');
        });

        Route::prefix('stripe')->name('stripe.')->group(function () {
            Route::get('/{id}/success', [StripeService::class, 'success'])->name('success');
            Route::get('/{id}/cancel', [StripeService::class, 'cancel'])->name('cancel');
            Route::post('/webhook', [StripeService::class, 'handleWebhook'])->name('handleWebhook');
        });
    
    });
    
});
