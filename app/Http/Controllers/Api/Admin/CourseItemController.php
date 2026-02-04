<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ZoomController;
use App\Http\Resources\CourseItemResource;
use App\Models\Course;
use App\Models\CourseItem;
use App\Models\CourseItemLang;
use App\Models\CourseItemMedia;
use App\Models\CourseLiveSession;
use App\Models\CourseSection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseItemController extends Controller
{
    private function mustBeAdmin(Request $request)
    {
        if (($request->user()->type ?? 0) != 0) {
            abort(403, 'Admin only.');
        }
    }

    public function index(Request $request, $id)
    {
        $this->mustBeAdmin($request);

        $lang = $request->header('lang', 'en');

        $course = Course::findOrFail($id);
        $items = $course->items()
            ->with([
                'langs',
                'media',
                'liveSession',
            ])
            ->orderBy('sort_order')
            ->get();


        return CourseItemResource::collection($items);
    }

    public function store(Request $request, $id)
    {
        $this->mustBeAdmin($request);

        $course = Course::findOrFail($id);
        if (! $course) {
            return response()->json(['message' => 'Course not found.'], 404);
        }
        $type = $request->type;
        if ($type == CourseItem::TYPE_INTRO_ZOOM || $type == CourseItem::TYPE_INTRO_RECORDING) {
            $section = CourseSection::where('course_id', $course->id)->first();
            if (! $section) {
                return response()->json(['message' => 'Section not found.'], 404);
            }
            $request->merge(['section_id' => $section->id]);
        }

        $data = $request->validate([
            'section_id' => 'required|integer|exists:course_sections,id',
            'type' => 'required|in:lesson_video,intro_zoom,intro_recording,workshop_zoom,workshop_recording,intro',
            'is_free_preview' => 'nullable|boolean',
            'duration_seconds' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',

            'langs' => 'required|array|min:1',
            'langs.*.lang' => 'required|string|in:ar,en',
            'langs.*.title' => 'required|string|max:255',
            'langs.*.description' => 'nullable|string',

            'zoom_start_at' => 'nullable|date',
            'instructor_id' => 'nullable|integer|exists:users,id',

            'external_video_url' => 'nullable|url',
            'content_source' => 'nullable|in:zoom,upload,url',
        ]);

        return DB::transaction(function () use ($course, $data) {
            if ($data['type'] == CourseItem::TYPE_INTRO_ZOOM || $data['type'] == CourseItem::TYPE_INTRO_RECORDING) {
                $item = CourseItem::where('course_id', $course->id)->where('type', $data['type'])->first();
                if ($item) {
                    $item->delete();
                }
            }
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

            if ($data['type'] == CourseItem::TYPE_INTRO_ZOOM || $data['type'] == CourseItem::TYPE_WORKSHOP_ZOOM) {
                $zoom_start_at = Carbon::parse(
                    $data['zoom_start_at'],
                    'Asia/Gaza'
                )->format('Y-m-d H:i:s');

                $zoom_end_at = Carbon::parse(
                    $data['zoom_start_at'],
                    'Asia/Gaza'
                )->addMinutes(60)
                    ->format('Y-m-d H:i:s');

                $date = Carbon::parse($data['zoom_start_at'])->format('Y-m-d');
                $postValues = [
                    'title' => $data['langs'][0]['title'],
                    'timezone' => 35,
                    'start_time' => $zoom_start_at,
                    'end_time' => $zoom_end_at,
                    'date' => $date,
                    'record' => 3,
                ];

                $zoom = new ZoomController;
                $result = $zoom->createMeeting($postValues);

                $liveSession = CourseLiveSession::create([
                    'item_id' => $item->id,
                    'instructor_id' => $data['instructor_id'],
                    'start_at' => $zoom_start_at,
                    'end_at' => $zoom_end_at,
                    'zoom_meeting_id' => $result['data']['id'] ?? null,
                    'join_url_host' => $result['data']['start_url'] ?? null,
                    'join_url_attendee' => $result['data']['join_url'] ?? null,
                    'recording_item_id' => null,
                ]);
            }
            if ($data['type'] == CourseItem::TYPE_INTRO_RECORDING || $data['type'] == CourseItem::TYPE_WORKSHOP_RECORDING || $data['type'] == CourseItem::TYPE_LESSON_VIDEO) {
                $media = CourseItemMedia::create([
                    'item_id' => $item->id,
                    'source_type' => $data['content_source'],
                    'media_url' => $data['external_video_url'],
                ]);
            }

            return response()->json([
                'message' => 'Item created.',
                'item' => $item->load('langs', 'media', 'liveSession'),
            ], 201);
        });
    }

    public function update(Request $request, $id)
    {
        $this->mustBeAdmin($request);
        $item = CourseItem::findOrFail($id);
        $type = $request->type;
        $request->merge(['section_id' => $item->section_id]);

        $data = $request->validate([
            'section_id' => 'required|integer|exists:course_sections,id',
            'type' => 'required|in:lesson_video,intro_zoom,intro_recording,workshop_zoom,workshop_recording,intro',
            'is_free_preview' => 'nullable|boolean',
            'duration_seconds' => 'nullable|integer|min:0',
            'sort_order' => 'nullable|integer|min:0',

            'langs' => 'required|array|min:1',
            'langs.*.lang' => 'required|string|in:ar,en',
            'langs.*.title' => 'required|string|max:255',
            'langs.*.description' => 'nullable|string',

            'zoom_start_at' => 'nullable|date',
            'instructor_id' => 'nullable|integer|exists:users,id',

            'external_video_url' => 'nullable|url',
            'content_source' => 'nullable|in:zoom,upload,url',
        ]);

        return DB::transaction(function () use ($item, $data) {

            foreach ($data['langs'] as $lng) {
                CourseItemLang::updateOrCreate(
                    ['item_id' => $item->id, 'lang' => $lng['lang']],
                    [
                        'title' => $lng['title'],
                        'description' => $lng['description'] ?? null,
                    ]
                );
            }

            if ($data['type'] == CourseItem::TYPE_INTRO_ZOOM || $data['type'] == CourseItem::TYPE_WORKSHOP_ZOOM) {
                if (Carbon::parse($item->liveSession()?->first()?->start_at)->toDateTimeString() != Carbon::parse($data['zoom_start_at'])->toDateTimeString()) {

                    $item->liveSession()->delete();

                    $zoom_start_at = Carbon::parse(
                        $data['zoom_start_at'],
                        'Asia/Gaza'
                    )->format('Y-m-d H:i:s');

                    $zoom_end_at = Carbon::parse(
                        $data['zoom_start_at'],
                        'Asia/Gaza'
                    )->addMinutes(60)
                        ->format('Y-m-d H:i:s');
                    $date = Carbon::parse($data['zoom_start_at'])->format('Y-m-d');
                    $postValues = [
                        'title' => $data['langs'][0]['title'],
                        'timezone' => 35,
                        'start_time' => $zoom_start_at,
                        'end_time' => $zoom_end_at,
                        'date' => $date,
                        'record' => 3,
                    ];

                    $zoom = new ZoomController;
                    $result = $zoom->createMeeting($postValues);

                    $liveSession = CourseLiveSession::create([
                        'item_id' => $item->id,
                        'instructor_id' => $data['instructor_id'],
                        'start_at' => $zoom_start_at,
                        'end_at' => $zoom_end_at,
                        'zoom_meeting_id' => $result['data']['id'] ?? null,
                        'join_url_host' => $result['data']['start_url'] ?? null,
                        'join_url_attendee' => $result['data']['join_url'] ?? null,
                        'recording_item_id' => null,
                    ]);
                }
            }
            if ($data['type'] == CourseItem::TYPE_INTRO_RECORDING || $data['type'] == CourseItem::TYPE_WORKSHOP_RECORDING || $data['type'] == CourseItem::TYPE_LESSON_VIDEO) {
                CourseItemMedia::updateOrCreate([
                    'item_id' => $item->id,
                ], [
                    'source_type' => $data['content_source'],
                    'media_url' => $data['external_video_url'],
                ]);
            }

            $item->update($data);

            return response()->json([
                'message' => 'Item created.',
                'item' => $item->load('langs', 'media', 'liveSession'),
            ], 201);
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

        $ids = $request->ids;

        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:course_items,id',
        ]);
        $items = CourseItem::whereIn('id', $ids)->get();

        foreach ($items as $row) {
            $update = ['sort_order' => $row['sort_order']];
            if (! empty($row['section_id'])) {
                $update['section_id'] = $row['section_id'];
            }

            $course->items()->where('id', $row['id'])->update($update);
        }

        return response()->json(['message' => 'Sorted.']);
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'instructor_id' => 'required|exists:users,id',
            'start_at' => 'required|date',
        ]);

        $zoom_start_at = Carbon::parse($request->start_at)->format('Y-m-d H:i:s');

        $tutor = User::find($request->instructor_id);
        $orderController = new OrderController;
        $checkAllowBooking = $orderController->checkAllowBooking($tutor, null, $zoom_start_at, 40);
        if (! $checkAllowBooking['success']) {
            // return response($checkAllowBooking, 200);
            $available = false;
        } else {
            $available = true;
        }

        return response()->json([
            'available' => $available,
        ]);
    }
}
