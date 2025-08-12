<?php

namespace App\Http\Controllers\Constants;


class SubjectController extends ConstantController
{
    
    function __construct() {
        $auditInfo='Subject';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='subjects';
    }
    
}