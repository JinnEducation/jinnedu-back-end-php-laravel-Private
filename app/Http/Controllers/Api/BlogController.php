<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\BlogResource;

class BlogController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $blogs = Blog::filter($request->query())
            ->with('category:id,name', 'users:id,name', 'courses:id,name')
            ->paginate(10);

        return BlogResource::collection($blogs);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'categ_blog_id' => 'required',
            'title' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'image' => 'required',
            'date' => 'required',
            'status' => 'required',
            'user_id' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('uploads/blogs', 'public');
        } else {
            $imagePath = null;
        }
        $data['image'] = $imagePath;

        $blogs = Blog::create($data);
        return Response::json($blogs, 200);
    }



    public function show($id)
    {

        $blog = Blog::findOrFail($id);
        return new BlogResource($blog);
    }


    public function update(Request $request, $id)
    {

        $blog = Blog::findOrFail($id);

        $data = $request->validate([
            'categ_blog_id' => 'sometimes|required|integer|exists:categ_blog,id',
            'title'         => 'sometimes|required|string',
            'slug'          => 'sometimes|required|string',
            'description'   => 'sometimes|required|string',
            'image'         => 'sometimes|required',
            'date'          => 'sometimes|required|date',
            'status'        => 'sometimes|required|in:draft,published,archived',
            'user_id'       => 'sometimes|required|integer|exists:users,id',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('uploads/blogs', 'public');
        } else {
            $imagePath = $blog->image ?? null;
        }
        $data['image'] = $imagePath;

        $blog->update($data);


        return Response::json($blog);
    }


    public function destroy($id)
    {
        Blog::destroy($id);
        return response()->json([
            'message' => 'Blog deleted successfully',

        ], 204);
    }
}
