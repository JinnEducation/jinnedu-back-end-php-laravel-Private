<?php

namespace App\Http\Controllers\Contents;


class EventController extends ContentController
{
    function __construct() {
        $auditInfo='Event';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='events';
        $this->content_type=5;
    }
    
}