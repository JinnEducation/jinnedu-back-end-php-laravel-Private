<?php

use App\Http\Controllers\Api\Admin\ConferenceRecordingController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\Constants\ContactUsController;
use App\Http\Controllers\Constants\CountryController;
use App\Http\Controllers\Constants\CourseCategoryController;
use App\Http\Controllers\Constants\CurrencyController;
use App\Http\Controllers\Constants\DegreeTypeController;
use App\Http\Controllers\Constants\ExperienceController;
use App\Http\Controllers\Constants\ForbiddenWordsController;
use App\Http\Controllers\Constants\FrequencyController;
use App\Http\Controllers\Constants\LevelController;
use App\Http\Controllers\Constants\OutlineController;
use App\Http\Controllers\Constants\PriceController;
use App\Http\Controllers\Constants\SituationController;
use App\Http\Controllers\Constants\SortByTutorController;
use App\Http\Controllers\Constants\SpecializationController;
use App\Http\Controllers\Constants\SubjectController;
use App\Http\Controllers\Constants\WeekDayController;
use App\Http\Controllers\Constants\WorldTimezoneController;
use App\Http\Controllers\Contents\AdvertisementController;
use App\Http\Controllers\Contents\DocumentController;
use App\Http\Controllers\Contents\EventController;
use App\Http\Controllers\Contents\HelpController;
use App\Http\Controllers\Contents\ImageController;
use App\Http\Controllers\Contents\LinkController;
use App\Http\Controllers\Contents\PackageController;
use App\Http\Controllers\Contents\PageController;
use App\Http\Controllers\Contents\PostController;
use App\Http\Controllers\Contents\VideoController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\GroupClassController;
use App\Http\Controllers\Localizations\LabelController;
use App\Http\Controllers\Localizations\LanguageController;
use App\Http\Controllers\Localizations\TranslationController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OurCourseController;
use App\Http\Controllers\ParentInvitationController;
use App\Http\Controllers\PayoutController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Sections\CategoryController;
use App\Http\Controllers\Sections\CourseController;
use App\Http\Controllers\Sections\DepartmentController;
use App\Http\Controllers\Sections\NavigationController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentStatisticsController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TutorFinanceController;
use App\Http\Controllers\TutorStatisticsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFavoriteController;
use App\Http\Controllers\UserInterestController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WalletPaymentTransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/tests', [UserController::class,'test']);


