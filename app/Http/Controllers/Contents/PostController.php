<?php

namespace App\Http\Controllers\Contents;


class PostController extends ContentController
{
    function __construct() {
        $auditInfo='Post';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='posts';
        $this->content_type=1;
    }
    
}