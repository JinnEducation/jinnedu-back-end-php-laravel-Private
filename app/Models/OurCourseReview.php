<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurCourseReview extends Model
{
    use HasFactory, SoftDeletes;
    
    //protected $table = 'users_abouts';


    protected $guarded = [];
    
    public function User()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function ourCourse()
    {
        return $this->belongsTo(OurCourse::class,'our_course_id');
    }
    

   
}