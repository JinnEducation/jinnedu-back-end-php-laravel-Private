<?php

namespace App\Http\Controllers\Front;

use App\Models\Blog;
use App\Models\User;
use App\Models\Category;
use App\Models\CateqBlog;
use App\Models\OurCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
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

        return view('front.home', compact('tutors', 'stats', 'categories', 'courses', 'categoryId'));
    }

    public function blog(Request $request)
    {

        $perPage = (int) $request->input('per_page', 9);
        $allowedPerPage = [6, 9, 12];
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 9;
        }


        $categories = CateqBlog::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();


        $categorySlug = $request->query('category');


        $blogs = Blog::query()
            ->with('category')
            ->published()
            ->when($categorySlug, function ($q) use ($categorySlug) {
                $q->whereHas('category', function ($c) use ($categorySlug) {
                    $c->where('slug', $categorySlug);
                });
            })
            ->orderByDesc('date')
            ->paginate($perPage)
            ->withQueryString();



        return view('front.blog', compact('categories', 'blogs', 'categorySlug', 'perPage', 'allowedPerPage'));
    }

    public function showBlog(string $slug)
    {

        $blog = Blog::query()
            ->published()                  
            ->where('slug', $slug)
            ->with([
                'category:id,name,slug',
                'users:id,name',
                'courses' => function ($q) {
                    $q->select(
                        'id', 'name', 'image',
                        'lessons', 'class_length',
                        'category_id', 'blog_id',
                        'publish', 'publish_date'
                    )
                    ->where('publish', 1)
                    ->orderByDesc('publish_date');
                    $q->with(['category:id,name']);
                },
            ])
            ->firstOrFail();

        return view('front.singlebloge', compact('blog'));
    }
}
