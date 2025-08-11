<?php

namespace App\Http\Controllers\Contents;


class AdvertisementController extends ContentController
{
    function __construct() {
        $auditInfo='Advertisement';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='advertisements';
        $this->content_type=9;
    }
    
}