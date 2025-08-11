<?php

namespace App\Http\Controllers\Constants;

use App\Http\Controllers\Controller;
use App\Models\ForbiddenWords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForbiddenWordsController extends Controller
{
    //

    public function index(Request $request)
    {
        $forbidden_words = ForbiddenWords::query('forbidden_words.*');

        if(!empty($request->q)) {
            $forbidden_words->whereRaw(filterTextDB('forbidden_words.word') . ' like ?', ['%' . filterText($request->q) . '%']);
            $forbidden_words->distinct();
        }

        // $forbidden_words = $forbidden_words->paginate(10);
        $forbidden_words = paginate($forbidden_words, 10);

        return $forbidden_words;
    }

    public function store(Request $request)
    {
        $request->validate([
            'word' => 'string|required',
            'status' => 'required|boolean'
        ]);
        $forbidden_word = ForbiddenWords::create($request->all());

        return $forbidden_word;
    }


    public function show($id)
    {

        $forbidden_word = ForbiddenWords::find($id);

        return $forbidden_word;
    }

    public function update(Request $request, $id)
    {

        $forbidden_word = ForbiddenWords::find($id);
        $forbidden_word->update($request->all());

        return $forbidden_word;
    }

    public function delete($id)
    {

        $forbidden_word = ForbiddenWords::find($id);
        $forbidden_word->delete();

        return response()->json(
            'word deleted successfully',
            200
        );
    }
}
