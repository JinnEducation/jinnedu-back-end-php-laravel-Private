<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Controllers\Localizations\LabelController;

use App\Models\Label;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function addLabelAndTranslations($request){
        //dd($request);
        $request->name = str_replace(' ','-',strtolower($request->name));
        $request->file = str_replace(' ','-',strtolower($request->file));
        $lable = Label::where('name',$request->name)->where('file',$request->file)->first();
        $item=null;
        
        $cont = new LabelController;

        if($lable) $item = $cont->storeUpdateRequest($request,$lable->id);
        else $item = $cont->storeUpdateRequest($request);
        
        return $item;
    }
}
