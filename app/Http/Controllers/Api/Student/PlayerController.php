<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseEnrollment, CourseReview, Language};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
    public function player(Request $request, $id)
    {
        $user = $request->user();
        $langHeader = $request->header('lang', 'en');

        // ✅ تحميل الكورس
        $course = Course::findOrFail($id);
        abort_if($course->status !== 'published', 404);

        // ✅ تحميل كل اللغات مرة واحدة
        $languagesMap = Language::all()->keyBy('shortname');

        // ✅ هل المستخدم مسجّل؟
        $enrolled = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        // ✅ eager loading كامل
        $course->load([
            'langs',
            'sections.langs',
            'items.langs',
            'items.media',
            'items.liveSession',
            'items.progresses' => function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->where('status', 'completed');
            }
        ]);

        /* =========================
     * Course title (same output)
     * ========================= */
        $languages = $course->langs->mapWithKeys(function ($language) use ($languagesMap) {
            $lang = $languagesMap->get($language->lang);

            return [
                $lang?->id ?? ($language->lang ?? '1') => [
                    'id' => $language->id,
                    'lang' => $language->lang,
                    'language_id' => $lang?->id ?? '1',
                    'title' => $language->title,
                ]
            ];
        });

        $courseTitle = $languages->mapWithKeys(
            fn($lang, $id) => [$id => $lang['title']]
        ) ?? $course->title;

        /* =========================
     * Review
     * ========================= */
        $review = CourseReview::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->first();

        /* =========================
     * Sections
     * ========================= */
        $sections = $course->sections->map(function ($section) use ($languagesMap) {

            $languagesSection = $section->langs->mapWithKeys(function ($language) use ($languagesMap) {
                $lang = $languagesMap->get($language->lang);

                return [
                    $lang?->id ?? ($language->lang ?? '1') => [
                        'id' => $language->id,
                        'lang' => $language->lang,
                        'language_id' => $lang?->id ?? '1',
                        'title' => $language->title,
                    ]
                ];
            });

            return [
                'id' => $section->id,
                'title' => $languagesSection->mapWithKeys(
                    fn($lang, $id) => [$id => $lang['title']]
                ) ?? $section->title,
            ];
        });

        /* =========================
     * Items + lock logic
     * ========================= */
        $items = $course->items->map(function ($item) use ($enrolled, $course, $languagesMap) {

            $locked = false;

            if (!$item->is_free_preview) {
                if (!$course->is_free && $course->final_price > 0 && !$enrolled) {
                    $locked = true;
                }
            }

            $languagesItem = $item->langs->mapWithKeys(function ($language) use ($languagesMap) {
                $lang = $languagesMap->get($language->lang);

                return [
                    $lang?->id ?? ($language->lang ?? '1') => [
                        'id' => $language->id,
                        'lang' => $language->lang,
                        'language_id' => $lang?->id ?? '1',
                        'title' => $language->title,
                        'description' => $language->description,
                    ]
                ];
            });

            return [
                'id' => $item->id,
                'course_id' => $item->course_id,
                'section_id' => $item->section_id,
                'type' => $item->type,
                'is_free_preview' => (bool) $item->is_free_preview,
                'duration_seconds' => $item->duration_seconds,
                'sort_order' => $item->sort_order,
                'title' => $languagesItem->mapWithKeys(
                    fn($lang, $id) => [$id => $lang['title']]
                ) ?? $item->title,
                'description' => $languagesItem->mapWithKeys(
                    fn($lang, $id) => [$id => $lang['description']]
                ) ?? $item->description,
                'media' => $locked ? [] : $item->media,
                'live_session' => $locked ? null : $item->liveSession,
                'locked' => $locked,
                'completed' => $item->progresses->isNotEmpty(),
            ];
        });

        /* =========================
     * Response (unchanged)
     * ========================= */
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
            'sections' => $sections,
            'items' => $items,
        ]);
    }
}
