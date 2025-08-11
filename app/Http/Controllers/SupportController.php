<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Support;


use Bouncer;
use Mail;

class SupportController extends Controller
{
    
    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        $items = Support::query();
        if(!empty($request->q)){
            $items->whereRaw(filterTextDB('subject').' like ?',['%'.filterText($request->q).'%']);
        }
        
         $items = $items->paginate($limit);
         
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }
    

    public function add(Request $request)
    {
        if(empty($request->email)) return response([
                'success' => false,
                'message' => 'email-is-empty',
                'msg-code' => '222'
        ] , 200);
        
        if(empty($request->name)) return response([
                'success' => false,
                'message' => 'name-is-empty',
                'msg-code' => '222'
        ] , 200);
        
        if(empty($request->country_id)) return response([
                'success' => false,
                'message' => 'country-is-empty',
                'msg-code' => '222'
        ] , 200);
        
        
        if(empty($request->subject)) return response([
                'success' => false,
                'message' => 'note-is-empty',
                'msg-code' => '333'
        ] , 200);
        
        if(empty($request->note)) return response([
                'success' => false,
                'message' => 'note-is-empty',
                'msg-code' => '333'
        ] , 200);
        
        $data = [];
        
        $data['subject'] = $request->subject;
        $data['note'] = $request->note;
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['country_id'] = $request->country_id;
        $data['ipaddress'] = $request->ip();
        $data['user_id'] = 0;
        
        if(!empty($request->file)) {
            $file = uploadMedia($request->file,['pdf','doc','docx','ppt','pptx','exl','exlx','jpg','jpeg','png','gif','svg','mp3','mp4'],'supports');
            $data = array_merge($data, $file);
        }
        
        $item = Support::create($data);
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item,
                'data' => $data
        ] , 200);
    }
    
    public function reply(Request $request)
    {
        $user = Auth::user();
        
        $conference = Support::find($request->reply_id);
        if(!$conference) return response([
                'success' => false,
                'message' => 'item-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        if(empty($request->status)) return response([
                'success' => false,
                'message' => 'status-is-empty',
                'msg-code' => '222'
        ] , 200);
        
        if(empty($request->note)) return response([
                'success' => false,
                'message' => 'note-is-empty',
                'msg-code' => '333'
        ] , 200);

        if(empty($request->country_id)) return response([
                'success' => false,
                'message' => 'note-is-empty',
                'msg-code' => '444'
        ] , 200);
        
        $data = [];
        
        $data['status'] = $request->status;
        $data['note'] = $request->note;
        $data['reply_id'] = $request->reply_id;
        $data['country_id'] = $request->country_id;
        $data['user_id'] = $user->id;
        $data['ipaddress'] = $request->ip();
        
        if(!empty($request->file)) {
            $file = uploadMedia($request->file,['pdf','doc','docx','ppt','pptx','exl','exlx','jpg','jpeg','png','gif','svg','mp3','mp4'],'supports');
            $data = array_merge($data, $file);
        }
        
        $item = Support::create($data);
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item,
                'data' => $data
        ] , 200);
    }
    
    public function show($id)
    {
        $item = Support::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
       $item->user;
       $item->replies = $item->replies()->get();
       
       foreach($item->replies as $reply){
           $reply->user;
           if($reply->user) $reply->user->email=null;
           
       }
        
        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ] , 200);
    }

    public function destroy($id)
    {
        $item = Support::find($id);
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