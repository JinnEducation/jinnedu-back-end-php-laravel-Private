<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\BlogLang;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\CategBlogLang;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Facades\Response;

class BlogController extends Controller
{


    public function __construct()
    {
         $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $blogs = Blog::filter($request->query())
            ->with('category:id,name', 'users:id,name', 'courses:id,name', 'langs')
            ->paginate(10);

        return BlogResource::collection($blogs);
    }

    

    public function store(Request $request)
{
    
    $langShort = $request->header('lang');
    $language = $langShort
        ? Language::where('shortname', $langShort)->first()
        : null;

    $languageId = $language?->id ?? $request->input('language_id');

   
    $dataBlog = $request->validate([
        'categ_blog_id' => 'required|exists:categ_blog,id',
        'image' => 'required|file|image', 
        'date' => 'required|date',
        'status' => 'required',
        'user_id' => 'required|exists:users,id',
    ]);

   
    $dataLang = $request->validate([
        'title'       => 'required|string',
        'slug'        => 'required|string|unique:blog_langs,slug',
        'description' => 'required|string',
    ]);

  
    if ($request->hasFile('image')) {
        $file = $request->file('image');

        if (! $file->isValid()) {
            return response()->json(['message' => 'Uploaded image is not valid.'], 422);
        }

       
        $imagePath = $file->store('uploads/blogs', 'public'); 
        $dataBlog['image'] = $imagePath;
    }

    // create blog
    $blog = Blog::create($dataBlog);

     CategBlogLang::create([
        'blog_id'      => $blog->id,
        'language_id'  => $languageId,
        'title'        => $dataLang['title'],
        'slug'         => $dataLang['slug'],
        'description'  => $dataLang['description'],
    ]);

    $blog->load(['category:id,name', 'users:id,name', 'courses:id,name,blog_id', 'langs']);

    
    return response()->json($blog);
}



    public function show($id)
    {

        $blog = Blog::with(['category:id,name', 'users:id,name', 'courses:id,name,blog_id', 'langs'])
            ->findOrFail($id);

        return new BlogResource($blog);
    }


    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $langShort = $request->header('lang');
        $language = $langShort ? Language::where('shortname', $langShort)->first() : null;
        $languageId = $language?->id ?? $request->input('language_id');

        $dataBlog = $request->validate([
            'categ_blog_id' => ['sometimes', 'required', 'integer', 'exists:categ_blog,id'],
            'image'         => ['sometimes', 'nullable'],
            'date'          => ['sometimes', 'required', 'date'],
            'status'        => ['sometimes', 'required'],
            'user_id'       => ['sometimes', 'required', 'integer', 'exists:users,id'],
        ]);

        $dataLang = $request->validate([
            'title'       => ['sometimes', 'required'],
            'slug'        => [
                'sometimes',
                'required',
            ],
            'description' => ['sometimes', 'required'],
        ]);

        // if ($request->hasFile('image')) {
        //     $dataBlog['image'] = $request->file('image')->store('uploads/blogs', 'public');
        // }

        if (!empty($dataBlog)) {
            $blog->update($dataBlog);
        }


        if (!empty($dataLang)) {
            $langRow = BlogLang::firstOrNew([
                'blog_id'     => $blog->id,
                'language_id' => $languageId,
            ]);

            if (array_key_exists('title', $dataLang))       $langRow->title = $dataLang['title'];
            if (array_key_exists('slug', $dataLang))        $langRow->slug = $dataLang['slug'];
            if (array_key_exists('description', $dataLang)) $langRow->description = $dataLang['description'];

            $langRow->save();
        }

        $blog->load(['category:id,name', 'users:id,name', 'courses:id,name,blog_id', 'langs']);

        return new BlogResource($blog);
    }


    public function destroy($id)
    {
        Blog::destroy($id);
        return response()->json([
            'message' => 'Blog deleted successfully',

        ], 204);
    }
}
