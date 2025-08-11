<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupClassStudent extends Model
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
    
    public function student()
    {
        return $this->belongsTo(Student::class,'student_id');
    }
    
    public function conference()
    {
        return $this->belongsTo(Conference::class,'conference_id');
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }

   
}
