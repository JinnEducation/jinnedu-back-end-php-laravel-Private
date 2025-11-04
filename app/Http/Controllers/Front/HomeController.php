<?php

namespace App\Http\Controllers\Front;

use App\Models\Blog;
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
        $perPage = (int) $request->input('per_page', 9);
        $allowedPerPage = [6, 9, 12];
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 9;
        }


        $locale = app()->getLocale();
        $language = Language::where('shortname', $locale)->first();

        if (! $language) {

            $language = Language::where('main', 1)->first();
        }

        if (! $language) {

            $language = Language::first();
        }

        $languageId = $language ? $language->id : null;


        $categoriesQuery = CateqBlog::with('langsAll.language');

        if ($languageId) {
            $categoriesQuery->whereHas('langsAll', function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            });
        }
        $categories = $categoriesQuery->get();

        $categorySlug = $request->query('category');

        $blogsQuery = Blog::filter($request->query())
            ->with('category', 'category.langs', 'users:id,name', 'langsAll.language')
            ->published();

        if ($languageId) {
            $blogsQuery->whereHas('langsAll', function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            });
        }

        $blogs = $blogsQuery->get();
        return view('front.blog', compact('categories', 'blogs', 'categorySlug', 'languageId'));
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

        $blogQuery = Blog::with('category', 'category.langs', 'users:id,name', 'langsAll.language');

        if ($languageId) {
            $blogQuery->whereHas('langsAll', function ($query) use ($languageId,$slug) {
                $query->where('language_id', $languageId);
                $query->where('slug', $slug);
            });
        }

        $blog = $blogQuery->first();

        $blogsQuery = Blog::filter($request->query())
            // ->where('id', '!=', $blog->id)
            ->with('category', 'category.langs', 'users:id,name', 'langsAll.language')
            ->published();

        if ($languageId) {
            $blogsQuery->whereHas('langsAll', function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            });
        }

        $blogs = $blogsQuery->get();

        return view('front.singlebloge', compact('blog','blogs'));
    }

    public function contact_us()
    {
        return view('front.contact_us');
    }
}
