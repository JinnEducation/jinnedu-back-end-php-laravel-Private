<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Media;

use Bouncer;
use Mail;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $limit = setDataTablePerPageLimit($request->limit);
        
        $items = Media::paginate($limit);
         
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $data = [];
        if(!empty($request->attachment)) $data = uploadMedia($request->attachment,['pdf','doc','docx','ppt','pptx','exl','exlx','jpg','jpeg','png','gif','svg','mp3','mp4'],'medias');
        $data['user_id'] = $user->id;
        $data['language_id'] = $request->language_id;
        $data['ipaddress'] = $request->ip();
        $item = Media::create($data);
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ] , 200);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        $item = Media::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $data = [];
        if(!empty($request->attachment)) $data = uploadMedia($request->attachment,['pdf','doc','docx','ppt','pptx','exl','exlx','jpg','jpeg','png','gif','svg','mp3','mp4'],'medias');
        $data['user_id'] = $user->id;
        $data['ipaddress'] = $request->ip();
        $item->update($data);

        return response([
                'success' => true,
                'message' => 'item-updated-successfully',
                'result' => $item
        ] , 200);
    }
    
    public function show($id)
    {
        $user = Auth::user();
        $item = Media::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ] , 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $item = Media::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $item->delete();
        
        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ] , 200);
    }
    
}