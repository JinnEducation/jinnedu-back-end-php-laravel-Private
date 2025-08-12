<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAvailability extends Model
{
    use HasFactory, SoftDeletes;
    
    //protected $table = 'users_abouts';


    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function timezone()
    {
        return $this->belongsTo(WorldTimezone::class,'timezone_id');
    }
    
    public function day()
    {
        return $this->belongsTo(WeekDay::class,'day_id');
    }
   
}
