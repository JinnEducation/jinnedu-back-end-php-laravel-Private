<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationInfo extends Model
{
    use HasFactory, SoftDeletes;

   protected $fillable = [
        'n_title',
        'n_details',
        'n_url',
        'n_icon',
        'n_color',
        'n_seen',
    ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
   
}
