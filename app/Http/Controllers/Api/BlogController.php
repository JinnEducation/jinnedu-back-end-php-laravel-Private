<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use App\Models\BlogLang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $type = $user->type;
        if($type == 0) {
            $blogs = Blog::filter($request->query())
                ->with('category', 'category.langs', 'users:id,name', 'langsAll.language')
                ->paginate(10);
        } 
        elseif($type == 2) {
            $blogs = Blog::filter($request->query())
                ->where('user_id', $user->id)
                ->with('category', 'category.langs', 'users:id,name', 'langsAll.language')
                ->paginate(10);
        }
        else {
            return response()->json(['message' => 'You are not a tutor to access this resource.'], 403);
        }
        // $blogs = Blog::filter($request->query())
        //     ->with('category', 'category.langs', 'users:id,name', 'langsAll.language')
        //     ->paginate(10);

        return BlogResource::collection($blogs);
    }

    public function store(Request $request)
    {
        $languages = $request->langs;
        DB::beginTransaction();
        try {
            $dataBlog = $request->validate([
                'categ_blog_id' => 'required|exists:categ_blog,id',
                'image' => 'required',
                'date' => 'required|date',
                'status' => 'required',
                'user_id' => 'required|exists:users,id',
            ]);

            $dataLang = $request->validate([
                'title.*' => 'required|string',
                'slug.*' => 'required|string|unique:blog_langs,slug',
                'description.*' => 'required|string',
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if (! $file->isValid()) {
                    return response()->json(['message' => 'Uploaded image is not valid.'], 422);
                }

                $imagePath = $file->store('uploads/blogs', 'public');
                $dataBlog['image'] = $imagePath;
            } else {
                $dataBlog['image'] = $request->image ?? null;
            }

            // create blog
            $blog = Blog::create($dataBlog);

            foreach ($languages as $language) {
                BlogLang::create([
                    'blog_id' => $blog->id,
                    'language_id' => $language['id'],
                    'title' => $request->title[$language['id']],
                    'slug' => $request->slug[$language['id']],
                    'description' => $request->description[$language['id']],
                ]);
            }

            $blog->load(['category', 'category.langs', 'users:id,name', 'courses:id,name,blog_id', 'langsAll.language']);
            DB::commit();

            return response()->json($blog);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {

        $blog = Blog::with(['category', 'category.langs', 'users:id,name', 'langsAll.language'])
            ->findOrFail($id);

        return new BlogResource($blog);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);
        $languages = $request->langs;
        DB::beginTransaction();
        try {
            $dataBlog = $request->validate([
                'categ_blog_id' => ['sometimes', 'required', 'integer', 'exists:categ_blog,id'],
                'image' => ['sometimes', 'nullable'],
                'date' => ['sometimes', 'required', 'date'],
                'status' => ['sometimes', 'required'],
                'user_id' => ['sometimes', 'required', 'integer', 'exists:users,id'],
            ]);

            $dataLang = $request->validate([
                'title.*' => ['sometimes', 'required'],
                'slug.*' => [
                    'sometimes',
                    'required',
                ],
                'description.*' => ['sometimes', 'required'],
            ]);

            // if ($request->hasFile('image')) {
            //     $dataBlog['image'] = $request->file('image')->store('uploads/blogs', 'public');
            // }

            if (! empty($dataBlog)) {
                $blog->update($dataBlog);
            }

            if (! empty($dataLang)) {
                foreach($languages as $language){
                    BlogLang::updateOrCreate([
                        'blog_id' => $blog->id,
                        'language_id' => $language['id'],
                    ], [
                        'title' => $request->title[$language['id']],
                        'slug' => $request->slug[$language['id']],
                        'description' => $request->description[$language['id']],
                    ]);
                }
            }

            $blog->load(['category', 'category.langs', 'users:id,name', 'courses:id,name,blog_id', 'langsAll.language']);
            DB::commit();

            return new BlogResource($blog);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        Blog::destroy($id);

        return response()->json([
            'message' => 'Blog deleted successfully',

        ], 204);
    }
}
