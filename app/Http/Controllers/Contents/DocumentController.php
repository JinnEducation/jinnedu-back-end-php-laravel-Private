<?php

namespace App\Http\Controllers\Contents;


class DocumentController extends ContentController
{
    function __construct() {
        $auditInfo='Document';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='documents';
        $this->content_type=4;
    }
    
}