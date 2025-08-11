<?php

namespace App\Http\Controllers\Contents;


class ImageController extends ContentController
{
    function __construct() {
        $auditInfo='Image';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='images';
        $this->content_type=6;
    }
    
}