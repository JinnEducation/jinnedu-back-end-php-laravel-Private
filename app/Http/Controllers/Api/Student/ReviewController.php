<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseReview, CourseEnrollment};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request, Course $course)
    {
        abort_if($course->status !== 'published', 404);

        $reviews = CourseReview::where('course_id', $course->id)
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }

    public function store(Request $request, Course $course)
    {
        abort_if($course->status !== 'published', 404);

        $user = $request->user();

        $enrolled = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        if (!$enrolled) {
            return response()->json(['message' => 'You must be enrolled to review.'], 403);
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        $review = CourseReview::updateOrCreate(
            ['course_id' => $course->id, 'user_id' => $user->id],
            $data
        );

        return response()->json([
            'message' => 'Review saved.',
            'review' => $review,
        ], 201);
    }
}
