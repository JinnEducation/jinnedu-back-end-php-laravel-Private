<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;
use Illuminate\Http\Request;

class ChatStatusController extends Controller
{
    public function __construct(protected ChatService $chatService)
    {
        $this->middleware('auth:sanctum');
    }

    public function seen(Request $request, int $id)
    {
        $authId = (int) $request->user()->id;

        $count = $this->chatService->markAsSeen($authId, $id);

        return response()->json(['ok' => true, 'seen_count' => $count]);
    }

    public function typing(Request $request, int $id)
    {
        $request->validate([
            'status' => ['required', 'boolean'],
        ]);

        $authId = (int) $request->user()->id;
        $status = (bool) $request->boolean('status');

        $this->chatService->setTyping($authId, $id, $status);

        return response()->json(['ok' => true]);
    }

    public function online(Request $request)
    {
        $request->user()->update([
            'online' => $request->status ? 1 : 0,
            'last_seen_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
