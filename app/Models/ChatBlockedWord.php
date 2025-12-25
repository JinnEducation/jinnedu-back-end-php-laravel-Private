<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ChatBlockedWord extends Model
{
    use HasFactory;
    protected $table = 'chat_blocked_words';
    protected $fillable = ['word', 'is_active'];


    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            'word' => null,
            'is_active' => null,
        ], $filters);

        $builder->when($options['word'], function ($builder, $value) {
            $builder->where('word', 'like', '%' . $value . '%');
        });

        $builder->when($options['is_active'], function ($builder, $value) {
            $builder->where('is_active', $value);
        });
    }
}
