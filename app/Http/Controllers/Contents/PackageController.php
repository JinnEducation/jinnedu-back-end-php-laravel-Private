<?php

namespace App\Http\Controllers\Contents;


class PackageController extends ContentController
{
    function __construct() {
        $auditInfo='Package';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='packages';
        $this->content_type=11;
    }
    
}