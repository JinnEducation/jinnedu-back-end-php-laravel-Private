<?php

namespace App\Http\Controllers\Api;

use App\Models\CateqBlog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CateqBlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {

        $cateqs = CateqBlog::filter($request->query())
            ->with('users:id,name')
            ->paginate();
        return Response::json($cateqs);
       
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'sort_order' => 'required',
            'is_active' => 'required',
            'user_id' => 'required',
        ]);

        $cateqs = CateqBlog::create($request->all());
        return Response::json($cateqs, 200);
    }



    public function show($id)
    {
        
        $cateq = CateqBlog::findOrFail($id);
       return Response::json($cateq);
    }


    public function update(Request $request, $id)
    {

        $cateqs = CateqBlog::findOrFail($id);

        $data = $request->validate([

            'name' => 'sometimes|required',
            'slug' => 'sometimes|required',
            'sort_order' => 'sometimes|required',
            'is_active' => 'sometimes|required',
            'user_id'       => 'sometimes|required|integer|exists:users,id',
        ]);

       $cateqs->update($data);
        return Response::json($cateqs);
    }


    public function destroy($id)
    {
        CateqBlog::destroy($id);
        return response()->json([
            'message' => 'Cateqory Blog deleted successfully',

        ], 204);
    }
}
