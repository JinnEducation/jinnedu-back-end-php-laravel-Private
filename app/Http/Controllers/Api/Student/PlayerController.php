<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseEnrollment, CourseReview};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    public function player(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        abort_if($course->status !== 'published', 404);

        $lang = $request->header('lang', 'en');
        $user = $request->user();

        $enrolled = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        $course->load([
            'langs' => fn($q) => $q->where('lang', $lang),
            'sections.langs' => fn($q) => $q->where('lang', $lang),
            'items.langs' => fn($q) => $q->where('lang', $lang),
            'items.media',
            'items.liveSession',
            'items.progresses' => function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->where('status', 'completed');
            }
        ]);

        $courseTitle = $course->langs->first()->title ?? $course->title;
        $review = CourseReview::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->first();


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
                'completed' => $item->progresses->isNotEmpty(),
            ];
        });

        return response()->json([
            'course' => [
                'id' => $course->id,
                'title' => $courseTitle,
                'has_certificate' => $course->has_certificate,
                'review' => $review ? [
                    'stars' => $review->rating,
                    'comment' => $review->comment,
                ] : null,
            ],
            'enrolled' => $enrolled,
            'sections' => $course->sections,
            'items' => $items,
        ]);
    }
}
