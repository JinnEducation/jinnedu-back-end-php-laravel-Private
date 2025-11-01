<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderLang extends Model
{
    use HasFactory;
     protected $guarded = [];
    

    public function slider()
    {
        return $this->belongsTo(Slider::class, 'slider_id');
    }

    public function language()
{
    return $this->belongsTo(Language::class, 'language_id');
}
}
