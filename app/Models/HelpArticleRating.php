<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelpArticleRating extends Model
{
    use HasFactory;

    protected $table = 'help_article_ratings';

    protected $guarded = [];

    protected $casts = [
        'ratings_json' => 'array',
    ];

    public function article()
    {
        return $this->belongsTo(HelpArticle::class, 'help_article_id');
    }
}
