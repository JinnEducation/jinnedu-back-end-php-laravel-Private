<?php

namespace App\Models;

use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Slider extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $append = ['image_url'];


    public function langs()
    {
        $lang = Request::header('lang');

        $language = Language::where('shortname', $lang)->first();

        return $language
            ? $this->hasMany(SliderLang::class, 'slider_id')->where('language_id', $language->id)
            : $this->hasMany(SliderLang::class, 'slider_id');
    }

    public function langsAll()
    {
        return $this->hasMany(SliderLang::class, 'slider_id');
    }

    public function getImageUrlAttribute() // $this->image_url
    {
        if (Str::startsWith($this->image, ['http', 'https'])) {
            return $this->image;
        }
        if (empty($this->image)) {
            return null;
        }
        return asset('storage/'.$this->image);
    }

}
