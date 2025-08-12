<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Setting\SettingRequest;

use App\Models\User;
use App\Models\Setting;

use Bouncer;
use Mail;


class SettingController extends Controller
{
    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        
        $items = Setting::query();
         
        if(!empty($request->q)){
            $items->whereRaw(filterTextDB('name').' like ?',['%'.filterText($request->q).'%']);
        }
         
         $items = $items->paginate($limit);
        
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }
    
    public function store(SettingRequest $request)
    {
        return $this->storeUpdateRequest($request);
    }
    
    public function update(SettingRequest $request, $id)
    {
        return $this->storeUpdateRequest($request, $id);
    }

    public function storeUpdateRequest($request, $id=0)
    {
        $data = ['name'=>$request->name,'type'=>$request->type,'value'=>$request->value,'options'=>$request->options,'icon'=>$request->icon,'class'=>$request->class_name,'color'=>$request->color];
        
        if(!empty($request->type) && $request->type=='file'){
            if(!empty($request->value)){
                $file = uploadFile($request->value,['jpg','jpeg','png','ico','gif'],'settings');
                $data['value'] = $file['path'];
            }
        }
        
        if($id>0){
            $item = Setting::find($id);
            if(!$item) return response([
                    'success' => false,
                    'message' => 'item-dose-not-exist',
                    'msg-code' => '111'
            ] , 200);
            $item->update($data);
        }else {
            $item = Setting::create($data);
        }
        
        
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ] , 200);
    }
    
    public function show($id)
    {
        $item = Setting::find($id);
        if(!$item) {
            $item = Setting::where('name',$id)->first();
            if(!$item) return response([
                            'success' => false,
                            'message' => 'item-dose-not-exist',
                            'msg-code' => '111'
                        ] , 200);
        }
        
        return response([
                'success' => true,
                'message' => 'item-showen-successfully',
                'result' => $item
        ] , 200);
    }

    public function destroy($id)
    {
        $item = Setting::find($id);
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