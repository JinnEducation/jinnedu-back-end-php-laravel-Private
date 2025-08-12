<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\ParentInvitation;


use Bouncer;
use Mail;

use DateTime;

class ParentInvitationController extends Controller
{
    
    
    public function parentInvitations(Request $request)
    {
        $user = Auth::user();
        
        $limit = setDataTablePerPageLimit($request->limit);
        $limit = 100;

        $items = ParentInvitation::query()->where('parent_id',$user->id);

        $items->orderBy('id','desc');
        
        //dd($items->toSql());
        $items=$items->paginate($limit);
        foreach($items as $item){
            $item->childInfo;
            
            $start_date = new DateTime($item->created_at);
            $since_start = $start_date->diff(new DateTime()); //date('Y-m-d H:i:s')
            
            $item->since_start=$since_start;
        }
        
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }
    
    public function childInvitations(Request $request)
    {
        $user = Auth::user();
        
        $limit = setDataTablePerPageLimit($request->limit);
        //$limit = 100;

        $items = ParentInvitation::query()->where('child_id',$user->id);

        $items->orderBy('id','desc');
        
        //dd($items->toSql());
        $items=$items->paginate($limit);
        foreach($items as $item){
            $item->parentInfo;
            
            $start_date = new DateTime($item->created_at);
            $since_start = $start_date->diff(new DateTime()); //date('Y-m-d H:i:s')
            
            $item->since_start=$since_start;
        }
        
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }

    public function sendInvitation($id)
    {
        $user = Auth::user();
        $data['parent_id'] = $user->id;
        $data['child_id'] = $id;
        
        $item = ParentInvitation::create($data);
        
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ] , 200);
    }
    
    public function acceptInvitation($id)
    {
        $user = Auth::user();
        
        $item = ParentInvitation::where('id',$id)->where('child_id',$user->id)->where('status','<>',1)->first();
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);

        
         ParentInvitation::where('id',$id)->where('child_id',$user->id)->where('status','<>',1)->update(["status"=>1]);
         return response([
            'success' => true,
            'message' => 'item-accepted-successfully',
            'result' => $item
        ] , 200);
        
        
    }
    
    public function rejectInvitation($id)
    {
        $user = Auth::user();
        
        $item = ParentInvitation::where('id',$id)->where('child_id',$user->id)->where('status','<>',2)->first();
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);

        
        ParentInvitation::where('id',$id)->where('child_id',$user->id)->where('status','<>',2)->update(["status"=>2]);
        return response([
            'success' => true,
            'message' => 'item-accepted-successfully',
            'result' => $item
        ] , 200);
        
        
    }
    
    public function removeInvitation($id)
    {
        $user = Auth::user();
        $item = ParentInvitation::find($id);
        
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        if($item->parent_id!=$user->id) return response([
                'success' => false,
                'message' => 'item-dose-not-delete',
                'msg-code' => '111'
        ] , 200);
        
        $item->delete();
        
        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ] , 200);
    }
    
}