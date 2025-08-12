<?php

namespace App\Http\Controllers\Contents;


class LinkController extends ContentController
{
    function __construct() {
        $auditInfo='Link';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='links';
        $this->content_type=7;
    }
    
}