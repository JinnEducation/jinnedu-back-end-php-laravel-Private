<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class HelpArticle extends Model
{
    use HasFactory;

    protected $table = 'help_articles';

    protected $fillable = [
        'audience',
        'slug',
        'icon',
        'icon_svg',
        'status',
    ];

    protected $appends = ['icon_url', 'average_rating', 'ratings_count'];

    public function langs()
    {
        $lang = Request::header('lang');

        $language = Language::where('shortname', $lang)->first();

        return $language
            ? $this->hasMany(HelpArticleLang::class, 'help_article_id')->where('language_id', $language->id)
            : $this->hasMany(HelpArticleLang::class, 'help_article_id');
    }

    public function langsAll()
    {
        return $this->hasMany(HelpArticleLang::class, 'help_article_id');
    }

    public function rating()
    {
        return $this->hasOne(HelpArticleRating::class, 'help_article_id');
    }

    public function getIconUrlAttribute()
    {
        if (Str::startsWith((string) $this->icon, ['http', 'https'])) {
            return $this->icon;
        }

        if (empty($this->icon)) {
            return asset('front/assets/imgs/logo.png');
        }

        return asset('storage/'.$this->icon);
    }

    public function getAverageRatingAttribute()
    {
        return (float) ($this->rating?->average_rating ?? 0);
    }

    public function getRatingsCountAttribute()
    {
        return (int) ($this->rating?->ratings_count ?? 0);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFilter(Builder $builder, array $filters): void
    {
        $options = array_merge([
            'audience' => null,
            'status' => null,
            'q' => null,
        ], $filters);

        $builder->when($options['audience'], fn (Builder $q, $v) => $q->where('audience', $v));
        $builder->when($options['status'], fn (Builder $q, $v) => $q->where('status', $v));

        $builder->when($options['q'], function (Builder $q, $value) {
            $q->whereHas('langsAll', function (Builder $langQ) use ($value) {
                $langQ->where('title', 'like', '%'.$value.'%');
            });
        });
    }
}
