<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserFavorite;

class UserFavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $data = $request->validate([
            'ref_id' => ['required', 'integer'],
            'type'   => ['required', 'in:1,3'],
        ]);

        $userId = auth()->id();

      
        $fav = UserFavorite::withTrashed()
            ->where('user_id', $userId)
            ->where('ref_id', $data['ref_id'])
            ->where('type', $data['type'])
            ->first();

   
        if ($fav && $fav->deleted_at === null) {
            $fav->delete();
            return response()->json(['status' => 'removed']);
        }

      
        if ($fav && $fav->deleted_at !== null) {
            $fav->restore();
            $fav->update(['ipaddress' => $request->ip()]);
            return response()->json(['status' => 'added']);
        }

      
        UserFavorite::create([
            'ref_id'    => $data['ref_id'],
            'user_id'   => $userId,
            'type'      => $data['type'],
            'ipaddress' => $request->ip(),
        ]);

        return response()->json(['status' => 'added']);
    }
}
