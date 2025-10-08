<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use App\Models\Category;
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
    ->select('id','name','parent_id')
    ->where('parent_id', 0)     // بدّلناها بدل whereNull
    // ->where('status', 1)      // اختياري لو بدك المفعّلة فقط
    ->orderBy('id')
    ->get();

    
    $coursesQuery = OurCourse::query()
        ->with(['langs','imageInfo','category:id,name'])
        ->select('id','name','slug','category_id','lessons','class_length','image','status')
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
        ->select('id','name','slug','avatar')
        ->latest('id')
        ->limit(12)
        ->get();

        return view('front.home', compact('tutors', 'stats','categories','courses','categoryId'));
    }
}
