<?php

namespace App\Http\Controllers\Api\Chat;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;
use Illuminate\Http\Request;

class ChatMessagesController extends Controller
{
    public function __construct(protected ChatService $chatService)
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request, int $id)
    {
        $authId = (int)$request->user()->id;
        // $perPage = (int)($request->query('per_page', 20));
        $perPage = 50;

        // id هنا = contactId
        $messages = $this->chatService->listMessages($authId, $id, $perPage);

        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'to_user' => ['required', 'integer', 'exists:users,id'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $fromId = (int)$request->user()->id;
        $toId = (int)$request->input('to_user');
        $message = (string)$request->input('message');

        $chat = $this->chatService->sendMessage(
            $fromId,
            $toId,
            $message,
            $request->ip()
        );

        return response()->json([
            'ok' => true,
            'data' => $chat,
        ]);
    }

    public function destroy(Request $request, int $id)
    {
        $authId = (int)$request->user()->id;

        $this->chatService->deleteMessage($authId, $id);

        return response()->json(['ok' => true]);
    }

    public function favorite(Request $request, int $id)
    {
        $authId = (int)$request->user()->id;

        $chat = $this->chatService->toggleFavorite($authId, $id);

        return response()->json(['ok' => true, 'data' => $chat]);
    }
}
