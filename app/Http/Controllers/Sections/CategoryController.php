<?php

namespace App\Http\Controllers\Sections;

class CategoryController extends SectionController
{
    function __construct() {
        $auditInfo='Category';
        $auditInfoLang='CategoryLang';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelNameLang=$appPrefix . '\\' . $auditInfoLang;
        $this->modelTitle='categories';
        $this->modelLangTitle='category_langs';
        $this->modelFK='category_id';
    }
    
}