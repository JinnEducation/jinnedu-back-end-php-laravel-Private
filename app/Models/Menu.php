<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Menu::class,'p_id');
    }

    public function childrens()
    {
        return $this->hasMany(Menu::class,'p_id');
    }
    
    public function childes()
    {
        return $this->hasMany(Menu::class,'p_id');
    }

    public function scopeParents($query)
    {
        return $query->where('p_id', 0);
    }
}
