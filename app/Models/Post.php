<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function langs()
    {
        $lang = Request::header('lang');

        $language = Language::where('shortname', $lang)->first();

        return $language ? 
                  $this->hasMany(PostLang::class,'post_id')->where('language_id', $language->id) 
                  : $this->hasMany(PostLang::class,'post_id');
    }
    
    public function imageInfo()
    {
       return $this->belongsTo(Media::class,'image');
    }
    
    public function medias()
    {
       return $this->hasMany(PostMedia::class,'post_id');
    }
    
    public function package()
    {
       return $this->hasMany(PostPackage::class,'post_id');
    }
    
    public function departments()
    {
       return $this->hasMany(PostDepartment::class,'post_id');
    }
    
    public function comments()
    {
       return $this->hasMany(PostComment::class,'post_id');
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class,'depid');
    }
    
    public function author()
    {
        return $this->belongsTo(User::class,'authorid');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
   
    public function getSelectedDatesAttribute($value)
    {
        if(is_string($value)) {
            return json_decode($value, true);
        }
        return $value;
    }

    public function setSelectedDatesAttribute($value)
    {
        if(is_array($value)) {
            $this->attributes['selected_dates'] = json_encode($value);
        } else {
            $this->attributes['selected_dates'] = $value;
        }
    }
}