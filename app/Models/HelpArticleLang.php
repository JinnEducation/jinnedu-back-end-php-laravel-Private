<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpArticleLang extends Model
{
    use HasFactory;

    protected $table = 'help_article_langs';

    protected $guarded = [];

    public function helpArticle()
    {
        return $this->belongsTo(HelpArticle::class, 'help_article_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }
}
