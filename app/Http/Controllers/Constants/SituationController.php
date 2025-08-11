<?php

namespace App\Http\Controllers\Constants;


class SituationController extends ConstantController
{
    
    function __construct() {
        $auditInfo='Situation';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='situations';
    }
    
}