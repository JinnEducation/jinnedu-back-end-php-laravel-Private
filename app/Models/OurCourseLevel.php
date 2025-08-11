<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurCourseLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    
    public function OurCourse()
    {
        return $this->belongsTo(OurCourse::class,'our_course_id');
    }
    
    public function level()
    {
        return $this->belongsTo(Level::class,'level_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
   
}