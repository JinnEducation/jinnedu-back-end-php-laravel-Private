<?php

namespace App\Http\Controllers\Constants;


class WeekDayController extends ConstantController
{
    function __construct() {
        $auditInfo='WeekDay';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='week-days';
    }
    
}