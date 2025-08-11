<?php

namespace App\Http\Controllers\Constants;

class FrequencyController extends ConstantController
{
    function __construct() {
        $auditInfo='Frequency';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='frequencies';
    }
    
}