<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\{CourseItem, CourseItemProgress, CourseEnrollment};
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $item = CourseItem::findOrFail($id);
        $course = $item->course;

        $data = $request->validate([
            'status' => 'nullable|in:not_started,in_progress,completed',
            'last_position_seconds' => 'nullable|integer|min:0',
        ]);

        $enrolled = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        // لو المحتوى مش preview والكورس مدفوع والطالب مش enrolled: ممنوع
        if (!$item->is_free_preview && !$course->is_free && $course->final_price > 0 && !$enrolled) {
            return response()->json(['message' => 'Not enrolled.'], 403);
        }

        $progress = CourseItemProgress::updateOrCreate(
            ['course_id' => $course->id, 'item_id' => $item->id, 'user_id' => $user->id],
            [
                'status' => $data['status'] ?? 'in_progress',
                'last_position_seconds' => $data['last_position_seconds'] ?? null,
                'completed_at' => ($data['status'] ?? null) === 'completed' ? now() : null,
            ]
        );

        return response()->json([
            'message' => 'Progress saved.',
            'progress' => $progress,
        ]);
    }
}
