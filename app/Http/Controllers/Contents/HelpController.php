<?php

namespace App\Http\Controllers\Contents;


class HelpController extends ContentController
{
    function __construct() {
        $auditInfo='Help';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='helps';
        $this->content_type=10;
    }
    
}