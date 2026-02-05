<?php

namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Models\{Course, CourseReview, CourseEnrollment, Language};
use Illuminate\Http\Request;
use App\Models\CourseCertificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as PDF;
use Illuminate\Support\Facades\Storage;


class StudentCourseController extends Controller
{

    public function myCourses(Request $request)
    {
        $user = $request->user();
        $type = $request->get('type', 'all'); // all | completed | unfinished

        // ✅ جلب كل اللغات مرة واحدة (keyed by shortname)
        $languagesMap = Language::all()->keyBy('shortname');

        // ✅ جلب الكورسات المسجّل بها المستخدم
        $courseIds = CourseEnrollment::where('user_id', $user->id)
            ->pluck('course_id');

        $courses = Course::query()
            ->whereIn('id', $courseIds)
            ->where('status', 'published')
            ->with(['langs']) // eager loading
            ->withCount([
                'items as total_items',
                'items as completed_items' => function ($q) use ($user) {
                    $q->whereHas('progresses', function ($p) use ($user) {
                        $p->where('user_id', $user->id)
                            ->where('status', 'completed');
                    });
                },
            ])
            ->latest('id')
            ->get()
            ->map(function ($course) use ($languagesMap) {

                $total = (int) $course->total_items;
                $done  = (int) $course->completed_items;

                $percent = $total > 0
                    ? (int) round(($done / $total) * 100)
                    : 0;

                // ✅ نفس الناتج – بدون أي query إضافي
                $languages = $course->langs->mapWithKeys(function ($language) use ($languagesMap) {
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
                    'id' => $course->id,
                    'title' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['title']]) ?? $course->title,
                    'description' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['description']]) ?? $course->description,
                    'total_items' => $total,
                    'completed_items' => $done,
                    'progress_percent' => $percent,
                    'has_certificate' => $course->has_certificate,
                    'course_image' => $course->course_image_full,
                ];
            });

        // ✅ فلترة حسب النوع
        if ($type === 'completed') {
            $courses = $courses->where('progress_percent', 100)->values();
        } elseif ($type === 'unfinished') {
            $courses = $courses->where('progress_percent', '<', 100)->values();
        }

        return response()->json($courses);
    }


    public function certificate(Request $request, Course $course)
    {
        abort_if($course->status !== 'published', 404);

        $user = $request->user() ?? Auth::user();

        // 1) تحقق التسجيل
        $enrolled = CourseEnrollment::where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->exists();

        abort_if(!$enrolled, 403, 'Not enrolled');

        // 2) تحقق أن الكورس يدعم شهادة
        abort_if(!$course->has_certificate, 404);

        // 3) تحقق اكتمال الكورس
        $totalItems = $course->items()->count();

        $completedItems = $course->items()
            ->whereHas('progresses', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->where('status', 'completed');
            })
            ->count();

        abort_if($totalItems === 0 || $completedItems < $totalItems, 403);

        // 4) إنشاء أو جلب سجل الشهادة
        $certificate = CourseCertificate::firstOrCreate(
            [
                'course_id' => $course->id,
                'user_id'   => $user->id,
            ],
            [
                'certificate_code' => strtoupper(Str::random(10)),
                'issued_at'        => now(),
            ]
        );

        // 5) توليد الملف إذا لم يكن موجود
        if (!$certificate->file_url || !Storage::disk('public')->exists($certificate->file_url)) {
            $pdf = PDF::loadView(
                'certificates.course',
                [
                    'user'        => $user,
                    'course'      => $course,
                    'certificate' => $certificate,
                ],
                [],
                [
                    'mode' => 'utf-8',
                    'format' => 'A4-L',
                    'default_font_size' => 12,
                    'default_font' => 'Arial',
                ]
            );
            return $pdf->stream();

            $path = 'certificates/course_' . $course->id . '_' . $user->id . '.pdf';

            Storage::disk('public')->put($path, $pdf->output());

            $certificate->update([
                'file_url' => $path,
                'issued_at' => now(),
            ]);
        }

        // 6) تحميل الشهادة
        return Storage::disk('public')->download(
            $certificate->file_url,
            'certificate-' . $course->id . '.pdf'
        );
    }
}
