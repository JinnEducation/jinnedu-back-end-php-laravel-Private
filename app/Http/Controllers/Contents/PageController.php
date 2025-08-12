<?php

namespace App\Http\Controllers\Contents;
use Illuminate\Http\Request;
use App\Models\Post;

class PageController extends ContentController
{
    function __construct() {
        $auditInfo='Page';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='pages';
        $this->content_type=2;
    }

    public function showPage(Request $request,$slug){
        $page = Post::with(['langs', 'medias', 'imageInfo'])->where('slug',$slug)->first();
        if(!$page){
            return response()->json(['success' => false , 'message' => 'item-dose-not-exist'],400);
        }
        return response()->json(['success' => true, 'message' => '','result'=>$page],200);
    }
    
}