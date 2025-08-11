<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupClassReview extends Model
{
    use HasFactory, SoftDeletes;
    
    //protected $table = 'users_abouts';


    protected $guarded = [];
    
    public function User()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function groupClass()
    {
        return $this->belongsTo(GroupClass::class,'class_id');
    }
    

   
}
