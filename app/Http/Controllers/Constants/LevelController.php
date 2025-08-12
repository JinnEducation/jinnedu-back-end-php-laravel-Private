<?php

namespace App\Http\Controllers\Constants;

class LevelController extends ConstantController
{
    function __construct() {
        $auditInfo='Level';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='levels';
    }
    
}