<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\Review;
use App\Models\Conference;


use Bouncer;
use Mail;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // return response([
        //         'success' => false,
        //         'message' => 'item-dose-not-allow-listed',
        //         'msg-code' => '111'
        // ] , 400);
        
        $user = Auth::user();
        
        $limit = setDataTablePerPageLimit($request->limit);
        
         $items = Review::where('user_id',$user->id)->paginate($limit);
         
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }
    
     public function listByType(Request $request,$type,$ref_id)
    {
        $user = Auth::user();
        
        $limit = setDataTablePerPageLimit($request->limit);
        
        $items = Review::where('type',$type)->where('ref_id',$ref_id)->where('user_id',$user->id)->paginate($limit);
         
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }

    public function store(Request $request)
    {
        return $this->storeUpdateRequest($request);
    }
    
    public function update(Request $request, $id)
    {
        return $this->storeUpdateRequest($request, $id);
    }

    public function storeUpdateRequest($request, $id=0)
    {
        $data = $request->only(['comment','ref_id','type','stars']);
        $user = Auth::user();
        $data['user_id'] = $user->id;
        $data['ipaddress'] = $request->ip();
        
        $conference = Conference::where('id',$data['ref_id'])->whereRaw('(student_id=? and student_id<>0 and order_id<>0 and ref_type<>1) or (ref_id in (select class_id from group_class_students where student_id=?) and student_id=0 and order_id=0 and ref_type=1)',[$user->id,$user->id])->first();
        if(!$conference) return response([
                'success' => false,
                'message' => 'conference-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $review = Review::where('ref_id',$data['ref_id'])->where('user_id',$user->id)->first();
        
        if($review){
            $review = Review::where('ref_id',$data['ref_id'])->where('user_id',$user->id)->update($data);
        }else {
            $review = Review::create($data);
            
            // هنا الحجز
        }
        
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $review
        ] , 200);
    }
    
    public function show($id)
    {
        $user = Auth::user();
        $item = Review::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $item->user;
        if($item->user) $item->user->email=null;
        
        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ] , 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $item = Review::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        if($item->user_id!=$user->id) return response([
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
    
    public function getReviews(Request $request)
    {
                
        $limit = setDataTablePerPageLimit($request->limit);
        
        $items = Review::with(['user:id,name,avatar'])->paginate($limit);

        return response([
                'success' => true,
                'message' => 'items-listed-successfully',
                'result' => $items
        ] , 200);
    }
}