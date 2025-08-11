<?php

namespace App\Http\Controllers\Sections;

class NavigationController extends SectionController
{
    function __construct() {
        $auditInfo='Navigation';
        $auditInfoLang='NavigationLang';
        $appPrefix = 'App\\Models';
        $this->modelName=$appPrefix . '\\' . $auditInfo;
        $this->modelNameLang=$appPrefix . '\\' . $auditInfoLang;
        $this->modelTitle='navigations';
        $this->modelLangTitle='navigation_langs';
        $this->modelFK='navigation_id';
    }
    
}