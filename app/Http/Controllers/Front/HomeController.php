<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GroupClassController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Models\Blog;
use App\Models\Category;
use App\Models\CateqBlog;
use App\Models\GroupClass;
use App\Models\GroupClassTutor;
use App\Models\Language;
use App\Models\OurCourse;
use App\Models\Slider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $sliders = Slider::with('langs')->orderBy('id')->get();

        // Our Numbers
        $stats = [
            'services' => 8,
            'students' => DB::table('users')->where('type', 1)->count(),
            'tutors' => DB::table('users')->where('type', 2)->count(),
            'courses' => DB::table('our_courses')->where('status', 1)->count(),
        ];

        // our course
        $categoryId = $request->query('category_id');
        $categories = Category::query()
            ->with('langs')
            ->select('id', 'name', 'parent_id')
            ->where('parent_id', 0)     // بدّلناها بدل whereNull
            // ->where('status', 1)      // اختياري لو بدك المفعّلة فقط
            ->orderBy('id')
            ->get();

        $coursesQuery = OurCourse::query()
            ->with(['langs', 'imageInfo', 'category:id,name'])
            ->select('id', 'name', 'slug', 'category_id', 'lessons', 'class_length', 'image', 'status')
            ->where('status', 1)
            ->latest('id');

        if ($categoryId) {
            $coursesQuery->where('category_id', $categoryId);
        }

        $courses = $coursesQuery->limit(12)->get();

        // Popular Tutors
        $tutors = User::query()
            ->where('type', 2)
            ->with([
                'abouts.country:id,name',
                'descriptions.specialization:id,name',
            ])
            ->latest('id')
            ->limit(12)
            ->get();

        return view('front.home', compact('tutors', 'stats', 'categories', 'courses', 'categoryId', 'sliders', 'languageId'));
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
                $class->tutor_name = $class->tutor->name;
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
            'category', 'category.langsAll',
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
        // // Suggestions (same category), each limited to current language
        // $suggestions = GroupClass::with([
        //     'langsAll.language',
        //     'dates',
        //     'reviews',
        //     'tutor',
        //     'imageInfo',
        // ])
        // ->where('category_id', $group_class->category_id)
        // ->where('id', '<>', $group_class->id)
        // ->limit(6)
        // ->get();

        // foreach ($suggestions as $suggestion) {
        //     $sTrans = $suggestion->langsAll->where('language_id', $languageId)->first();
        //     if (!$sTrans) {
        //         $sTrans = $suggestion->langsAll->first();
        //     }
        //     $suggestion->setRelation('langsAll', collect([$sTrans]));
        //     $suggestion->rating = $suggestion->reviews()->avg('stars');
        //     if ($suggestion->tutor) {
        //         $suggestion->tutor->email = null;
        //     }
        // }
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
        return view('front.class_details', compact('group_class', 'suggestions', 'languageId'));
    }

    public function groupClassOrder(string $locale, Request $request, string|int $id)
    {
        DB::beginTransaction();
        try {
            $class = GroupClass::where('id', $id)->first();
            if (! $class) {
                return redirect()->back()->with('error', 'Class not found');
            }
            $orderController = new OrderController;
            $response = $orderController->groupClass($request, $id);
            $original = $response->getOriginalContent();

            if ($original['success']) {
                $walletController = new WalletController();
                $responseCheckout = $walletController->checkout($original['order_id']);
                $originalCheckout = $responseCheckout->getOriginalContent();                
                
                if($originalCheckout['success']){
                    DB::commit();
                    return redirect()->route('redirect.dashboard')->with('success', $originalCheckout['message']);
                }

            } else {
                return redirect()->back()->with('error', $original['message']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
