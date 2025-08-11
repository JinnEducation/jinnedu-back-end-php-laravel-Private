<?php

namespace App\Http\Controllers\Sections;

class DepartmentController extends SectionController
{
    function __construct() {
        $auditInfo='Department';
        $auditInfoLang='DepartmentLang';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelNameLang=$appPrefix . '\\' . $auditInfoLang;
        $this->modelTitle='departments';
        $this->modelLangTitle='department_langs';
        $this->modelFK='department_id';
    } 
    
}