Route::middleware(['auth:sanctum','single_login_session', 'verified'])->group(function () {

        
    Route::prefix('contact-us')->name('contact-us.')->group(function () {
        Route::get('/', [ContactUsController::class, 'index'])->name('index');
        Route::get('/show/{id}', [ContactUsController::class, 'show'])->name('show');
    });

    
    Route::get('/get-users', [UserController::class,'index']);

    Route::get('/get-levels', [LevelController::class,'index']);

    Route::get('/get-categories', [CategoryController::class,'index']);


    Route::get('/forbidden-words', [ForbiddenWordsController::class,'index'])->name('words.index');
    Route::post('/forbidden-words', [ForbiddenWordsController::class,'store'])->name('words.store');
    Route::get('/forbidden-words/{id}', [ForbiddenWordsController::class,'show'])->name('words.show');
    Route::put('/forbidden-words/{id}', [ForbiddenWordsController::class,'update'])->name('words.update');
    Route::delete('/forbidden-words/{id}', [ForbiddenWordsController::class,'delete'])->name('words.delete');

    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::post('/create', [ExamController::class, 'store'])->name('store');
        Route::get('/show/{id}', [ExamController::class, 'show'])->name('show');
        Route::post('/update/{id}', [ExamController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ExamController::class,'destroy'])->name('destroy');

        Route::get('/group-class', [ExamController::class, 'groupClassHasExams'])->name('groupClassHasExams');
    });


    Route::prefix('users')->name('users.')->group(function () {

        $routeController = UserController::class;
        $routeModel = 'User';
        include 'rest_inc.php';
        
        Route::get('/all', [UserController::class, 'getUsers']);
    });

    //=================================================================================

    Route::prefix('countries')->name('countries.')->group(function () {
        $routeController = CountryController::class;
        $routeModel = 'Country';
        include 'rest_inc.php';
    });

    Route::prefix('levels')->name('levels.')->group(function () {
        $routeController = LevelController::class;
        $routeModel = 'Level';
        include 'rest_inc.php';
    });

    Route::prefix('currencies')->name('currencies.')->group(function () {
        $routeController = CurrencyController::class;
        $routeModel = 'Currency';
        include 'rest_inc.php';
    });

    Route::prefix('specializations')->name('specializations.')->group(function () {
        $routeController = SpecializationController::class;
        $routeModel = 'Specialization';
        include 'rest_inc.php';
    });
    Route::prefix('course_categories')->name('course_categories.')->group(function () {
        $routeController = CourseCategoryController::class;
        $routeModel = 'CourseCategory';
        include 'rest_inc.php';
    });

    Route::prefix('experiences')->name('experiences.')->group(function () {
        $routeController = ExperienceController::class;
        $routeModel = 'Experience';
        include 'rest_inc.php';
    });

    Route::prefix('situations')->name('situations.')->group(function () {
        $routeController = SituationController::class;
        $routeModel = 'Situation';
        include 'rest_inc.php';
    });

    Route::prefix('subjects')->name('subjects.')->group(function () {
        $routeController = SubjectController::class;
        $routeModel = 'Subject';
        include 'rest_inc.php';
    });

    Route::prefix('degree-types')->name('degree-types.')->group(function () {
        $routeController = DegreeTypeController::class;
        $routeModel = 'DegreeType';
        include 'rest_inc.php';
    });

    Route::prefix('week-days')->name('week-days.')->group(function () {
        $routeController = WeekDayController::class;
        $routeModel = 'WeekDay';
        include 'rest_inc.php';
    });

    Route::prefix('world-timezones')->name('world-timezones.')->group(function () {
        $routeController = WorldTimezoneController::class;
        $routeModel = 'WorldTimezone';
        include 'rest_inc.php';
    });

    Route::prefix('outlines')->name('outlines.')->group(function () {
        $routeController = OutlineController::class;
        $routeModel = 'Outline';
        include 'rest_inc.php';
    });

    Route::prefix('frequencies')->name('frequencies.')->group(function () {
        $routeController = FrequencyController::class;
        $routeModel = 'Frequency';
        include 'rest_inc.php';
    });

    Route::prefix('prices')->name('prices.')->group(function () {
        $routeController = PriceController::class;
        $routeModel = 'Price';
        include 'rest_inc.php';
    });

    Route::prefix('sort-by-tutors')->name('sort-by-tutors.')->group(function () {
        $routeController = SortByTutorController::class;
        $routeModel = 'SortByTutor';
        include 'rest_inc.php';
    });

    //========================================================================

    Route::prefix('languages')->name('languages.')->group(function () {
        $routeController = LanguageController::class;
        $routeModel = 'Language';
        include 'rest_inc.php';

        Route::get('/main/{id}', [LanguageController::class, 'setMain'])->name('setmain')->middleware('can:edit,App\\Models\\' . $routeModel);
    });

    Route::prefix('labels')->name('labels.')->group(function () {
        $routeController = LabelController::class;
        $routeModel = 'Label';
        include 'rest_inc.php';

        Route::get('/options', [LabelController::class, 'getOptions'])->name('getoptions')->middleware('can:index,App\\Models\\' . $routeModel);
    });

    Route::prefix('translations')->name('translations.')->group(function () {
        $routeController = TranslationController::class;
        $routeModel = 'Translation';
        include 'rest_inc.php';
    });

    //========================================================================

    Route::prefix('medias')->name('medias.')->group(function () {
        $routeController = MediaController::class;
        $routeModel = 'Media';
        include 'rest_inc_without_permissions.php';
    });

    //========================================================================

    Route::prefix('posts')->name('posts.')->group(function () {
        $routeController = PostController::class;
        $routeModel = 'Post';
        include 'rest_inc.php';
    });

    Route::prefix('packages')->name('packages.')->group(function () {
        $routeController = PackageController::class;
        $routeModel = 'Package';
        include 'rest_inc.php';
    });

    Route::prefix('pages')->name('pages.')->group(function () {
        $routeController = PageController::class;
        $routeModel = 'Page';
        include 'rest_inc.php';
    });

    Route::prefix('videos')->name('videos.')->group(function () {
        $routeController = VideoController::class;
        $routeModel = 'Video';
        include 'rest_inc.php';
    });

    Route::prefix('advertisements')->name('advertisements.')->group(function () {
        $routeController = AdvertisementController::class;
        $routeModel = 'Advertisement';
        include 'rest_inc.php';
    });

    Route::prefix('links')->name('links.')->group(function () {
        $routeController = LinkController::class;
        $routeModel = 'Link';
        include 'rest_inc.php';
    });

    Route::prefix('events')->name('events.')->group(function () {
        $routeController = EventController::class;
        $routeModel = 'Event';
        include 'rest_inc.php';
    });

    Route::prefix('helps')->name('helps.')->group(function () {
        $routeController = HelpController::class;
        $routeModel = 'Help';
        include 'rest_inc.php';
    });

    Route::prefix('images')->name('images.')->group(function () {
        $routeController = ImageController::class;
        $routeModel = 'Image';
        include 'rest_inc.php';
    });

    Route::prefix('documents')->name('documents.')->group(function () {
        $routeController = DocumentController::class;
        $routeModel = 'Document';
        include 'rest_inc.php';
    });

    //========================================================================

    Route::prefix('departments')->name('departments.')->group(function () {
        $routeController = DepartmentController::class;
        $routeModel = 'Department';
        include 'rest_inc.php';
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        $routeController = CategoryController::class;
        $routeModel = 'Category';
        include 'rest_inc.php';
    });

    // Route::prefix('courses')->name('courses.')->group(function () {
    //     $routeController = CourseController::class;
    //     $routeModel = 'Course';
    //     include 'rest_inc.php';
    // });

    Route::prefix('navigations')->name('navigations.')->group(function () {
        $routeController = NavigationController::class;
        $routeModel = 'Navigation';
        include 'rest_inc.php';
    });

    //========================================================================

    Route::prefix('roles')->name('roles.')->group(function () {
        $routeController = RoleController::class;
        $routeModel = 'Role';
        include 'rest_inc.php';

        Route::get('/menus/{role?}', [RoleController::class, 'menus'])->name('menus')->middleware('can:index,App\\Models\\' . $routeModel);
    });

    //========================================================================

    Route::prefix('complaints')->name('complaints.')->group(function () {
        $routeController = ComplaintController::class;
        $routeModel = 'ConferenceComplaint';
        include 'rest_inc.php';

        Route::post('/reply', [ComplaintController::class, 'reply'])->name('reply')->middleware('can:reply,App\\Models\\' . $routeModel);
    });

    //========================================================================

    Route::prefix('supports')->name('supports.')->group(function () {
        $routeController = SupportController::class;
        $routeModel = 'Support';
        include 'rest_inc.php';

        Route::post('/reply', [SupportController::class, 'reply'])->name('reply')->middleware('can:reply,App\\Models\\' . $routeModel);
    });

    //========================================================================

    Route::prefix('favorites')->name('favorites.')->group(function () {
        $routeController = UserFavoriteController::class;
        $routeModel = 'UserFavorite';
        include 'rest_inc_without_permissions.php';
        Route::delete('/remove', [$routeController, 'remove'])->name('remove');
    });

    //========================================================================

    Route::prefix('interests')->name('interests.')->group(function () {
        $routeController = UserInterestController::class;
        $routeModel = 'UserInterest';
        include 'rest_inc.php';
    });

    //========================================================================

    Route::prefix('conferences')->name('conferences.')->group(function () {
        $routeController = ConferenceController::class;
        $routeModel = 'Conference';
        //include 'rest_inc.php';

        Route::get('/admin-index', [ConferenceController::class, 'adminIndex'])->name('admin-index')->middleware('can:admin-index,App\\Models\\' . $routeModel);
        Route::get('/tutor-index', [ConferenceController::class, 'tutorIndex'])->name('tutor-index')->middleware('can:tutor-index,App\\Models\\' . $routeModel);
        Route::get('/student-index', [ConferenceController::class, 'studentIndex'])->name('student-index')->middleware('can:student-index,App\\Models\\' . $routeModel);

        Route::get('/create-tutor-link/{id}', [ConferenceController::class, 'createTutorLink'])->name('create-tutor-link')->middleware('can:create-tutor-link,App\\Models\\' . $routeModel);
        Route::get('/create-student-link/{id}', [ConferenceController::class, 'createStudentLink'])->name('create-student-link')->middleware('can:create-student-link,App\\Models\\' . $routeModel);
        Route::get('/my-links/{id}', [ConferenceController::class, 'myLinks'])->name('create-link')->middleware('can:create-link,App\\Models\\' . $routeModel);

        Route::post('/student-change-date/{id}', [ConferenceController::class, 'studentChangeDate'])->name('student-change-date')->middleware('can:student-change-date,App\\Models\\' . $routeModel);
        Route::post('/tutor-change-date/{id}', [ConferenceController::class, 'tutorChangeDate'])->name('tutor-change-date')->middleware('can:tutor-change-date,App\\Models\\' . $routeModel);
        Route::post('/cancel-conference/{id}', [ConferenceController::class, 'cancelConference'])->name('cancel-conference')->middleware('can:student-change-date,App\\Models\\' . $routeModel);

        Route::get('/admin-card/{id}', [ConferenceController::class, 'adminCard'])->name('admin-card')->middleware('can:admin-index,App\\Models\\' . $routeModel);
        Route::get('/tutor-card/{id}', [ConferenceController::class, 'tutorCard'])->name('tutor-card')->middleware('can:tutor-index,App\\Models\\' . $routeModel);
        Route::get('/student-card/{id}', [ConferenceController::class, 'studentCard'])->name('student-card')->middleware('can:student-index,App\\Models\\' . $routeModel);

        Route::get('/tutor-overview/{id}', [ConferenceController::class, 'tutorOverview'])->name('tutor-overview')->middleware('can:tutor-index,App\\Models\\' . $routeModel);
        Route::get('/student-overview/{id}', [ConferenceController::class, 'studentOverview'])->name('student-overview')->middleware('can:student-index,App\\Models\\' . $routeModel);

        Route::post('/upload-file/{id}', [ConferenceController::class, 'uploadFile'])->name('upload-link')->middleware('can:upload-file,App\\Models\\' . $routeModel);
        Route::get('/my-files/{id}', [ConferenceController::class, 'myFiles'])->name('my-files')->middleware('can:upload-file,App\\Models\\' . $routeModel);

        Route::post('/add-note/{id}', [ConferenceController::class, 'addNote'])->name('add-note')->middleware('can:add-note,App\\Models\\' . $routeModel);
        Route::get('/my-notes/{id}', [ConferenceController::class, 'myNotes'])->name('my-notes')->middleware('can:add-note,App\\Models\\' . $routeModel);

        Route::post('/add-complaint/{id}', [ConferenceController::class, 'addComplaint'])->name('add-complaint')->middleware('can:add-complaint,App\\Models\\' . $routeModel);
        Route::post('/reply-complaint/{confid}/{compid}', [ConferenceController::class, 'replyComplaint'])->name('reply-complaint')->middleware('can:add-complaint,App\\Models\\' . $routeModel);
        Route::get('/view-complaint/{confid}/{compid}', [ConferenceController::class, 'viewComplaint'])->name('view-complaint')->middleware('can:add-complaint,App\\Models\\' . $routeModel);
        Route::get('/my-complaints/{id}', [ConferenceController::class, 'myComplaints'])->name('my-complaints')->middleware('can:add-complaint,App\\Models\\' . $routeModel);

        Route::post('/register-attendance/{id}', [ConferenceController::class, 'registerAttendance'])->name('register-attendance');
                
        Route::post('/toggle-conference-attendance', [ConferenceController::class, 'toggleConferenceAttendance'])->name('toggle-conference-attendance');

    });

    //========================================================================

    Route::prefix('invitations')->name('invitations.')->group(function () {
        $routeController = ParentInvitationController::class;
        $routeModel = 'ParentInvitation';
        //include 'rest_inc.php';
        Route::get('/admin-invitations', [ParentInvitationController::class, 'adminInvitations'])->name('admin-invitations')->middleware('can:admin-invitations,App\\Models\\' . $routeModel);
        Route::get('/parent-invitations', [ParentInvitationController::class, 'parentInvitations'])->name('parent-invitations')->middleware('can:parent-invitations,App\\Models\\' . $routeModel);
        Route::get('/child-invitations', [ParentInvitationController::class, 'childInvitations'])->name('child-invitations')->middleware('can:child-invitations,App\\Models\\' . $routeModel);
        Route::get('/send-invitation/{id}', [ParentInvitationController::class, 'sendInvitation'])->name('send-invitation')->middleware('can:send-invitation,App\\Models\\' . $routeModel);
        Route::get('/accept-invitation/{id}', [ParentInvitationController::class, 'acceptInvitation'])->name('accept-invitation')->middleware('can:accept-invitation,App\\Models\\' . $routeModel);
        Route::get('/reject-invitation/{id}', [ParentInvitationController::class, 'rejectInvitation'])->name('reject-invitation')->middleware('can:reject-invitation,App\\Models\\' . $routeModel);
        Route::get('/remove-invitation/{id}', [ParentInvitationController::class, 'removeInvitation'])->name('remove-invitation')->middleware('can:remove-invitation,App\\Models\\' . $routeModel);
    });

    //========================================================================

    Route::prefix('wallet')->name('wallet.')->group(function () {
        $routeController = WalletController::class;
        $routeModel = 'UserWallet';

        Route::get('/balance', [WalletController::class, 'balance'])->name('balance');
        Route::post('/checkout/{orderid}', [WalletController::class, 'checkout'])->name('checkout');
        Route::post('/refund/{orderid}', [WalletController::class, 'refund'])->name('refund')->middleware('can:refund,App\\Models\\Order');
        Route::post('/transfer/{conferenceid}', [WalletController::class, 'transfer'])->name('transfer')->middleware('can:transfer,App\\Models\\Order');

        Route::get('/get-transactions', [WalletController::class, 'getWalletTransactions'])->name('getWalletTransactions');
        Route::post('/add-transaction', [WalletController::class, 'addWalletTransaction'])->name('addWalletTransaction');


        Route::prefix('payment')->name('payment.')->group(function () {
            Route::get('/transactions', [WalletPaymentTransactionController::class, 'index'])->name('transactions');
        });
    });

    //========================================================================

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/read/{id}', [NotificationController::class, 'read']);
        Route::get('/read-all', [NotificationController::class, 'readAll']);
    });

    //========================================================================

    Route::prefix('orders')->name('orders.')->group(function () {
        $routeController = OrderController::class;
        $routeModel = 'Order';
        //include 'rest_inc.php';
        Route::get('/admin-index', [OrderController::class, 'adminIndex'])->name('admin-index')->middleware('can:admin-index,App\\Models\\' . $routeModel);
        Route::get('/my-index', [OrderController::class, 'myIndex'])->name('my-index')->middleware('can:my-index,App\\Models\\' . $routeModel);
        Route::post('/refund/{orderid}', [OrderController::class, 'refund'])->name('refund')->middleware('can:refund,App\\Models\\' . $routeModel);
    });

    //========================================================================

    Route::prefix('settings')->name('settings.')->group(function () {
        $routeController = SettingController::class;
        $routeModel = 'Setting';
        include 'rest_inc_without_permissions.php';
    });

    //========================================================================

    Route::prefix('group-classes')->name('group-classes.')->group(function () {
        $routeController = GroupClassController::class;
        $routeModel = 'GroupClass';
        include 'rest_inc.php';
        Route::post('/register-as-tutor/{group_class_id}', [GroupClassController::class, 'registerAsTutor'])->name('register-as-tutor')->middleware('can:register-as-tutor,App\\Models\\' . $routeModel);
        Route::post('/un-register-as-tutor/{group_class_id}', [GroupClassController::class, 'unRegisterAsTutor'])->name('un-register-as-tutor')->middleware('can:un-register-as-tutor,App\\Models\\' . $routeModel);
        Route::get('/tutors/{group_class_id}', [GroupClassController::class, 'groupClassTutors'])->name('tutors');
        Route::post('/assign-tutor', [GroupClassController::class, 'assignTutorToGroupClass'])->name('assign-tutor');
        Route::post('/un-assign-tutor', [GroupClassController::class, 'unAssignTutorToGroupClass'])->name('un-assign-tutor');
        Route::get('/get-tutor-groupclasses', [GroupClassController::class, 'getTutorGroupClasses'])->name('tutors-groupclasses');
        Route::get('/tutor-index', [GroupClassController::class, 'tutorIndex'])->name('tutor-index');
        Route::get('/details/{group_class_id}', [GroupClassController::class, 'groupClassDetails'])->name('group-class-details');

    });

    //========================================================================

    Route::prefix('our-courses')->name('our-courses.')->group(function () {
        $routeController = OurCourseController::class;
        $routeModel = 'OurCourse';
        include 'rest_inc.php';
        Route::get('/tutor-index', [OurCourseController::class, 'tutorIndex'])->name('tutor-index')->middleware('can:tutor-index,App\\Models\\' . $routeModel);
    });

    //========================================================================

    Route::prefix('payouts')->name('payouts.')->group(function () {
        Route::get('/', [PayoutController::class, 'index']);
        Route::post('/store', [PayoutController::class,'store']);
        Route::post('/update/{id}', [PayoutController::class,'update']);
        Route::post('/{id}/transfer', [PayoutController::class,'transfer']);
    });

    //========================================================================

    Route::prefix('tutor-statistics')->name('tutor-statistics.')->group(function () {
        Route::get('/group-class-orders', [TutorStatisticsController::class, 'getTutorGroupClassOrders']);
        Route::get('/private-lesson-orders', [TutorStatisticsController::class, 'getTutorPrivateLessonOrders']);
        Route::get('/tutor-info', [TutorStatisticsController::class,'tutorInfo']);
        Route::get('/get-tutors', [TutorStatisticsController::class,'getTutors']);
        Route::get('/get-tutor-finance', [TutorStatisticsController::class,'getTutorFinance']);
        Route::post('/update-tutor-finance/{id}', [TutorStatisticsController::class,'updateTutorFinanceStatus']);
                
        Route::post('/tutor-transfer-fees/{conference_id}', [TutorStatisticsController::class,'tutorTransferFeesToHisWallet']);

        Route::get('/get-completed-conference', [TutorStatisticsController::class, 'getTutorCompletedConference'])->name('completed-conference');
        Route::get('/get-complaints-conference', [TutorStatisticsController::class, 'getTutorConferenceWithComplaints'])->name('complaints-conference');
        Route::get('/get-postponed-conference', [TutorStatisticsController::class, 'getPostponedConferences'])->name('postponed-conference');

    });

    Route::prefix('student-statistics')->name('student-statistics.')->group(function () {
        Route::get('/group-class-orders', [StudentStatisticsController::class, 'getStudentGroupClassOrders']);
        Route::get('/private-lesson-orders', [StudentStatisticsController::class, 'getStudentPrivateLessonOrders']);
        Route::get('/student-info', [StudentStatisticsController::class,'studentInfo']);
    });

    Route::prefix('tutor-finance')->name('tutor-finance.')->group(function () {
        Route::get('/', [TutorFinanceController::class, 'index']);
        Route::get('/my-index', [TutorFinanceController::class, 'myIndex'])->name('my-index');
        Route::post('/update/{id}', [TutorFinanceController::class,'update']);
        Route::post('/{id}/transfer', [TutorFinanceController::class,'transfer']);
    });
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/students-conference-report', [ReportController::class, 'studentsConferenceReport'])->name('studentsConferenceReport');
        Route::get('/revenue-Report', [ReportController::class, 'revenueReport'])->name('revenue');
    });


     Route::get('/conference-recordings/by-conference/{conference_id}', [ConferenceRecordingController::class, 'indexByConference']);

    Route::post('/conference-recordings/create', [ConferenceRecordingController::class, 'store']);

    Route::post('/conference-recordings/upload/video', [ConferenceRecordingController::class, 'uploadVideo']);

    Route::delete('/conference-recordings/delete/{id}', [ConferenceRecordingController::class, 'destroy']);
    
});