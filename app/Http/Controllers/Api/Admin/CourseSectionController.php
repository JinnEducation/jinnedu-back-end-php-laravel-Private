<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\CourseSectionResource;
use Illuminate\Http\Request;
use App\Models\CourseSectionLang;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\{Course, CourseSection};

class CourseSectionController extends Controller
{
    private function mustBeAdmin(Request $request)
    {
        if (($request->user()->type ?? 0) != 0) abort(403, 'Admin only.');
    }

    public function index(Request $request, $id)
    {
        $this->mustBeAdmin($request);
        $course = Course::find($id);

        if(!$course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }
        $lang = $request->header('lang', 'en');

        $sections = $course->sections()
            ->with(['langs' => fn($q) => $q->where('lang', $lang)])
            ->orderBy('sort_order')
            ->get();

        return CourseSectionResource::collection($sections);
    }

    public function store(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'sort_order' => 'nullable|integer|min:0',
            'langs' => 'required|array|min:1',
            'langs.*.lang' => 'required|string|in:ar,en',
            'langs.*.title' => 'required|string|max:255',
        ]);

        return DB::transaction(function () use ($course, $data) {
            $section = CourseSection::create([
                'course_id' => $course->id,
                'sort_order' => $data['sort_order'] ?? ($course->sections()->max('sort_order') + 1),
            ]);

            foreach ($data['langs'] as $lng) {
                CourseSectionLang::create([
                    'section_id' => $section->id,
                    'lang' => $lng['lang'],
                    'title' => $lng['title'],
                ]);
            }

            return response()->json([
                'message' => 'Section created.',
                'section' => $section->load('langs'),
            ], 201);
        });
    }

    public function update(Request $request, CourseSection $section)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'sort_order' => 'nullable|integer|min:0',
            'langs' => 'nullable|array',
            'langs.*.lang' => 'required_with:langs|string|in:ar,en',
            'langs.*.title' => 'required_with:langs|string|max:255',
        ]);

        return DB::transaction(function () use ($section, $data) {
            if (isset($data['sort_order'])) $section->sort_order = $data['sort_order'];
            $section->save();

            if (!empty($data['langs'])) {
                foreach ($data['langs'] as $lng) {
                    CourseSectionLang::updateOrCreate(
                        ['section_id' => $section->id, 'lang' => $lng['lang']],
                        ['title' => $lng['title']]
                    );
                }
            }

            return response()->json([
                'message' => 'Section updated.',
                'section' => $section->fresh()->load('langs'),
            ]);
        });
    }

    public function destroy(Request $request, CourseSection $section)
    {
        $this->mustBeAdmin($request);

        $section->delete();
        return response()->json(['message' => 'Section deleted.']);
    }

    public function sort(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:course_sections,id',
            'items.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($data['items'] as $row) {
            $course->sections()->where('id', $row['id'])->update(['sort_order' => $row['sort_order']]);
        }

        return response()->json(['message' => 'Sorted.']);
    }
}
