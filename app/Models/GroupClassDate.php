<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupClassDate extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    /*public function outline()
    {
        return $this->belongsTo(GroupClassOutline::class,'gco_id');
    }*/
    
    public function groupClass()
    {
        return $this->belongsTo(GroupClass::class,'class_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

   
}
