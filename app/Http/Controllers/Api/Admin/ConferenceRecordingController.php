<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\ConferenceAttendance;
use App\Models\ConferenceRecording;
use App\Models\GroupClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ConferenceRecordingController extends Controller
{
    /**
     * جلب كل اللقاءات (المواعيد) التي لها تسجيل مسجل على الأقل.
     * نفس شكل استجابة studentIndex: success, message, result مع إثراء كل عنصر.
     */
    public function indexConferences(Request $request)
    {
        $user = Auth::user();
        $limit = \setDataTablePerPageLimit($request->limit ?? 15);

        $items = Conference::query()
            ->whereHas('recordings')
            ->with(['recordings', 'tutor'])
            ->latest('id')
            ->paginate($limit);

        foreach ($items as $item) {
            $item->tutor;
            $item->rating = $item->reviews()->avg('stars');
            $item->is_available = strtotime($item->date . ' ' . $item->start_time) >= time();
            $item->attendance_status = $user
                ? ConferenceAttendance::where(['conference_id' => $item->id, 'user_id' => $user->id])->exists()
                : null;
            if ($item->ref_type == 1) {
                $item->group_class = GroupClass::select('id', 'name', 'slug')->where('id', $item->ref_id)->first();
            }
            $item->recordings = $item->recordings()->first();
        }

        return response([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => $items,
        ], 200);
    }

    public function indexByConference($conference_id)
    {
        $recordings = ConferenceRecording::query()
            ->where('conference_id', $conference_id)
            ->latest('id')
            ->get();

        return response()->json($recordings);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'conference_id' => ['required', 'integer', 'exists:conferences,id'],
            'source_type'   => ['required', 'string', Rule::in([ConferenceRecording::SOURCE_UPLOAD, ConferenceRecording::SOURCE_URL])],
            'media_url'     => ['required', 'string'],
        ]);

        
        $recording = ConferenceRecording::updateOrCreate([
            'conference_id' => $validated['conference_id'],
        ], [
            'source_type' => $validated['source_type'],
            'media_url' => $validated['media_url'],
        ]);

        return response()->json([
            'message' => 'Conference recording created successfully.',
            'data' => $recording,
        ], 201);
    }

    public function destroy($id)
    {
        $recording = ConferenceRecording::findOrFail($id);

        // (اختياري) لو بدك تحذف الملف من التخزين لما يكون upload
        // if ($recording->source_type === ConferenceRecording::SOURCE_UPLOAD) {
        //     $path = $recording->media_url;
        //     if ($path && Storage::disk('public')->exists($path)) {
        //         Storage::disk('public')->delete($path);
        //     }
        // }

        $recording->delete();

        return response()->json([
            'message' => 'Conference recording deleted successfully.',
        ]);
    }

    public function uploadVideo(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
           
        ]);

        $file = $request->file('file');

     
        $path = $file->store('recordings/conferences', 'public');

        $mediaUrl  = Storage::disk('public')->url($path);

        return response()->json([
            'message' => 'Video uploaded successfully.',
            'media_url' => $mediaUrl,
            'path' => $path,
        ]);
    }
}