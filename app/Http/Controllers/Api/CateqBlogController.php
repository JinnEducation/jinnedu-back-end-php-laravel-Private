<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategBlogResource;
use App\Models\CateqBlog;
use App\Models\CategBlogLang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CateqBlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $cateqs = CateqBlog::filter($request->query())
            ->with('users:id,name', 'langsAll.language')
            ->paginate(10);

        return CategBlogResource::collection($cateqs);
    }

    public function store(Request $request)
    {
        $languages = $request->langs;
        DB::beginTransaction();
        try {
            $dataCateqBlog = $request->validate([
                'is_active' => 'required',
                'sort_order' => 'required',
                'user_id' => 'required|exists:users,id',
            ]);

            $dataLang = $request->validate([
                'name.*' => 'required|string',
                'slug.*' => 'required|string|unique:categ_blog_langs,slug',
            ]);

            // create categblog
            $categblog = CateqBlog::create($dataCateqBlog);

            foreach ($languages as $language) {
                CategBlogLang::create([
                    'language_id' => $language['id'],
                    'categ_blog_id'      => $categblog->id,
                    'name'        => $dataLang['name'][$language['id']],
                    'slug'         => $dataLang['slug'][$language['id']],
                ]);
            }

            $categblog->load(['users:id,name', 'langsAll.language']);
            DB::commit();

            return response()->json($categblog);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $categblog = CateqBlog::with(['users:id,name', 'langsAll.language'])
            ->findOrFail($id);

        return new CategBlogResource($categblog);
    }

    public function update(Request $request, $id)
    {
        $categblog = CateqBlog::findOrFail($id);
        $languages = $request->langs;
        DB::beginTransaction();
        try {
            $dataCateqBlog = $request->validate([
                'is_active' => ['sometimes', 'required'],
                'sort_order' => ['sometimes', 'required'],
                'user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            ]);

            $dataLang = $request->validate([
                'name.*' => ['sometimes', 'required'],
                'slug.*' => [
                    'sometimes',
                    'required',
                ],
            ]);

            if (! empty($dataCateqBlog)) {
                $categblog->update($dataCateqBlog);
            }

            if (! empty($dataLang)) {
                foreach($languages as $language){
                    CategBlogLang::updateOrCreate([
                        'categ_blog_id' => $categblog->id,
                        'language_id' => $language['id'],
                    ], [
                        'name' => $dataLang['name'][$language['id']],
                        'slug' => $dataLang['slug'][$language['id']],
                    ]);
                }
            }

            $categblog->load(['users:id,name', 'langsAll.language']);
            DB::commit();

            return new CategBlogResource($categblog);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        CateqBlog::destroy($id);

        return response()->json([
            'message' => 'CateqBlog deleted successfully',

        ], 204);
    }
}
