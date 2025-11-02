<?php

namespace App\Http\Controllers\Api;

use App\Models\Language;
use App\Models\CateqBlog;
use Illuminate\Http\Request;
use App\Models\CategBlogLang;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\CategBlogResource;

class CateqBlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {

        $cateqs = CateqBlog::filter($request->query())
            ->with('users:id,name', 'langs')
            ->paginate();
        return Response::json($cateqs);
    }

    public function store(Request $request)
    {

        $langShort = $request->header('lang');
        $language = $langShort
            ? Language::where('shortname', $langShort)->first()
            : null;

        $languageId = $language?->id ?? $request->input('language_id');

      $dataCategBlog =  $request->validate([
            'sort_order' => 'required',
            'is_active' => 'required',
            'user_id' => 'required',
        ]);

         $dataCategBlogLang = $request->validate([
        'name'       => 'required|string',
        'slug'        => 'required|string|unique:categ_blog_langs,slug',
       
    ]);

    // create blog
    $categblog = CateqBlog::create($dataCategBlog);

    CategBlogLang::create([
        'categ_blog_id'      => $categblog->id,
        'language_id'  => $languageId,
        'name'        => $dataCategBlogLang['name'],
        'slug'         => $dataCategBlogLang['slug'],
    ]);

    $categblog->load(['users:id,name','blog_id', 'langs']);

    
    return response()->json( $categblog);

        
    }



    public function show($id)
    {

        $cateq = CateqBlog::findOrFail($id);
        return Response::json($cateq);
    }


    public function update(Request $request, $id)
    {

        $categblog = CateqBlog::findOrFail($id);

        $langShort = $request->header('lang');
        $language = $langShort ? Language::where('shortname', $langShort)->first() : null;
        $languageId = $language?->id ?? $request->input('language_id');

        $dataCategBlog = $request->validate([
            'sort_order'  => ['sometimes', 'required'],
            'is_active'  => ['sometimes', 'required'],
            'user_id'       => ['sometimes', 'required', 'integer', 'exists:users,id'],
        ]);

        $dataCategBlogLang = $request->validate([
            'name'       => ['sometimes', 'required'],
            'slug'        => ['sometimes', 'required'],
           
        ]);


        if (!empty($dataCategBlog)) {
            $categblog->update($dataCategBlog);
        }


        if (!empty($dataCategBlogLang)) {
            $langRow = CategBlogLang::firstOrNew([
                'categ_blog_id'     => $categblog->id,
                'language_id' => $languageId,
            ]);

            if (array_key_exists('name', $dataCategBlogLang))       $langRow->title = $dataCategBlogLang['title'];
            if (array_key_exists('slug',$dataCategBlogLang))        $langRow->slug = $dataCategBlogLang['slug'];
           

            $langRow->save();
        }

       $categblog->load(['users:id,name', 'blogs', 'langs']);

        return new CategBlogResource($categblog);
    }


    public function destroy($id)
    {
        CateqBlog::destroy($id);
        return response()->json([
            'message' => 'Cateqory Blog deleted successfully',

        ], 204);
    }
}
