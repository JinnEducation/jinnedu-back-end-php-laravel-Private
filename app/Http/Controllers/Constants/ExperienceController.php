<?php

namespace App\Http\Controllers\Constants;


class ExperienceController extends ConstantController
{
    
    function __construct() {
        $auditInfo='Experience';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='experiences';
    }
    
}