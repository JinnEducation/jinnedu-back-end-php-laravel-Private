<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TutorTransfer extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class,'tutor_id');
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
   
}
