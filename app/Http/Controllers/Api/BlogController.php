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
            ->paginate();
            

             return Response::json($blogs);
            // return BlogResource::collection($blogs);
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
        'image'         => 'sometimes|required|string',
        'date'          => 'sometimes|required|date',
        'status'        => 'sometimes|required|in:draft,published,archived',
        'user_id'       => 'sometimes|required|integer|exists:users,id',
    ]);

    $blog->update($data);
        // $blog->update($request->all());

         return Response::json($blog);
      
        
    }


    public function destroy($id)
    {
        Blog::destroy($id);
        return response()->json ([
                     'message' => 'Blog deleted successfully',
                     
                 ], 204);
    }

}
