<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\CourseLang;
use App\Models\CourseSection;
use App\Models\CourseSectionLang;
use App\Models\User;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    private function mustBeAdmin(Request $request)
    {
        if (($request->user()->type ?? 0) != 0) {
            abort(403, 'Admin only.');
        }
    }

    public function index(Request $request)
    {
        $this->mustBeAdmin($request);

        $lang = $request->header('lang', 'en');

        $q = Course::query()
            ->with([
                'category:id,name',
                'instructor:id,name',
                'langs',
                'activeDiscount'
            ]);

        if ($request->filled('category_id')) {
            $q->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        $courses = $q->paginate(15);

        return CourseResource::collection($courses);

        // return response()->json($q->paginate(15));
    }

    public function store(Request $request)
    {
        $this->mustBeAdmin($request);

        if ($request->has('langs')) {
            $langsPayload = $request->input('langs');

            // إذا جاي JSON string من FormData
            if (is_string($langsPayload)) {
                $langsPayload = json_decode($langsPayload, true);
            }

            if (is_array($langsPayload)) {
                $normalizedLangs = [];

                // mapping ثابت حسب نظامك
                $langMap = Language::query()
                    ->select('id', 'shortname')
                    ->get()
                    ->pluck('shortname', 'id') // [id => shortname]
                    ->toArray();


                foreach ($langMap as $langId => $langCode) {
                    // تجاهل اللغة الفارغة
                    if (
                        empty($langsPayload['title'][$langId]) &&
                        empty($langsPayload['excerpt'][$langId]) &&
                        empty($langsPayload['description'][$langId])
                    ) {
                        continue;
                    }

                    $normalizedLangs[] = [
                        'lang' => $langCode,
                        'title' => $langsPayload['title'][$langId] ?? '',
                        'excerpt' => $langsPayload['excerpt'][$langId] ?? null,
                        'description' => $langsPayload['description'][$langId] ?? null,
                        'outcomes_json' => $langsPayload['outcomes_json'][$langId] ?? [],
                        'requirements_json' => $langsPayload['requirements_json'][$langId] ?? [],
                    ];
                }

                $request->merge([
                    'langs' => $normalizedLangs,
                ]);
            }
        }

        $data = $request->validate([
            'category_id' => 'required|integer|exists:course_categories,id',
            'instructor_id' => 'nullable|integer|exists:users,id',
            'course_image' => 'nullable|string|max:2048',
            'course_duration_hours' => 'nullable|numeric|min:0',
            'certificate_image' => 'nullable|string|max:2048',
            'promo_video_url' => 'nullable|string|max:2048',
            'promo_video_duration_seconds' => 'nullable|integer|min:0',

            'price' => 'nullable|numeric|min:0',
            'is_free' => 'nullable|boolean',

            'discount_type' => 'nullable|in:percent,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_starts_at' => 'nullable|date',
            'discount_ends_at' => 'nullable|date|after_or_equal:discount_starts_at',

            'has_certificate' => 'nullable|boolean',
            'status' => 'nullable|in:draft,published',

            // langs payload (ar/en) optional now, can be saved later
            'langs' => 'nullable|array',
            'langs.*.lang' => 'required_with:langs|string|in:ar,en',
            'langs.*.title' => 'required_with:langs|string|max:255',
            'langs.*.excerpt' => 'nullable|string|max:255',
            'langs.*.description' => 'nullable|string',
            'langs.*.outcomes_json' => 'nullable|array',
            'langs.*.requirements_json' => 'nullable|array',
        ]);

        return DB::transaction(function () use ($data) {

            $course = Course::create([
                'category_id' => $data['category_id'],
                'instructor_id' => $data['instructor_id'] ?? null,

                'course_image' => $data['course_image'] ?? null,
                'course_duration_hours' => $data['course_duration_hours'] ?? null,
                'certificate_image' => $data['certificate_image'] ?? null,

                'promo_video_url' => $data['promo_video_url'] ?? null,
                'promo_video_duration_seconds' => $data['promo_video_duration_seconds'] ?? null,

                'price' => $data['price'] ?? 0,
                'is_free' => $data['is_free'] ?? false,

                'has_certificate' => $data['has_certificate'] ?? false,
                'status' => $data['status'] ?? 'draft',
                'published_at' => ($data['status'] ?? 'draft') === 'published' ? now() : null,
            ]);

            // Save course_langs (optional)
            if (! empty($data['langs'])) {
                foreach ($data['langs'] as $lng) {
                    CourseLang::updateOrCreate(
                        ['course_id' => $course->id, 'lang' => $lng['lang']],
                        [
                            'title' => $lng['title'],
                            'excerpt' => $lng['excerpt'] ?? null,
                            'description' => $lng['description'] ?? null,
                            'outcomes_json' => $lng['outcomes_json'] ?? null,
                            'requirements_json' => $lng['requirements_json'] ?? null,
                        ]
                    );
                }
            }

            // Create discount if provided
            if (! empty($data['discount_type']) && ! empty($data['discount_value'])) {
                $course->discounts()->create([
                    'type' => $data['discount_type'],
                    'value' => $data['discount_value'],
                    'starts_at' => $data['discount_starts_at'] ?? null,
                    'ends_at' => $data['discount_ends_at'] ?? null,
                    'is_active' => true,
                ]);
            }

            // Create default section + its langs
            $section = CourseSection::create([
                'course_id' => $course->id,
                'sort_order' => 1,
            ]);

            CourseSectionLang::insert([
                ['section_id' => $section->id, 'lang' => 'ar', 'title' => 'المحتوى', 'created_at' => now(), 'updated_at' => now()],
                ['section_id' => $section->id, 'lang' => 'en', 'title' => 'Content', 'created_at' => now(), 'updated_at' => now()],
            ]);

            return response()->json([
                'message' => 'Course created.',
                'course' => $course->load('langs', 'sections.langs'),
            ], 201);
        });
    }

    public function show(Request $request, $id)
    {
        $this->mustBeAdmin($request);

        $lang = $request->header('lang', 'en');

        $course = Course::with([
            'category:id,name',
            'instructor:id,name',
            'langs',
            'sections.langs',
            'items.langs',
            'items.media',
            'items.liveSession',
            'discounts',
            'activeDiscount'
        ])->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }

        return new CourseResource($course);
        // return response()->json($course);
    }

    public function update(Request $request, $id)
    {
        $this->mustBeAdmin($request);
        if ($request->has('langs')) {
            $langsPayload = $request->input('langs');

            // إذا جاي JSON string من FormData
            if (is_string($langsPayload)) {
                $langsPayload = json_decode($langsPayload, true);
            }

            if (is_array($langsPayload)) {
                $normalizedLangs = [];

                // mapping ثابت حسب نظامك
                $langMap = Language::query()
                    ->select('id', 'shortname')
                    ->get()
                    ->pluck('shortname', 'id') // [id => shortname]
                    ->toArray();


                foreach ($langMap as $langId => $langCode) {
                    // تجاهل اللغة الفارغة
                    if (
                        empty($langsPayload['title'][$langId]) &&
                        empty($langsPayload['excerpt'][$langId]) &&
                        empty($langsPayload['description'][$langId])
                    ) {
                        continue;
                    }

                    $normalizedLangs[] = [
                        'lang' => $langCode,
                        'title' => $langsPayload['title'][$langId] ?? '',
                        'excerpt' => $langsPayload['excerpt'][$langId] ?? null,
                        'description' => $langsPayload['description'][$langId] ?? null,
                        'outcomes_json' => $langsPayload['outcomes_json'][$langId] ?? [],
                        'requirements_json' => $langsPayload['requirements_json'][$langId] ?? [],
                    ];
                }

                $request->merge([
                    'langs' => $normalizedLangs,
                ]);
            }
        }

        $data = $request->validate([
            'category_id' => 'required|integer|exists:course_categories,id',
            'instructor_id' => 'nullable|integer|exists:users,id',
            'course_image' => 'nullable|string|max:2048',
            'course_duration_hours' => 'nullable|numeric|min:0',
            'certificate_image' => 'nullable|string|max:2048',
            'promo_video_url' => 'nullable|string|max:2048',
            'promo_video_duration_seconds' => 'nullable|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'is_free' => 'nullable|boolean',

            'discount_type' => 'nullable|in:percent,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_starts_at' => 'nullable|date',
            'discount_ends_at' => 'nullable|date|after_or_equal:discount_starts_at',

            'has_certificate' => 'nullable|boolean',
            'status' => 'nullable|in:draft,published',

            'langs' => 'nullable|array',
            'langs.*.lang' => 'required_with:langs|string|in:ar,en',
            'langs.*.title' => 'required_with:langs|string|max:255',
            'langs.*.excerpt' => 'nullable|string|max:255',
            'langs.*.description' => 'nullable|string',
            'langs.*.outcomes_json' => 'nullable|array',
            'langs.*.requirements_json' => 'nullable|array',
        ]);
        return DB::transaction(function () use ($data, $id) {
            $course = Course::findOrFail($id);

            $course->update($data);

            // published_at handling
            if (isset($data['status'])) {
                $published_at = null;
                if ($data['status'] === 'published' && ! $course->published_at) {
                    $published_at = now();
                }
                if ($data['status'] === 'draft') {
                    $published_at = null;
                }
                $course->update([
                    'published_at' => $published_at
                ]);
            }

            // Save course_langs (optional)
            if (! empty($data['langs'])) {
                foreach ($data['langs'] as $lng) {
                    CourseLang::updateOrCreate(
                        ['course_id' => $course->id, 'lang' => $lng['lang']],
                        [
                            'title' => $lng['title'],
                            'excerpt' => $lng['excerpt'] ?? null,
                            'description' => $lng['description'] ?? null,
                            'outcomes_json' => $lng['outcomes_json'] ?? null,
                            'requirements_json' => $lng['requirements_json'] ?? null,
                        ]
                    );
                }
            }

            // Create discount if provided
            if (!empty($data['discount_type']) && !empty($data['discount_value'])) {

                // عطّل أي خصم نشط سابق
                $course->discounts()
                    ->where('is_active', true)
                    ->update(['is_active' => false]);

                // أنشئ خصم جديد (هو الوحيد النشط)
                $course->discounts()->create([
                    'type' => $data['discount_type'],
                    'value' => $data['discount_value'],
                    'starts_at' => $data['discount_starts_at'] ?? null,
                    'ends_at' => $data['discount_ends_at'] ?? null,
                    'is_active' => true,
                ]);
            }

            return response()->json([
                'message' => 'Course updated.',
                'course' => $course,
            ]);
        });
    }

    public function destroy(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $course->delete();

        return response()->json(['message' => 'Course deleted.']);
    }

    public function publish(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $course->update(['status' => 'published', 'published_at' => now()]);

        return response()->json(['message' => 'Published.']);
    }

    public function unpublish(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $course->update(['status' => 'draft', 'published_at' => null]);

        return response()->json(['message' => 'Unpublished.']);
    }

    public function instructors(Request $request)
    {
        $tutors = User::where('type', 2)->orderBy('id', 'desc')->get()->map(function ($tutor) {
            return [
                'id' => $tutor->id,
                'name' => $tutor->full_name,
            ];
        });

        return response()->json([
            'data' => $tutors,
        ]);
    }
}
