<?php

namespace App\Http\Controllers\Constants;


class SortByTutorController extends ConstantController
{
    function __construct() {
        $auditInfo='SortByTutor';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='sort-by-tutors';
    }
    
}