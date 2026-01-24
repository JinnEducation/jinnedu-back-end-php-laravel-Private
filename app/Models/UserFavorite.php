<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFavorite extends Model
{
    use HasFactory, SoftDeletes;
    
    //protected $table = 'users_abouts';


    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class,'ref_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class,'ref_id');
    }
   
    public function group_class()
    {
        return $this->belongsTo(GroupClass::class,'ref_id');
    }
}
