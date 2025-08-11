<?php

namespace App\Http\Controllers\Constants;

class WorldTimezoneController extends ConstantController
{
    
    function __construct() {
        $auditInfo='WorldTimezone';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='world-timezones';
    }
    
}