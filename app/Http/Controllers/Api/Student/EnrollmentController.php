<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseEnrollment};
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, Course $course)
    {
        abort_if($course->status !== 'published', 404);

        $user = $request->user();

        $data = $request->validate([
            'order_id' => 'nullable|integer', // لو مدفوع (جاهز عندك)
        ]);

        // لو الكورس مدفوع وبدون order_id: ممنوع
        if (!$course->is_free && $course->final_price > 0 && empty($data['order_id'])) {
            return response()->json(['message' => 'order_id is required for paid course.'], 422);
        }

        $enrollment = CourseEnrollment::firstOrCreate(
            ['course_id' => $course->id, 'user_id' => $user->id],
            ['order_id' => $data['order_id'] ?? null, 'enrolled_at' => now()]
        );

        return response()->json([
            'message' => 'Enrolled.',
            'enrollment' => $enrollment,
        ], 201);
    }
}
