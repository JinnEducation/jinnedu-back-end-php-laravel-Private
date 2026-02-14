<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GroupClassController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Models\Blog;
use App\Models\Category;
use App\Models\CateqBlog;
use App\Models\Conference;
use App\Models\Country;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\GroupClass;
use App\Models\GroupClassTutor;
use App\Models\Language;
use App\Models\Order;
use App\Models\Slider;
use App\Models\Specialization;
use App\Models\Subject;
use App\Models\TutorReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        // Slider
        $locale = app()->getLocale();
        $language = Language::where('shortname', $locale)->first();
        if (! $language) {
            $language = Language::where('main', 1)->first();
        }
        if (! $language) {
            $language = Language::first();
        }
        $languageId = $language ? $language->id : null;
        $langShorts = $language->shortname;
        $sliders = Slider::with('langs')->orderBy('id')->get();

        // Our Numbers
        $stats = [
            'services' => 8,
            'students' => DB::table('users')->where('type', 1)->count(),
            'tutors' => DB::table('users')->where('type', 2)->count(),
            'courses' => DB::table('courses')->where('status', 'published')->count(),
        ];

        // our course
        $categoryId = $request->query('category_id');

        $course_categories = CourseCategory::query()
            // ->with('langs')
            ->select('id', 'name')
            // ->where('status', 1)      // اختياري لو بدك المفعّلة فقط
            ->orderBy('id')
            ->get();

        $coursesQuery = Course::query()
            ->with(['category:id,name', 'instructor:id,name', 'langs', 'activeDiscount'])
            ->where('status', 'published')
            ->latest('id');

        if ($categoryId) {
            $coursesQuery->where('category_id', $categoryId);
        }

        $courses = $coursesQuery->limit(12)->get();

        $subjects = Subject::query()->orderBy('name')->get();
        $languages = Language::query()->orderBy('name')->get();
        $countries = Country::query()->orderBy('en_name')->get();
        $specializations = Specialization::query()->orderBy('name')->get();

        // Popular Tutors
        $tutors = User::query()
            ->where('type', 2)
            ->with([
                'profile',
                'tutorProfile',
                'reviews'
            ])
            ->latest('id')
            ->limit(12)
            ->get();


        return view('front.home', compact('tutors', 'stats', 'course_categories', 'courses', 'categoryId', 'sliders', 'languageId', 'langShorts', 'subjects', 'languages', 'countries', 'specializations'));
    }

    public function blog(Request $request)
    {
        $locale = app()->getLocale();
        $language = Language::where('shortname', $locale)->first();

        if (! $language) {

            $language = Language::where('main', 1)->first();
        }

        if (! $language) {

            $language = Language::first();
        }

        $languageId = $language ? $language->id : null;

        // جلب جميع الفئات مع كل الترجمات
        $categories = CateqBlog::with('langsAll')->get();

        // لكل فئة، نحدد الترجمة المناسبة للعرض
        $categories->each(function ($category) use ($languageId) {
            // نحاول الحصول على الترجمة باللغة الحالية
            $translation = $category->langsAll->where('language_id', $languageId)->first();

            // إذا لم نجد ترجمة باللغة الحالية، نأخذ أول ترجمة متاحة
            if (! $translation) {
                $translation = $category->langsAll->first();
            }

            // نستبدل langsAll بـ collection يحتوي على ترجمة واحدة فقط
            $category->setRelation('langsAll', collect([$translation]));
        });

        // جلب جميع المدونات المنشورة مع كل الترجمات
        $blogsQuery = Blog::filter($request->query())
            ->with('category', 'category.langsAll', 'users:id,name', 'langsAll')
            ->published();

        $blogs = $blogsQuery->get();

        // لكل مدونة، نحدد الترجمة المناسبة للعرض
        $blogs->each(function ($blog) use ($languageId) {
            // نحاول الحصول على الترجمة باللغة الحالية
            $translation = $blog->langsAll->where('language_id', $languageId)->first();

            // إذا لم نجد ترجمة باللغة الحالية، نأخذ أول ترجمة متاحة
            if (! $translation) {
                $translation = $blog->langsAll->first();
            }

            // نستبدل langsAll بـ collection يحتوي على ترجمة واحدة فقط
            $blog->setRelation('langsAll', collect([$translation]));

            // نفس الشيء للفئة الخاصة بالمدونة
            if ($blog->category) {
                $categoryTranslation = $blog->category->langsAll->where('language_id', $languageId)->first();

                if (! $categoryTranslation) {
                    $categoryTranslation = $blog->category->langsAll->first();
                }

                // نستبدل langsAll للفئة بـ collection يحتوي على ترجمة واحدة فقط
                $blog->category->setRelation('langsAll', collect([$categoryTranslation]));
            }
        });

        return view('front.blog', compact('categories', 'blogs', 'languageId'));
    }

    public function showBlog(Request $request, string $slug)
    {
        $locale = app()->getLocale();
        $language = Language::where('shortname', $locale)->first();

        if (! $language) {

            $language = Language::where('main', 1)->first();
        }

        if (! $language) {

            $language = Language::first();
        }

        $languageId = $language ? $language->id : null;
        $slug = (string) $request->route('slug', $slug);

        // البحث عن المدونة بناءً على slug في علاقة langsAll
        $blog = Blog::with('category', 'category.langs', 'users:id,name', 'langsAll.language')
            ->whereHas('langsAll', function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->first();

        // إذا لم نجد المدونة نهائياً، نرجع 404
        if (! $blog) {
            abort(404);
        }

        // إذا وجدنا المدونة، نتحقق من اللغة الحالية
        // إذا كانت اللغة الحالية مختلفة عن لغة الـ slug، نعيد تحميل البيانات باللغة الصحيحة
        $currentSlugLang = $blog->langsAll->where('slug', $slug)->first();

        if ($currentSlugLang && $languageId && $currentSlugLang->language_id != $languageId) {
            // نبحث عن الترجمة باللغة الحالية
            $translationInCurrentLang = $blog->langsAll->where('language_id', $languageId)->first();

            if ($translationInCurrentLang && $translationInCurrentLang->slug) {
                // إعادة توجيه للـ slug الصحيح باللغة الحالية
                return redirect()->route('site.showBlog', $translationInCurrentLang->slug);
            }
        }

        // نتأكد من تحميل البيانات باللغة الصحيحة
        $blog->load(['langsAll' => function ($query) use ($languageId) {
            if ($languageId) {
                $query->where('language_id', $languageId);
            }
        }]);

        // إذا لم نجد بيانات باللغة الحالية، نجيب أول لغة متاحة
        if ($blog->langsAll->isEmpty()) {
            $blog->load(['langsAll' => function ($query) {
                $query->orderBy('id', 'asc')->limit(1);
            }]);
        }

        // جلب المدونات الأخرى (باستثناء المدونة الحالية)
        $blogsQuery = Blog::filter($request->query())
            ->with(['category', 'category.langs', 'users:id,name', 'langsAll' => function ($query) use ($languageId) {
                // نجيب اللغة المطلوبة أولاً
                if ($languageId) {
                    $query->where('language_id', $languageId);
                }
            }, 'langsAll.language'])
            ->published()
            ->where('id', '!=', $blog->id);

        $blogs = $blogsQuery->take(5)->get();

        // لو المدونات ما فيها بيانات باللغة المطلوبة، نجيب أول لغة متاحة
        $blogs = $blogs->map(function ($blogItem) use ($languageId) {
            // إذا ما في بيانات باللغة الحالية
            if ($blogItem->langsAll->isEmpty() || ! $blogItem->langsAll->where('language_id', $languageId)->first()) {
                // نجيب أول لغة متاحة
                $blogItem->load(['langsAll' => function ($query) {
                    $query->orderBy('id', 'asc')->limit(1);
                }]);
            }

            return $blogItem;
        });

        return view('front.singlebloge', compact('blog', 'blogs'));
    }

    public function contact_us()
    {
        return view('front.contact_us');
    }

    public function coming_soon()
    {
        return view('front.coming_soon');
    }

    public function online_group_classes()
    {
        // Resolve current language (same logic used in blog())
        $locale = app()->getLocale();
        $language = Language::where('shortname', $locale)->first();
        if (! $language) {
            $language = Language::where('main', 1)->first();
        }
        if (! $language) {
            $language = Language::first();
        }
        $languageId = $language ? $language->id : null;

        $groupClassController = new GroupClassController;
        $request = new Request;
        $request->limit = 1000;
        $response = $groupClassController->getAssignedGroupClass($request, null);
        $original = $response->getOriginalContent();

        $classes = collect($original['result']->items());  // تحويل المصفوفة إلى Collection

        // تصفية الكلاسات حسب الشروط المطلوبة
        $now = Carbon::now();
        $classes = $classes->filter(function ($class) use ($now) {
            // 1. التحقق من أن الكلاس فعال (status = 1)
            if ($class->status != 1) {
                return false;
            }

            // 2. التحقق من وجود معلم مرتبط بالكلاس
            if (! $class->tutor_id) {
                return false;
            }

            // 3. التحقق من أن المعلم موافق عليه في group_class_tutors
            $tutorApproved = GroupClassTutor::where('group_class_id', $class->id)
                ->where('tutor_id', $class->tutor_id)
                ->where('status', 'approved')
                ->exists();

            if (! $tutorApproved) {
                return false;
            }

            // 4. التحقق من أن جميع الحصص لم تنتهي (class_date > now)
            if ($class->dates->isEmpty()) {
                return false;
            }

            // تحقق إذا الحصة اليوم أو إذا فقط في حصة واحدة، باقي الحصص لازم تكون بعد الآن أو اليوم نفسه
            $allSessionsValid = $class->dates->every(function ($date) use ($now) {
                $dateObj = Carbon::parse($date->class_date);

                // إذا الحصة اليوم تعتبر مقبولة أيضاً
                return $dateObj->isAfter($now) || $dateObj->isSameDay($now);
            });

            if (! $allSessionsValid) {
                return false;
            }

            if ($class->level?->level_number > 1) {
                if ($class->exams?->count() <= 0) {
                    return false;
                }
            }

            return true;
        });

        foreach ($classes as $class) {
            // Keep only current language translation
            $translation = $class->langsAll->where('language_id', $languageId)->first();
            if (! $translation) {
                $translation = $class->langsAll->first();
            }
            $class->setRelation('langsAll', collect([$translation]));

            // Rating and reviews count
            $class->rating = round((float) $class->reviews()->avg('stars'), 2);
            $class->reviews_count = (int) $class->reviews()->count('id');

            // First session date/time
            $firstDate = optional($class->dates->first())->class_date;
            if ($firstDate) {
                $class->first_date = date('Y-m-d', strtotime($firstDate));
                $class->first_time = date('h:i A', strtotime($firstDate));
            } else {
                $class->first_date = null;
                $class->first_time = null;
            }

            // Tutor summary
            if ($class->tutor) {
                $class->tutor->email = null;
                $class->tutor_name = $class->tutor->full_name;
                $class->tutor_price = $class->tutor->hourlyPrices()->first()->price ?? 0;
            } else {
                $class->tutor_name = null;
                $class->tutor_price = 0;
            }
        }

        return view('front.online_group_classes', compact('classes', 'languageId'));
    }

    public function groupClassDetails(string $locale, string|int $id)
    {
        // Resolve current language (same approach as blog())
        $locale = app()->getLocale();
        $language = Language::where('shortname', $locale)->first();
        if (! $language) {
            $language = Language::where('main', 1)->first();
        }
        if (! $language) {
            $language = Language::first();
        }
        $languageId = $language ? $language->id : null;

        // Load group class with relations
        $group_class = GroupClass::with([
            'level',
            'language',
            'category',
            'category.langsAll',
            'langsAll.language',
            'dates',
            'reviews.user',
            'tutor',
            'tutor.hourlyPrices',
            'tutor.reviews',
            'tutor.descriptions',
            'tutor.abouts.language',
            'tutor.abouts.subject',
            'tutor.videos',
            'imageInfo',
            'attachment',
            'exams',
        ])->find($id);

        if (! $group_class) {
            abort(404);
        }

        // Pick only the current language translation for the class
        $classTranslation = $group_class->langsAll->where('language_id', $languageId)->first();
        if (! $classTranslation) {
            $classTranslation = $group_class->langsAll->first();
        }
        $group_class->setRelation('langsAll', collect([$classTranslation]));

        // Category translation limited to current language
        if ($group_class->category) {
            $catTranslation = $group_class->category->langsAll->where('language_id', $languageId)->first();
            if (! $catTranslation) {
                $catTranslation = $group_class->category->langsAll->first();
            }
            $group_class->category->setRelation('langsAll', collect([$catTranslation]));
        }

        // Compute rating
        $group_class->rating = round((float) $group_class->reviews()->avg('stars'), 2);

        // Tutor derived fields
        if ($group_class->tutor) {
            $group_class->tutor->email = null;
            $group_class->tutor->price = $group_class->tutor->hourlyPrices()->first()->price ?? 0;
            $tutorAbout = $group_class->tutor->abouts()->first();
            if ($tutorAbout) {
                $group_class->tutor->language = optional($tutorAbout->language()->first())->name;
                $group_class->tutor->subject = optional($tutorAbout->subject()->first())->name;
            } else {
                $group_class->tutor->language = null;
                $group_class->tutor->subject = null;
            }
        }
        $dates = $group_class->dates->filter(function ($date) {
            if ($date->class_date < Carbon::now()) {
                return false;
            }

            return true;
        });
        $group_class->dates = $dates;

        $order = Order::where('user_id', Auth::id())->where('ref_type', 1)->where('ref_id', $id)->where('status', 1)->first();
        $hasOrder = false;
        if ($order) {
            $hasOrder = true;
        }

        $exams = collect([]);
        $attempts = ExamAttempt::where('exam_id', $group_class->exams?->first()?->id)->where('student_id', Auth::user()?->id)->orderBy('id', 'desc')->first();
        $result = $attempts ? $attempts->result : null;
        foreach ($group_class->exams as $exam) {
            $exams->push(Exam::with('level', 'category', 'langsAll', 'groupClass:id,name')->find($exam->id));
        }

        $groupClassController = new GroupClassController;
        $request = new Request;
        $request->limit = 1000;
        $response = $groupClassController->getAssignedGroupClass($request, null);
        $original = $response->getOriginalContent();

        $suggestions = collect($original['result']->items());  // تحويل المصفوفة إلى Collection

        // تصفية الكلاسات حسب الشروط المطلوبة
        $now = Carbon::now();
        $suggestions = $suggestions->filter(function ($class) use ($now, $id) {

            if ($class->id == $id) {
                return false;
            }

            // 1. التحقق من أن الكلاس فعال (status = 1)
            if ($class->status != 1) {
                return false;
            }

            // 2. التحقق من وجود معلم مرتبط بالكلاس
            if (! $class->tutor_id) {
                return false;
            }

            // 3. التحقق من أن المعلم موافق عليه في group_class_tutors
            $tutorApproved = GroupClassTutor::where('group_class_id', $class->id)
                ->where('tutor_id', $class->tutor_id)
                ->where('status', 'approved')
                ->exists();

            if (! $tutorApproved) {
                return false;
            }

            // 4. التحقق من أن جميع الحصص لم تنتهي (class_date > now)
            if ($class->dates->isEmpty()) {
                return false;
            }

            // تحقق إذا الحصة اليوم أو إذا فقط في حصة واحدة، باقي الحصص لازم تكون بعد الآن أو اليوم نفسه
            $allSessionsValid = $class->dates->every(function ($date) use ($now) {
                $dateObj = Carbon::parse($date->class_date);

                // إذا الحصة اليوم تعتبر مقبولة أيضاً
                return $dateObj->isAfter($now) || $dateObj->isSameDay($now);
            });

            // إذا يوجد فقط حصة واحدة نسمح بعرضها حتى ولو كانت اليوم
            if ($class->dates->count() == 1) {
                $allSessionsValid = true;
            }

            if (! $allSessionsValid) {
                return false;
            }

            return true;
        });

        // dd($group_class,$suggestions,$languageId);
        return view('front.class_details', compact('group_class', 'exams', 'suggestions', 'languageId', 'result', 'attempts', 'hasOrder'));
    }

    public function groupClassOrder(string $locale, Request $request, string|int $id)
    {
        DB::beginTransaction();
        try {
            $class = GroupClass::where('id', $id)->first();
            if (! $class) {
                return redirect()->back()->with('error', 'Class not found');
            }

            $order = Order::where('user_id', Auth::id())->where('ref_type', 1)->where('ref_id', $id)->first();
            $orderNotPay = false;
            $orderId = null;
            if ($order && $order->status == 1) {
                return redirect()->route('redirect.dashboard')->with('error', 'You have already booked a lesson with this tutor');
            }
            if ($order && $order->status != 1) {
                $orderNotPay = true;
                $orderId = $order->id;
            }

            if (! $order) {
                $orderController = new OrderController;
                $response = $orderController->groupClass($request, $id);
                $original = $response->getOriginalContent();
                if ($original['success']) {
                    $orderId = $original['order_id'] ?? $original['result']['id'];
                }
            }
            if ($orderId) {
                $walletController = new WalletController;
                $responseCheckout = $walletController->checkout($orderId);
                $originalCheckout = $responseCheckout->getOriginalContent();

                if (! $originalCheckout['success']) {
                    return redirect()->back()->with('error', $originalCheckout['message']);
                }
                $order = Order::find($orderId);

                if (! $order) {
                    DB::rollBack();

                    return redirect()->back()->with('error', 'Order not found');
                }

                // Check if user wants to pay directly from wallet or go to checkout
                $payDirectly = $request->get('pay_directly', false); // يمكن إرسالها من الـ frontend

                // Check wallet balance
                $user = Auth::user();
                $wallet = $user->wallets()->first();
                $walletBalance = $wallet ? $wallet->balance : 0;
                $hasEnoughBalance = $walletBalance >= $order->price;

                // If user wants to pay directly AND has enough balance
                if ($payDirectly && $hasEnoughBalance) {
                    // Pay directly from wallet
                    $walletController = new WalletController;
                    $responseCheckout = $walletController->checkout($orderId);
                    $originalCheckout = $responseCheckout->getOriginalContent();

                    if ($originalCheckout['success']) {
                        DB::commit();

                        return redirect()->route('redirect.dashboard')->with('success', $originalCheckout['message']);
                    } else {
                        DB::rollBack();

                        // If direct payment failed, redirect to checkout
                        return redirect()->route('checkout', [
                            'type' => 'pay',
                            'order_ids' => $orderId,
                        ])->with('error', $originalCheckout['message'] ?? 'Payment failed');
                    }
                } else {
                    // Redirect to checkout page to choose payment method
                    DB::commit();

                    return redirect()->route('checkout', [
                        'type' => 'pay',
                        'order_ids' => $orderId,
                    ])->with('info', $hasEnoughBalance
                        ? 'You can pay from your wallet or choose another payment method'
                        : 'Please complete payment to finish your order');
                }
            } else {
                DB::rollBack();

                return redirect()->back()->with('error', $original['message']);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function online_private_classes(Request $request)
    {
        $filters = [
            'filterSubject' => $request->filterSubject,
            'filterPriceRange' => $request->filterPriceRange,
            'filterNativeLanguage' => $request->filterNativeLanguage,
            'filterAvailability' => $request->filterAvailability,
            'filterSpecialization' => $request->filterSpecialization,
            'filterCountry' => $request->filterCountry,
            'filterAlsoSpeaks' => $request->filterAlsoSpeaks,
            'filterSortBy' => $request->filterSortBy,
            'filterFullName' => $request->filterFullName,
        ];

        $tutors = User::where('type', 2)
            ->with(['profile', 'tutorProfile'])
            ->orderBy('id', 'desc')
            ->get();

        $subjects = Subject::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $countries = Country::orderBy('en_name')->get();
        $specializations = Specialization::orderBy('name')->get();

        return view('front.online_private_classes', compact(
            'tutors',
            'subjects',
            'languages',
            'countries',
            'specializations',
            'filters'
        ));
    }

    public function tutor_jinn(string $locale, string|int $id)
    {

        $tutor = User::with(['profile', 'tutorProfile'])
            ->where('type', 2)
            ->findOrFail($id);

        if (! $tutor->tutorProfile) {
            abort(404);
        }
        $orderTrial = Order::where('user_id', Auth::id())->where('ref_type', 3)->where('ref_id', $tutor->id)->first();
        $orderTrialExists = false;
        $orderTrialFinash = false;
        if ($orderTrial) {
            $orderTrialExists = true;
            $conferences = Conference::where('order_id', $orderTrial->id)->first();
            if ($conferences) {
                $status = $conferences->status;
                if ($status == 1) {
                    $orderTrialFinash = true;
                }
            }
        }
        $order = Order::where('user_id', Auth::id())->where('ref_type', 4)->where('tutor_id', $tutor->id)->first();
        $checkAllowOrder = false;
        if ($order) {
            $conferences = Conference::where('order_id', $order->id)->first();
            if ($conferences) {
                $status = $conferences->status;
                if ($status == 1) {
                    $checkAllowOrder = true;
                }
            }
            if (! $conferences) {
                $checkAllowOrder = true;
            }
            // $order->dates = json_decode($order->dates);
            // $time = $order->dates?->start_date_time;

            // // نتأكد إذا الوقت مر أو لا، والتخزين بيكون true إذا الوقت انتهى و false إذا لسه
            // $checkAllowOrder = Carbon::now()->lt(Carbon::parse($time));
        } else {
            $checkAllowOrder = true;
        }

        $availabilities = $tutor->getFilteredAvailabilities();
        $tutorsSuggestions = User::where('type', 2)
            ->with([
                'profile',
                'tutorProfile',
            ])
            ->where('id', '!=', $tutor->id)
            ->take(2)
            ->orderBy('id', 'desc')
            ->get();
        $reviews = TutorReview::where('tutor_id', $tutor->id)->get();
        $reviewsCount = $reviews->count();
        $orderTrialExists = true;
        $orderTrialFinash = true;

        return view('front.tutor_jinn', compact('tutor', 'availabilities', 'tutorsSuggestions', 'reviewsCount', 'reviews', 'checkAllowOrder', 'orderTrialExists', 'orderTrialFinash'));
    }

    public function privateLessonOrder(string $locale, Request $request, string|int $id)
    {
        DB::beginTransaction();
        try {
            $tutor = \App\Models\Tutor::find($id);
            if (! $tutor) {
                return redirect()->back()->with('error', 'Tutor not found');
            }

            $order = Order::where('user_id', Auth::id())->where('ref_type', 4)->where('tutor_id', $tutor->id)->first();
            if ($order) {
                $conferences = Conference::where('order_id', $order->id)->first();
                if ($conferences) {
                    $status = $conferences->status;
                    if ($status == 1) {
                        return redirect()->route('redirect.dashboard')->with('error', 'You have already booked a lesson with this tutor');
                    }
                }
            }

            $orderController = new OrderController;

            $request->date = $orderController->getLastAvailableBookingTime($tutor, 60);
            if (! $request->date['success']) {
                return redirect()->back()->with('error', 'No available time found');
            }

            $response = $orderController->privateLesson($request, $id);
            $original = $response->getOriginalContent();
            if ($original['success']) {
                $walletController = new WalletController;
                $responseCheckout = $walletController->checkout($original['result']['id'] ?? $original['order_id']);
                $originalCheckout = $responseCheckout->getOriginalContent();

                if (! $originalCheckout['success']) {
                    return redirect()->back()->with('error', $originalCheckout['message']);
                }

                $orderId = $original['result']['id'] ?? $original['order']['id'];
                $order = Order::find($orderId);

                if (! $order) {
                    DB::rollBack();

                    return redirect()->back()->with('error', 'Order not found');
                }

                // هل يريد الدفع مباشرة من المحفظة؟
                $payDirectly = $request->get('pay_directly', false);

                $user = Auth::user();
                $wallet = $user->wallets()->first();
                $walletBalance = $wallet ? $wallet->balance : 0;
                $hasEnoughBalance = $walletBalance >= $order->price;
                // الدفع المباشر
                if ($payDirectly && $hasEnoughBalance) {
                    $responseCheckout = $walletController->checkout($orderId);
                    $originalCheckout = $responseCheckout->getOriginalContent();

                    if ($originalCheckout['success']) {
                        $walletController->addTutorFinance($order, $order->ref_id, $order->ref_type);
                        
                        DB::commit();

                        return redirect()->route('redirect.dashboard')
                            ->with('success', $originalCheckout['message']);
                    } else {
                        DB::rollBack();

                        // إذا فشل الدفع نرسله لصفحة checkout
                        return redirect()->route('checkout', [
                            'type' => 'pay',
                            'order_ids' => $orderId,
                        ])->with('error', $originalCheckout['message'] ?? 'Payment failed');
                    }
                }

                // التوجيه إلى صفحة الدفع مع خيارات الدفع
                DB::commit();

                return redirect()->route('checkout', [
                    'type' => 'pay',
                    'order_ids' => $orderId,
                ])->with(
                    'info',
                    $hasEnoughBalance
                        ? 'You can pay from your wallet or choose another payment method'
                        : 'Please complete payment to finish your order'
                );
            } else {
                DB::rollBack();

                return redirect()->back()->with('error', $original['message']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // throw $e;

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function trialLessonOrder(string $locale, Request $request, string|int $id)
    {
        DB::beginTransaction();
        try {
            $tutor = \App\Models\Tutor::find($id);
            if (! $tutor) {
                return redirect()->back()->with('error', 'Tutor not found');
            }

            $orderController = new OrderController;

            $request->date = $orderController->getLastAvailableBookingTime($tutor, 15);
            if (! $request->date['success']) {
                return redirect()->back()->with('error', 'No available time found');
            }

            $order = Order::where('user_id', Auth::id())->where('ref_type', 3)->where('ref_id', $tutor->id)->first();
            if ($order) {
                return redirect()->back()->with('error', 'You have already booked a trial lesson with this tutor');
            }

            $response = $orderController->trialLesson($request, $id);
            $original = $response->getOriginalContent();
            if ($original['success']) {

                $orderId = $original['result']['id'] ?? $original['order']['id'];
                $order = Order::find($orderId);

                if (! $order) {
                    DB::rollBack();

                    return redirect()->back()->with('error', 'Order not found');
                }

                DB::commit();

                return redirect()->route('redirect.dashboard', ['redirect_to' => '/conferences/student-index'])
                    ->with('success', 'Trial lesson booked successfully');
            } else {
                DB::rollBack();

                return redirect()->back()->with('error', $original['message']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
