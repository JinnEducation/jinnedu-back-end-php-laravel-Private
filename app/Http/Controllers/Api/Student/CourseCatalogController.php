<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseCatalogController extends Controller
{
    public function index(Request $request)
    {
        $lang = $request->header('lang', 'en');

        $q = Course::query()
            ->where('status', 'published')
            ->with([
                'category:id,name',
                'instructor:id,name',
                'langs',
            ]);

        if ($request->filled('category_id')) $q->where('category_id', $request->category_id);

        return response()->json($q->latest()->paginate(12));
    }

    public function show(Request $request, Course $course)
    {
        $lang = $request->header('lang', 'en');

        abort_if($course->status !== 'published', 404);

        $course->load([
            'category:id,name',
            'instructor:id,name',
            'langs',
            'sections.langs',
            'items.langs',
        ]);

        return response()->json([
            'course' => $course,
            'final_price' => $course->final_price, // accessor
        ]);
    }
}
