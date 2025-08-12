<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function langs()
    {
        $lang = Request::header('lang');

        $language = Language::where('shortname', $lang)->first();

        return $language ? 
                  $this->hasMany(CourseLang::class,'course_id')->where('language_id', $language->id) 
                  : $this->hasMany(CourseLang::class,'course_id');
    }
    
    public function childrens()
    {
        return $this->hasMany(Course::class,'parent_id');
    }
    
    public function imageInfo()
    {
       return $this->belongsTo(Media::class,'image');
    }
    
    public function iconInfo()
    {
       return $this->belongsTo(Media::class,'icon');
    }
    
    public function bannerInfo()
    {
       return $this->belongsTo(Media::class,'banner');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
   
}