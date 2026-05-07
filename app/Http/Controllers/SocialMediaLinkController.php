<?php

namespace App\Http\Controllers;

use App\Models\SocialMediaLink;
use Illuminate\Http\Request;

class SocialMediaLinkController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => SocialMediaLink::ordered()->get(),
        ]);
    }

    public function frontIndex()
    {
        return response()->json([
            'success' => true,
            'message' => 'item-listed-successfully',
            'result' => SocialMediaLink::activeOrdered()->get(),
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $data = $request->validate([
            'links' => ['required', 'array'],
            'links.*.id' => ['required', 'integer', 'exists:social_media_links,id'],
            'links.*.url' => ['nullable', 'string', 'max:2048'],
        ]);

        foreach ($data['links'] as $link) {
            SocialMediaLink::where('id', $link['id'])->update([
                'url' => $link['url'] ?? null,
                'is_active' => ! empty($link['url']),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'item-updated-successfully',
            'result' => SocialMediaLink::ordered()->get(),
        ]);
    }
}
