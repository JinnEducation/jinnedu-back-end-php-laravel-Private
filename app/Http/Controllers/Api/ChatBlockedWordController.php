<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatBlockedWordResource;
use App\Models\ChatBlockedWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatBlockedWordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $chatBlockedWords = ChatBlockedWord::filter($request->query())
            ->paginate(10);

        return ChatBlockedWordResource::collection($chatBlockedWords);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $dataChatBlockedWord = $request->validate([
                'word' => 'required',
                'is_active' => 'required',
            ]);

            $chatBlockedWord = ChatBlockedWord::create($dataChatBlockedWord);
            DB::commit();

            return new ChatBlockedWordResource($chatBlockedWord);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $chatBlockedWord = ChatBlockedWord::findOrFail($id);

        return new ChatBlockedWordResource($chatBlockedWord);
    }

    public function update(Request $request, $id)
    {
        $chatBlockedWord = ChatBlockedWord::findOrFail($id);
        try {
            $dataChatBlockedWord = $request->validate([
                'word' => 'required',
                'is_active' => 'required',
            ]);

            $chatBlockedWord->update($dataChatBlockedWord);
            DB::commit();

            return new ChatBlockedWordResource($chatBlockedWord);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        ChatBlockedWord::destroy($id);

        return response()->json([
            'message' => 'ChatBlockedWord deleted successfully',
        ], 204);
    }

    public function checkWord(Request $request)
    {
        $word = $request->input('word');
        $chatBlockedWord = ChatBlockedWord::where('word', $word)->first();
        if ($chatBlockedWord) {
            return response()->json([
                'exists' => true,
            ]);
        }
        else {
            return response()->json([
                'exists' => false,
            ]);
        }
    }
}
