<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
     public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user', 'id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user', 'id');
    }
   
}