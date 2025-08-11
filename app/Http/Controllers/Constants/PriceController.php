<?php

namespace App\Http\Controllers\Constants;


class PriceController extends ConstantController
{
    function __construct() {
        $auditInfo='Price';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='prices';
    }
    
}