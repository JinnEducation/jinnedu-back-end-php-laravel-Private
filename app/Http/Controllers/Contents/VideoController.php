<?php

namespace App\Http\Controllers\Contents;


class VideoController extends ContentController
{
    function __construct() {
        $auditInfo='Video';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='videos';
        $this->content_type=3;
    }
    
}