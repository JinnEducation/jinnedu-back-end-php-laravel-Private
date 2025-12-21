<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChatContact extends Model
{
    use SoftDeletes;

    protected $table = 'chat_contacts';

    protected $fillable = [
        'user_id',
        'contact_id',
        'last_msg',
        'last_msg_date',
        'status',
    ];

    protected $casts = [
        'last_msg_date' => 'datetime',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contact()
    {
        return $this->belongsTo(User::class, 'contact_id');
    }

    public function scopeForUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }
}
