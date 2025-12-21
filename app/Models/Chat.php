<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
class Chat extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

     protected $casts = [
        'status' => 'boolean',
        'fav' => 'boolean',
        'seen' => 'boolean',
        'seen_date' => 'datetime',
    ];
    
     public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user');
    }

    public function scopeBetweenUsers(Builder $q, int $a, int $b): Builder
    {
        return $q->where(function ($qq) use ($a, $b) {
            $qq->where('from_user', $a)->where('to_user', $b);
        })->orWhere(function ($qq) use ($a, $b) {
            $qq->where('from_user', $b)->where('to_user', $a);
        });
    }

    public function scopeUnseenFor(Builder $q, int $userId, ?int $fromId = null): Builder
    {
        $q->where('to_user', $userId)->where('seen', 0);
        if ($fromId) {
            $q->where('from_user', $fromId);
        }
        return $q;
    }
   
}