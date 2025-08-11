<?php

namespace App\Http\Controllers\Localizations;

use Illuminate\Http\Request;
use App\Http\Requests\Translation\TranslationRequest;

use App\Models\User;
use App\Models\Language;
use App\Models\Translation;
use App\Models\Label;

use Bouncer;
use Mail;


class TranslationController extends LocalizationController
{
    public function index(Request $request)
    {
        $limit = setDataTablePerPageLimit($request->limit);
        
        $items = Translation::query('translations.*');
         
        if(!empty($request->q)){
            $items->whereRaw(filterTextDB('translations.title').' like ?',['%'.filterText($request->q).'%']);
            $items->distinct();
        }
         
        //  $items = $items->paginate($limit);
         $items = paginate($items, $limit);
        
         foreach($items as $item){
            $item->language;
            $item->label;
         }
         
         return response([
                'success' => true,
                'message' => 'item-listed-successfully',
                'result' => $items
        ] , 200);
    }

    public function store(TranslationRequest $request)
    {
        $data = $request->only(['langid','labelid','title']);
        $item = Translation::create($data);
        return response([
                'success' => true,
                'message' => 'item-added-successfully',
                'result' => $item
        ] , 200);
    }

    public function update(TranslationRequest $request, $id)
    {
        $item = Translation::find($id);
        if(!$item) return response([
                'success' => false,
                'message' => 'item-dose-not-exist',
                'msg-code' => '111'
        ] , 200);
        
        $data = $request->only(['langid','labelid','title']);
        $item->update($data);
        return response([
                'success' => true,
                'message' => 'item-updated-successfully',
                'result' => $item
        ] , 200);
    }
    
    public function show($id)
    {
        $item = Translation::find($id);
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
        $item = Translation::find($id);
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