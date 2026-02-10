<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

   protected $fillable = ['name', 'value'];
    
   public static function valueOf(string $name, $default = null): mixed
    {
        
        return Cache::remember("setting:$name", 60, function () use ($name, $default) {
            $row = static::query()->where('name', $name)->first();
            return $row?->value ?? $default;
        });
    }
   
}
