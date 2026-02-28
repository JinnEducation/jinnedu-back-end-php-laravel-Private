<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConferenceRecording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ConferenceRecordingController extends Controller
{
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

        $recording = ConferenceRecording::create($validated);

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

        $mediaUrl = $path;

        return response()->json([
            'message' => 'Video uploaded successfully.',
            'media_url' => $mediaUrl,
            'path' => $path,
        ]);
    }
}