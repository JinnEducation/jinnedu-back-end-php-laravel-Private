<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentInvitation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public function parentInfo()
    {
        return $this->belongsTo(User::class,'parent_id');
    }
    
    public function childInfo()
    {
        return $this->belongsTo(User::class,'child_id');
    }
   
}
