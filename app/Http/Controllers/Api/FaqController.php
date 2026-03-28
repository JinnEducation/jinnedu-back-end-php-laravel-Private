<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Models\FaqLang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $faqs = Faq::filter($request->query())
            ->with('langsAll.language')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(10);

        return FaqResource::collection($faqs);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $dataFaq = $request->validate([
                'status' => ['required', 'in:draft,published,archived'],
                'sort_order' => ['nullable', 'integer', 'min:0'],
            ]);

            $request->validate([
                'langs' => ['required', 'array', 'min:1'],
                'langs.*.id' => ['required', 'exists:languages,id'],
                'question' => ['required', 'array'],
                'question.*' => ['required', 'string'],
                'answer' => ['required', 'array'],
                'answer.*' => ['required', 'string'],
            ]);

            $faq = Faq::create($dataFaq);

            foreach ($request->langs as $language) {
                $languageId = (int) $language['id'];

                FaqLang::create([
                    'faq_id' => $faq->id,
                    'language_id' => $languageId,
                    'question' => $request->question[$languageId] ?? '',
                    'answer' => $request->answer[$languageId] ?? '',
                ]);
            }

            $faq->load('langsAll.language');
            DB::commit();

            return new FaqResource($faq);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $faq = Faq::with('langsAll.language')->findOrFail($id);

        return new FaqResource($faq);
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        DB::beginTransaction();
        try {
            $dataFaq = $request->validate([
                'status' => ['sometimes', 'required', 'in:draft,published,archived'],
                'sort_order' => ['sometimes', 'nullable', 'integer', 'min:0'],
            ]);

            $request->validate([
                'langs' => ['sometimes', 'array', 'min:1'],
                'langs.*.id' => ['required_with:langs', 'exists:languages,id'],
                'question' => ['sometimes', 'array'],
                'question.*' => ['sometimes', 'required', 'string'],
                'answer' => ['sometimes', 'array'],
                'answer.*' => ['sometimes', 'required', 'string'],
            ]);

            if (! empty($dataFaq)) {
                $faq->update($dataFaq);
            }

            if ($request->filled('langs')) {
                foreach ($request->langs as $language) {
                    $languageId = (int) $language['id'];

                    if (! array_key_exists($languageId, $request->question ?? []) || ! array_key_exists($languageId, $request->answer ?? [])) {
                        continue;
                    }

                    FaqLang::updateOrCreate([
                        'faq_id' => $faq->id,
                        'language_id' => $languageId,
                    ], [
                        'question' => $request->question[$languageId],
                        'answer' => $request->answer[$languageId],
                    ]);
                }
            }

            $faq->load('langsAll.language');
            DB::commit();

            return new FaqResource($faq);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        Faq::destroy($id);

        return response()->json([
            'message' => 'FAQ deleted successfully',
        ]);
    }
}
