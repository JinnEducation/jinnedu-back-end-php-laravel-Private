<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

class Department extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function langs()
    {
        $lang = Request::header('lang');

        $language = Language::where('shortname', $lang)->first();

        return $language ? 
               $this->hasMany(DepartmentLang::class,'department_id')->where('language_id', $language->id) 
               : $this->hasMany(DepartmentLang::class,'department_id');
    }

    public function childrens()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function imageInfo()
    {
        return $this->belongsTo(Media::class, 'image');
    }

    public function iconInfo()
    {
        return $this->belongsTo(Media::class, 'icon');
    }

    public function bannerInfo()
    {
        return $this->belongsTo(Media::class, 'banner');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
