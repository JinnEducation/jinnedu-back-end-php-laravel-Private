<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\{
    Course, CourseItem, CourseItemLang, CourseItemMedia
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseItemController extends Controller
{
    private function mustBeAdmin(Request $request)
    {
        if (($request->user()->type ?? 0) != 1) abort(403, 'Admin only.');
    }

    public function index(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $lang = $request->header('lang', 'en');

        $items = $course->items()
            ->with([
                'langs' => fn($q) => $q->where('lang', $lang),
                'media',
                'liveSession'
            ])
            ->orderBy('sort_order')
            ->get();

        return response()->json($items);
    }

    public function store(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'section_id' => 'required|integer|exists:course_sections,id',
            'type' => 'required|in:lesson_video,intro_zoom,intro_recording,workshop_zoom,workshop_recording',
            'is_free_preview' => 'nullable|boolean',
            'duration_seconds' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',

            'langs' => 'required|array|min:1',
            'langs.*.lang' => 'required|string|in:ar,en',
            'langs.*.title' => 'required|string|max:255',
            'langs.*.description' => 'nullable|string',

            // media (optional)
            'media' => 'nullable|array',
            'media.source_type' => 'required_with:media|in:upload,url',
            'media.media_url' => 'required_with:media|string|max:2048',
        ]);

        return DB::transaction(function () use ($course, $data) {

            $item = CourseItem::create([
                'course_id' => $course->id,
                'section_id' => $data['section_id'],
                'type' => $data['type'],
                'is_free_preview' => $data['is_free_preview'] ?? false,
                'duration_seconds' => $data['duration_seconds'] ?? null,
                'sort_order' => $data['sort_order'] ?? ($course->items()->max('sort_order') + 1),
            ]);

            foreach ($data['langs'] as $lng) {
                CourseItemLang::create([
                    'item_id' => $item->id,
                    'lang' => $lng['lang'],
                    'title' => $lng['title'],
                    'description' => $lng['description'] ?? null,
                ]);
            }

            if (!empty($data['media'])) {
                CourseItemMedia::create([
                    'item_id' => $item->id,
                    'source_type' => $data['media']['source_type'],
                    'media_url' => $data['media']['media_url'],
                ]);
            }

            return response()->json([
                'message' => 'Item created.',
                'item' => $item->load('langs','media','liveSession'),
            ], 201);
        });
    }

    public function update(Request $request, CourseItem $item)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'section_id' => 'nullable|integer|exists:course_sections,id',
            'type' => 'nullable|in:lesson_video,intro_zoom,intro_recording,workshop_zoom,workshop_recording',
            'is_free_preview' => 'nullable|boolean',
            'duration_seconds' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',

            'langs' => 'nullable|array',
            'langs.*.lang' => 'required_with:langs|string|in:ar,en',
            'langs.*.title' => 'required_with:langs|string|max:255',
            'langs.*.description' => 'nullable|string',

            // media (optional)
            'media' => 'nullable|array',
            'media.source_type' => 'required_with:media|in:upload,url',
            'media.media_url' => 'required_with:media|string|max:2048',
        ]);

        return DB::transaction(function () use ($item, $data) {

            $item->fill($data);
            $item->save();

            if (!empty($data['langs'])) {
                foreach ($data['langs'] as $lng) {
                    CourseItemLang::updateOrCreate(
                        ['item_id' => $item->id, 'lang' => $lng['lang']],
                        [
                            'title' => $lng['title'],
                            'description' => $lng['description'] ?? null,
                        ]
                    );
                }
            }

            if (array_key_exists('media', $data)) {
                // إذا جت media: نعمل upsert، وإذا كانت null نحذف
                if ($data['media'] === null) {
                    $item->media()->delete();
                } else {
                    $item->media()->updateOrCreate(
                        ['item_id' => $item->id],
                        [
                            'source_type' => $data['media']['source_type'],
                            'media_url' => $data['media']['media_url'],
                        ]
                    );
                }
            }

            return response()->json([
                'message' => 'Item updated.',
                'item' => $item->fresh()->load('langs','media','liveSession'),
            ]);
        });
    }

    public function destroy(Request $request, CourseItem $item)
    {
        $this->mustBeAdmin($request);

        $item->delete();
        return response()->json(['message' => 'Item deleted.']);
    }

    public function sort(Request $request, Course $course)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:course_items,id',
            'items.*.sort_order' => 'required|integer|min:0',
            'items.*.section_id' => 'nullable|integer|exists:course_sections,id',
        ]);

        foreach ($data['items'] as $row) {
            $update = ['sort_order' => $row['sort_order']];
            if (!empty($row['section_id'])) $update['section_id'] = $row['section_id'];

            $course->items()->where('id', $row['id'])->update($update);
        }

        return response()->json(['message' => 'Sorted.']);
    }
}
