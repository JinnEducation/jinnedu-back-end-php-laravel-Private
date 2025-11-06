<?php

namespace App\Http\Controllers\Front;

use App\Models\Blog;
use App\Models\GroupClass;
use App\Models\User;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Language;
use App\Models\CateqBlog;
use App\Models\OurCourse;
use Illuminate\Http\Request;
use App\Models\CategBlogLang;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {

        //Slider
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
            'tutors'   => DB::table('users')->where('type', 2)->count(),
            'courses'  => DB::table('our_courses')->where('status', 1)->count(),
        ];

        //our course
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

        return view('front.home', compact('tutors', 'stats', 'categories', 'courses', 'categoryId','sliders', 'languageId'));
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
            if (!$translation) {
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
            if (!$translation) {
                $translation = $blog->langsAll->first();
            }
            
            // نستبدل langsAll بـ collection يحتوي على ترجمة واحدة فقط
            $blog->setRelation('langsAll', collect([$translation]));

            // نفس الشيء للفئة الخاصة بالمدونة
            if ($blog->category) {
                $categoryTranslation = $blog->category->langsAll->where('language_id', $languageId)->first();
                
                if (!$categoryTranslation) {
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
        if (!$blog) {
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
        $blog->load(['langsAll' => function($query) use ($languageId) {
            if ($languageId) {
                $query->where('language_id', $languageId);
            }
        }]);

        // إذا لم نجد بيانات باللغة الحالية، نجيب أول لغة متاحة
        if ($blog->langsAll->isEmpty()) {
            $blog->load(['langsAll' => function($query) {
                $query->orderBy('id', 'asc')->limit(1);
            }]);
        }

        // جلب المدونات الأخرى (باستثناء المدونة الحالية)
        $blogsQuery = Blog::filter($request->query())
            ->with(['category', 'category.langs', 'users:id,name', 'langsAll' => function($query) use ($languageId) {
                // نجيب اللغة المطلوبة أولاً
                if ($languageId) {
                    $query->where('language_id', $languageId);
                }
            }, 'langsAll.language'])
            ->published()
            ->where('id', '!=', $blog->id);

        $blogs = $blogsQuery->take(5)->get();

        // لو المدونات ما فيها بيانات باللغة المطلوبة، نجيب أول لغة متاحة
        $blogs = $blogs->map(function($blogItem) use ($languageId) {
            // إذا ما في بيانات باللغة الحالية
            if ($blogItem->langsAll->isEmpty() || !$blogItem->langsAll->where('language_id', $languageId)->first()) {
                // نجيب أول لغة متاحة
                $blogItem->load(['langsAll' => function($query) {
                    $query->orderBy('id', 'asc')->limit(1);
                }]);
            }
            return $blogItem;
        });

        return view('front.singlebloge', compact('blog','blogs'));
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
        if (!$language) {
            $language = Language::where('main', 1)->first();
        }
        if (!$language) {
            $language = Language::first();
        }
        $languageId = $language ? $language->id : null;

        // Fetch classes (light payload) prepared for listing page
        $classes = GroupClass::query()
            ->with([
                'langsAll.language',
                'imageInfo',
                'dates' => function ($q) { $q->orderBy('class_date'); },
                'reviews:id,class_id,stars',
                'tutor:id,name',
                'tutor.hourlyPrices:id,price',
            ])
            ->where('status', 1)
            ->latest('id')
            ->get();

        foreach ($classes as $class) {
            // Keep only current language translation
            $translation = $class->langsAll->where('language_id', $languageId)->first();
            if (!$translation) {
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
        if (!$language) {
            $language = Language::where('main', 1)->first();
        }
        if (!$language) {
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

        if (!$group_class) {
            abort(404);
        }

        // Pick only the current language translation for the class
        $classTranslation = $group_class->langsAll->where('language_id', $languageId)->first();
        if (!$classTranslation) {
            $classTranslation = $group_class->langsAll->first();
        }
        $group_class->setRelation('langsAll', collect([$classTranslation]));

        // Category translation limited to current language
        if ($group_class->category) {
            $catTranslation = $group_class->category->langsAll->where('language_id', $languageId)->first();
            if (!$catTranslation) {
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

        // Suggestions (same category), each limited to current language
        $suggestions = GroupClass::with([
            'langsAll.language',
            'dates',
            'reviews',
            'tutor',
            'imageInfo',
        ])
        ->where('category_id', $group_class->category_id)
        ->where('id', '<>', $group_class->id)
        ->limit(6)
        ->get();

        foreach ($suggestions as $suggestion) {
            $sTrans = $suggestion->langsAll->where('language_id', $languageId)->first();
            if (!$sTrans) {
                $sTrans = $suggestion->langsAll->first();
            }
            $suggestion->setRelation('langsAll', collect([$sTrans]));
            $suggestion->rating = $suggestion->reviews()->avg('stars');
            if ($suggestion->tutor) {
                $suggestion->tutor->email = null;
            }
        }
        // dd($group_class,$suggestions,$languageId);
        return view('front.class_details', compact('group_class', 'suggestions', 'languageId'));
    }
}
