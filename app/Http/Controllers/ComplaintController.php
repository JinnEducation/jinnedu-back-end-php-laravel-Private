<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\ConferenceComplaint;


use Bouncer;
use Mail;

class ComplaintController extends Controller
{
    
    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        $items = ConferenceComplaint::query()->with('student', 'tutor')->where('conference_id','<>',0);
        if(!empty($request->q)){
            $items->whereRaw(filterTextDB('subject').' like ?',['%'.filterText($request->q).'%']);
        }

        if(!empty($request->conference_id)){
            $items->where('conference_id', $request->conference_id);
        }

        if(!empty($request->student_id)){
            $items->where('student_id', $request->student_id);
        }

        if(!empty($request->tutor_id)){
            $items->where('tutor_id', $request->tutor_id);
        }

         $items = $items->paginate($limit);

         foreach ($items as $item) {
            if($item->conference->ref_type == 1){
                $item->conference_type = 'group class';
            }else if($item->conference->ref_type == 3){
                $item->conference_type = 'trial lesson';
            }else if($item->conference->ref_type == 4){
                $item->conference_type = 'private lesson';
            }else{
                $item->conference_type = 'unknown';
            }
         }
         
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }
    

    public function reply(Request $request)
    {
        $user = Auth::user();
        
        $conference_complaint = ConferenceComplaint::find($request->reply_id);
        if(!$conference_complaint) return response([
                'success' => false,
                'message' => 'item-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        if($conference_complaint->status == 2 || $conference_complaint->status == 3){
            return response([
               'success' => false,
               'message' => 'complaint-closed',
               'msg-code' => '444'
            ] , 200);
        }
        
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
        
        $data = [];
        
        $data['status'] = $request->status;
        $data['note'] = $request->note;
        $data['reply_id'] = $request->reply_id;
        $data['tutor_id'] = 0;
        $data['student_id'] = 0;
        $data['user_id'] = $user->id;
        $data['conference_id'] = 0;
        $data['ipaddress'] = $request->ip();
        
        if(!empty($request->file)) {
            $file = uploadMedia($request->file,['pdf','doc','docx','ppt','pptx','exl','exlx','jpg','jpeg','png','gif','svg','mp3','mp4'],'conferences-complaints');
            $data = array_merge($data, $file);
        }

        // update ConferenceComplaint status
        ConferenceComplaint::where('id', $request->reply_id)->update(['status' => $request->status]);
        
        $item = ConferenceComplaint::create($data);

        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item,
                'data' => $data
        ] , 200);
    }
    
    public function show($id)
    {
        $item = ConferenceComplaint::find($id);
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
        $item = ConferenceComplaint::find($id);
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
            
    private function addTutorTransferToHisWallet($tutor_id, $fee){

        $user = User::find($tutor_id);
        $wallet = $user->wallets()->first();
        if(!$wallet) {
            $wallet = new UserWallet;
            $wallet->user_id = $tutor_id;
            $wallet->balance = $fee;
            $wallet->save();
        } else {
            $wallet->balance += $fee;
            $wallet->save();
        }
        
    }
}