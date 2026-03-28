<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HelpArticleResource;
use App\Models\HelpArticle;
use App\Models\HelpArticleLang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HelpArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $articles = HelpArticle::filter($request->query())
            ->with(['langsAll.language', 'rating'])
            ->orderByDesc('id')
            ->paginate(10);

        return HelpArticleResource::collection($articles);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $dataArticle = $request->validate([
                'audience' => ['required', 'in:student,tutor'],
                'slug' => ['required', 'string', 'unique:help_articles,slug'],
                'icon' => ['nullable'],
                'icon_svg' => ['nullable', 'string'],
                'status' => ['required', 'in:draft,published,archived'],
            ]);

            $request->validate([
                'langs' => ['required', 'array', 'min:1'],
                'langs.*.id' => ['required', 'exists:languages,id'],
                'title' => ['required', 'array'],
                'title.*' => ['required', 'string'],
                'description' => ['required', 'array'],
                'description.*' => ['required', 'string'],
            ]);

            if ($request->hasFile('icon')) {
                $file = $request->file('icon');

                if (! $file->isValid()) {
                    return response()->json(['message' => 'Uploaded icon is not valid.'], 422);
                }

                $iconPath = $file->store('uploads/help_icons', 'public');
                $dataArticle['icon'] = $iconPath;
            } else {
                $dataArticle['icon'] = $request->icon ?? null;
            }

            $article = HelpArticle::create($dataArticle);

            foreach ($request->langs as $language) {
                $languageId = (int) $language['id'];

                HelpArticleLang::create([
                    'help_article_id' => $article->id,
                    'language_id' => $languageId,
                    'title' => $request->title[$languageId] ?? '',
                    'description' => $request->description[$languageId] ?? '',
                ]);
            }

            $article->load(['langsAll.language', 'rating']);
            DB::commit();

            return new HelpArticleResource($article);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $article = HelpArticle::with(['langsAll.language', 'rating'])->findOrFail($id);

        return new HelpArticleResource($article);
    }

    public function update(Request $request, $id)
    {
        $article = HelpArticle::findOrFail($id);

        DB::beginTransaction();
        try {
            $dataArticle = $request->validate([
                'audience' => ['sometimes', 'required', 'in:student,tutor'],
                'slug' => ['sometimes', 'required', 'string', 'unique:help_articles,slug,'.$article->id],
                'icon' => ['sometimes', 'nullable'],
                'icon_svg' => ['sometimes', 'nullable', 'string'],
                'status' => ['sometimes', 'required', 'in:draft,published,archived'],
            ]);

            $request->validate([
                'langs' => ['sometimes', 'array', 'min:1'],
                'langs.*.id' => ['required_with:langs', 'exists:languages,id'],
                'title' => ['sometimes', 'array'],
                'title.*' => ['sometimes', 'required', 'string'],
                'description' => ['sometimes', 'array'],
                'description.*' => ['sometimes', 'required', 'string'],
            ]);

            if ($request->hasFile('icon')) {
                $file = $request->file('icon');

                if (! $file->isValid()) {
                    return response()->json(['message' => 'Uploaded icon is not valid.'], 422);
                }

                $iconPath = $file->store('uploads/help_icons', 'public');
                $dataArticle['icon'] = $iconPath;
            } elseif (array_key_exists('icon', $dataArticle)) {
                $dataArticle['icon'] = $request->icon;
            }

            if (! empty($dataArticle)) {
                $article->update($dataArticle);
            }

            if ($request->filled('langs')) {
                foreach ($request->langs as $language) {
                    $languageId = (int) $language['id'];

                    if (
                        ! array_key_exists($languageId, $request->title ?? [])
                        || ! array_key_exists($languageId, $request->description ?? [])
                    ) {
                        continue;
                    }

                    HelpArticleLang::updateOrCreate([
                        'help_article_id' => $article->id,
                        'language_id' => $languageId,
                    ], [
                        'title' => $request->title[$languageId],
                        'description' => $request->description[$languageId],
                    ]);
                }
            }

            $article->load(['langsAll.language', 'rating']);
            DB::commit();

            return new HelpArticleResource($article);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        HelpArticle::destroy($id);

        return response()->json([
            'message' => 'Help article deleted successfully',
        ]);
    }
}
