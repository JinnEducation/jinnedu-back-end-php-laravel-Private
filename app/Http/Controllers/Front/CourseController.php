<?php

namespace App\Http\Controllers\Front;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
   public function singlecourse($id)
    {
        
       
        $course = Course::with([
                'category',
                'langs',
                'units.lessons',
                'reviews.user',
            ])
            ->findOrFail($id);

   
        $lang = app()->getLocale(); // 'en' or 'ar'
        $courseLang = $course->langs?->firstWhere('lang', $lang) ?? $course->langs?->first();

        $reviews = $course->reviews ?? collect();
        $avgRating = round((float) $reviews->avg('rating'), 1);
        $reviewsCount = (int) $reviews->count();

         $starPercents = [];
        for ($s = 5; $s >= 1; $s--) {
            $count = (int) $reviews->where('rating', $s)->count();
            $starPercents[$s] = $reviewsCount > 0 ? round(($count / $reviewsCount) * 100, 1) : 0;
        }

      
        $relatedCourses = Course::with(['category', 'langs'])
            ->when($course->category_id, fn($q) => $q->where('category_id', $course->category_id))
            ->where('id', '!=', $course->id)
            ->latest()
            ->take(8)
            ->get();

        return view('front.singlecourse', [
            'course'        => $course,
            'courseLang'    => $courseLang,
            'reviews'       => $reviews,
            'avgRating'     => $avgRating,
            'reviewsCount'  => $reviewsCount,
            'starPercents'  => $starPercents,
            'relatedCourses'=> $relatedCourses,
        ]);
    }
    
}
