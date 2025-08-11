<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\UserFavorite;
use App\Models\Tutor;
use Bouncer;
use Mail;

class UserFavoriteController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $limit = setDataTablePerPageLimit($request->limit);
        
        $items = UserFavorite::where('user_id',$user->id)->where('type', $request->type)->paginate($limit);
        
        foreach($items as $item) {
                if($item->type == 1){
                            $tutor = Tutor::where('tutors.id', $item->ref_id)
                                ->join('user_abouts', 'user_abouts.user_id', '=', 'tutors.id')
                                ->leftJoin('user_hourly_prices', 'user_hourly_prices.user_id', '=', 'tutors.id')
                                ->select(
                                    'tutors.name', 
                                    'tutors.id', 
                                    \DB::raw("(select avg(stars) from tutor_reviews where tutor_reviews.tutor_id = tutors.id and deleted_at is NULL) as rating"), 
                                    'tutors.type', 
                                    'user_hourly_prices.price', 
                                    'tutors.avatar', 
                                    'tutors.online', 
                                    \DB::raw("CONCAT(user_abouts.first_name,' ',user_abouts.last_name) full_name")
                                )->first();
                                
                                $tutor->videos =  $tutor->videos()->get();
                                $tutor->descriptions =  $tutor->descriptions()->get();
                                $tutor->rating = $tutor->reviews()->avg('stars');
                                //======================================================
                                $tutor->abouts =  $tutor->abouts()->get();
                                if($tutor->abouts) {
                                        foreach($tutor->abouts as $about) {
                                                $about->country;
                                                $about->level;
                                                $about->language;
                                                $about->subject;
                                                $about->experience;
                                                $about->situation;
                                        }
                                }
                        $item->tutor = $tutor;
                                        
                }elseif($item->type == 2){
                        $item->tutor = null;
                        $item->group_class = null; 
                        $item->course; 
                }else{
                        $item->tutor = null;
                        $item->course = null;  
                        $item->group_class; 
                }
        }
        
        return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }

    public function store(Request $request)
    {
        $data = $request->only(['ref_id','type']);
        $user = Auth::user();
        $data['user_id'] = $user->id;
        
        $item = UserFavorite::create($data);
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ] , 200);
    }
    
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        $item = UserFavorite::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 400);
        
        if($item->user_id!=$user->id) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 400);
        
        $data = $request->only(['ref_id']);
        $data['user_id'] = $user->id;
       
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
        $item = UserFavorite::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 400);
        
        if($item->user_id!=$user->id) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 400);
        

        if($item->type == 1){
                $item->tutor;
                if($item->tutor) $item->tutor->email = null;
                $item->course = null;
        }else{
                $item->tutor = null;
                $item->course; 
        }
        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ] , 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $item = UserFavorite::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 400);
        
        if($item->user_id!=$user->id) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 400);
        
        $item->delete();
        
        
        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ] , 200);
    }

    public function remove(Request $request)
    {
        $user = Auth::user();
        $item = UserFavorite::where('ref_id', $request->ref_id)->where('type', $request->type)->first();
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 400);
        
        if($item->user_id!=$user->id) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 400);
        
        $item->delete();
        
        
        return response([
                'success' => true,
                'message' => 'item-deleted-successfully'
        ] , 200);
    }
}