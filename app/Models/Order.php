<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class,'tutor_id');
    }
   
    public function conference()
    {
        return $this->hasOne(Conference::class, 'order_id');
    }
}
