<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Course;
use App\Models\CourseLang;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CourseSection;
use App\Models\CourseSectionLang;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class CourseAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    private function mustBeAdmin(Request $request)
    {
        if (($request->user()->type ?? 0) != 1) {
            abort(403, 'Admin only.');
        }
    }

    public function index(Request $request)
    {
        $this->mustBeAdmin($request);

        $lang = $request->header('lang', 'en');

        $q = Course::query()
            ->with([
                'category:id,name,slug',
                'instructor:id,name',
                'langs' => fn($qq) => $qq->where('lang', $lang),
            ])
            ->latest();

        if ($request->filled('category_id')) $q->where('category_id', $request->category_id);
        if ($request->filled('status')) $q->where('status', $request->status);

        return response()->json($q->paginate(15));
    }

    public function store(Request $request)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'instructor_id' => 'nullable|integer|exists:users,id',
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

                'promo_video_url' => $data['promo_video_url'] ?? null,
                'promo_video_duration_seconds' => $data['promo_video_duration_seconds'] ?? null,

                'price' => $data['price'] ?? 0,
                'is_free' => $data['is_free'] ?? false,

                'discount_type' => $data['discount_type'] ?? null,
                'discount_value' => $data['discount_value'] ?? null,
                'discount_starts_at' => $data['discount_starts_at'] ?? null,
                'discount_ends_at' => $data['discount_ends_at'] ?? null,

                'has_certificate' => $data['has_certificate'] ?? false,
                'status' => $data['status'] ?? 'draft',
                'published_at' => ($data['status'] ?? 'draft') === 'published' ? now() : null,
            ]);

            // Save course_langs (optional)
            if (!empty($data['langs'])) {
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
                'course' => $course->load('langs','sections.langs'),
            ], 201);
        });
    }

    public function show(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $lang = $request->header('lang', 'en');

        $course->load([
            'category:id,name,slug',
            'instructor:id,name',
            'langs' => fn($q) => $q->where('lang', $lang),
            'sections.langs' => fn($q) => $q->where('lang', $lang),
            'items.langs' => fn($q) => $q->where('lang', $lang),
            'items.media',
            'items.liveSession',
            'discounts',
        ]);

        return response()->json($course);
    }

    public function update(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'category_id' => 'sometimes|integer|exists:categories,id',
            'instructor_id' => 'nullable|integer|exists:users,id',
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

        return DB::transaction(function () use ($course, $data) {

            $course->fill($data);

            // published_at handling
            if (isset($data['status'])) {
                if ($data['status'] === 'published' && !$course->published_at) {
                    $course->published_at = now();
                }
                if ($data['status'] === 'draft') {
                    $course->published_at = null;
                }
            }

            $course->save();

            // Save langs
            if (!empty($data['langs'])) {
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

            return response()->json([
                'message' => 'Course updated.',
                'course' => $course->fresh()->load('langs','sections.langs'),
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
}
