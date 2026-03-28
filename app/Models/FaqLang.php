<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqLang extends Model
{
    use HasFactory;

    protected $table = 'faq_langs';

    protected $guarded = [];

    public function faq()
    {
        return $this->belongsTo(Faq::class, 'faq_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
