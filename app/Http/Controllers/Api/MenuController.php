<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $perPage = (int) ($request->query('per_page', 10));

        $query = Menu::query();
        if ($q) {
            $query->where(function ($qb) use ($q) {
                $qb->where('title', 'like', "%$q%")
                   ->orWhere('name', 'like', "%$q%")
                   ->orWhere('route', 'like', "%$q%")
                   ->orWhere('slug', 'like', "%$q%");
            });
        }

        $menus = $query->paginate($perPage);
        return MenuResource::collection($menus); // returns data + meta + links
    }

    public function parents()
    {
        $parents = Menu::where(function ($q) {
            $q->whereNull('p_id')->orWhere('p_id', 0);
        })->orderBy('title')->get(['id','title']);

        // front accepts data OR raw; we return data for consistency
        return MenuResource::collection($parents);
    }

    public function store(MenuRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('svg')) {
            $data['svg'] = $request->file('svg')->store('menus', 'public');
        }

        $menu = Menu::create($data);
        return MenuResource::make($menu);
    }

    public function show($id)
    {
        $menu = Menu::find($id);
        if(!$menu) {
            return response()->json([
                'message' => 'Menu not found',
            ], 404);
        }
        return MenuResource::make($menu);
    }

    public function update(MenuRequest $request, $id)
    {
        $menu = Menu::find($id);
        if(!$menu) {
            return response()->json([
                'message' => 'Menu not found',
            ], 404);
        }
        $data = $request->validated();

        if ($request->hasFile('svg')) {
            if ($menu->svg) {
                Storage::disk('public')->delete($menu->svg);
            }
            $data['svg'] = $request->file('svg')->store('menus', 'public');
        }
        $menu->update([
            "p_id" => $data['p_id'],
            "title" => $data['title'],
            "name" => $data['name'],
            "route" => $data['route'],
            "slug" => $data['slug'],
            "type" => $data['type'],
            "active_routes" => $data['active_routes'],
            "status" => $data['status'],
            "invisible" => $data['invisible'],
            "sortable" => $data['sortable'],
        ]);
        return MenuResource::make($menu);
    }

    public function destroy($id)
    {
        $menu = Menu::find($id);
        if(!$menu) {
            return response()->json([
                'message' => 'Menu not found',
            ], 404);
        }
        $menu->delete(); // soft delete
        return response()->noContent();
    }
}

