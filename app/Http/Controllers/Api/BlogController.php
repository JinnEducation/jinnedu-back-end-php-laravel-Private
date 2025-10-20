<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\BlogResource;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::filter($request->query())
        ->with('category:id,name', 'users:id,name', 'courses:id,name') 
            ->paginate();
            
            return BlogResource::collection($blogs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categ_blog_id' => 'required',
            'title' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'image' => 'required',
            'date' => 'required',
            'status' => 'required',
             'user_id' => 'required',
        ]);

        $blogs = Blog::create($request->all());
        return Response::json($blogs, 200);
    }



    public function show(Blog $blog)
    {
        
       return  $blog->load([
        'category:id,name',
        'users:id,name',            
        'courses:id,name,blog_id',
    ]);

            //  return new BlogResource($blog);
    }


     public function update(Request $request, Blog $blogs)
    {
       $request->validate([
            'categ_blog_id' => 'required',
            'title' => 'required',
            'slug' => 'required',
            'description' => 'required',
            'image' => 'required',
            'date' => 'required',
            'status' => 'required',
            'published_at' => 'required',
        ]);



        $blogs->update($request->all());


        return Response::json($blogs);
    }


    public function destroy($id)
    {
        Blog::destroy($id);
        return response()->json ([
                     'message' => 'Blog deleted successfully',
                     
                 ], 204);
    }
}
