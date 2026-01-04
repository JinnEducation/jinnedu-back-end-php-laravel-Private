<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    private function mustBeAdmin(Request $request)
    {
        if (($request->user()->type ?? 0) != 0) abort(403, 'Admin only.');
    }

    public function video(Request $request)
    {
        $this->mustBeAdmin($request);

        $data = $request->validate([
            'file' => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-matroska,video/webm,video/ogg', // 500MB
        ]);

        $path = $data['file']->store('courses/videos', 'public');
        $url  = Storage::disk('public')->url($path);

        return response()->json([
            'message' => 'Uploaded.',
            'media_url' => $url,
            'path' => $path,
        ], 201);
    }
    public function delete(Request $request)
    {
        $this->mustBeAdmin($request);
    
        $data = $request->validate([
            'path' => 'required|string',
        ]);
    
        if (!str_starts_with($data['path'], 'courses/videos/')) {
            abort(403);
        }
    
        if (Storage::disk('public')->exists($data['path'])) {
            Storage::disk('public')->delete($data['path']);
        }
    
        return response()->json([
            'message' => 'Video deleted.',
        ]);
    }
    

}
