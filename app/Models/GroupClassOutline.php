<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupClassOutline extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    
    public function groupClass()
    {
        return $this->belongsTo(GroupClass::class,'class_id');
    }
    
    public function outline()
    {
        return $this->belongsTo(Outline::class,'outline_id');
    }
    
    public function dates()
    {
        return $this->hasMany(GroupClassDate::class,'gco_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
   
}
