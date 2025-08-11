<?php

namespace App\Http\Controllers\Sections;

class CourseController extends SectionController
{
    function __construct() {
        $auditInfo='Course';
        $auditInfoLang='CourseLang';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelNameLang=$appPrefix . '\\' . $auditInfoLang;
        $this->modelTitle='courses';
        $this->modelLangTitle='course_langs';
        $this->modelFK='course_id';
    }
    
}