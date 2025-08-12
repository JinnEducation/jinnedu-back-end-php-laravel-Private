<?php

namespace App\Http\Controllers\Constants;

class SpecializationController extends ConstantController
{
    function __construct() {
        $auditInfo='Specialization';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='specializations';
    }
    
}