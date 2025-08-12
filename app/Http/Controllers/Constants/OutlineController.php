<?php

namespace App\Http\Controllers\Constants;

class OutlineController extends ConstantController
{
    function __construct() {
        $auditInfo='Outline';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='outlines';
    }
    
}