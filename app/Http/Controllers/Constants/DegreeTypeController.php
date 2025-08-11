<?php

namespace App\Http\Controllers\Constants;

class DegreeTypeController extends ConstantController
{
    function __construct() {
        $auditInfo='DegreeType';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='degree-types';
    }
    
}