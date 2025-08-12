<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupClassTutor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function groupClass()
    {
        return $this->belongsTo(GroupClass::class,'group_class_id');
    }
    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class,'tutor_id');
    }
}
