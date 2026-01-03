<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseEnrollment};
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function player(Request $request, Course $course)
    {
        abort_if($course->status !== 'published', 404);

        $lang = $request->header('lang', 'en');
        $user = $request->user();

        $enrolled = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        $course->load([
            'sections.langs' => fn($q) => $q->where('lang', $lang),
            'items.langs' => fn($q) => $q->where('lang', $lang),
            'items.media',
            'items.liveSession',
        ]);

        // Apply lock logic in response
        $items = $course->items->map(function ($item) use ($enrolled, $course) {
            $locked = false;

            if (!$item->is_free_preview) {
                // لو الكورس مدفوع: لازم يكون enrolled
                if (!$course->is_free && $course->final_price > 0 && !$enrolled) {
                    $locked = true;
                }
            }

            return [
                'id' => $item->id,
                'course_id' => $item->course_id,
                'section_id' => $item->section_id,
                'type' => $item->type,
                'is_free_preview' => (bool) $item->is_free_preview,
                'duration_seconds' => $item->duration_seconds,
                'sort_order' => $item->sort_order,
                'langs' => $item->langs,
                'media' => $locked ? [] : $item->media,     // إخفاء رابط الفيديو لو locked
                'live_session' => $locked ? null : $item->liveSession,
                'locked' => $locked,
            ];
        });

        return response()->json([
            'enrolled' => $enrolled,
            'sections' => $course->sections,
            'items' => $items,
        ]);
    }
}
