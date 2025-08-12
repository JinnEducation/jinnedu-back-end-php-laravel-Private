<?php

namespace App\Http\Controllers\Constants;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\User;
use App\Models\ContactUs;
use App\Models\Translation;
use DB;
use Bouncer;
use Mail;
use App\Models\Setting;

class ContactUsController extends Controller
{
    public $modelName;
    public $modelNameLang;
    public $modelTitle;
    public $modelFK;
    public $modelLangTitle;
    
    function __construct() {
        $this->modelName=null;
        $this->modelNameLang=null;
        $this->modelTitle='';
        $this->modelFK='';
        $this->modelLangTitle='';
    }
    
    public function index(Request $request){
        $limit = setDataTablePerPageLimit($request->limit);
        $items = ContactUs::query();
        $items = $items->orderBy('id','desc')->paginate($limit);
        return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }
    
    public function show(Request $request,$id){
         $item = ContactUs::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 400);
        
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $item
        ] , 200);
    }
    
       
    public function store(Request $request){
        $name     =   $request->get('name');
        $f_name     =   $request->get('f_name');
        $l_name     =   $request->get('l_name');
        $email      =   $request->get('email');
        $mobile     =   $request->get('mobile');
        $message    =   $request->get('message');
        
        $validator = \Validator::make($request->all(), [
                'name'              => 'required_without_all:f_name,l_name|string',
                'f_name'        => 'required_without:name|string',
                'l_name'          => 'required_without:name|string',
                'email'				=>	'required',
                'mobile'				=>	'required',
                'message'				=>	'required',
            ], [
                'name.required_without_all'	=>'name-required',
                'f_name.required_without'	=>'first-name-required',
                'l_name.required_without'	=>	'last-name-required',
                'email.required'	=>	'eamil-required',
                'mobile.required'	=>	'mobile-required',
                'message.required'	=>	'message-required',
            ]
        );

        if ($validator->fails()) {
            $all = collect($validator->errors()->getMessages())->map(function($item){
            return $item[0];
            });
            $strs = [];
            foreach ($all as $value) {
            $strs[]=  $value;
            }
            return response()->json(['success' => false , 'message' => implode(',',$strs)], 400);
        }
        
        
        DB::beginTransaction();
        try {

            $name = trim($name);
            if(!empty($name)){
                $nameParts = explode(" ", $name);
                $f_name = $nameParts[0];
                $l_name = implode(" ", array_slice($nameParts, 1));
            }
          
            $contact = new ContactUs();
            $contact->f_name = $f_name;
            $contact->l_name = $l_name;
            $contact->email = $email;
            $contact->mobile = $mobile;
            $contact->message = $message;
            $saved = $contact->save();
            if(!$saved){
               return response()->json(['success' => false , 'message' => 'error-occurred-during-process','msg-code' => '111'], 400); 
            }
            
            $site_name = 'Jinnedu';
            $site_email = 'info@jinnedu.com';
            $data = array('site_email'=>$site_email,'site_name'=>$site_name,'contact'=>$contact);
             \Mail::send('emails.contact_us', $data, function($message) use ($site_name,$site_email,$f_name,$l_name,$email){
                $message->to($site_email,$site_name)->subject('Contact Us');
                $message->from($email,$f_name.' '.$l_name);
            });
            
        
          DB::commit();
          
         return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $contact
        ] , 200);
        

        } catch (\Exception $e) {
            DB::rollback();
            return $e;
            return response()->json(['success' => false , 'message' => 'error-occurred-during-process','msg-code' => '111'], 400); 
        }
        
       
    }
}