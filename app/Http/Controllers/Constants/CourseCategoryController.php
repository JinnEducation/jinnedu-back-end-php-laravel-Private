<?php

namespace App\Http\Controllers\Constants;

class CourseCategoryController extends ConstantController
{
    function __construct() {
        $auditInfo='CourseCategory';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelTitle='course_categories';
    }
}