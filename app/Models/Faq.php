<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faq';

    protected $fillable = [
        'status',
        'sort_order',
    ];

    public function langs()
    {
        $lang = Request::header('lang');

        $language = Language::where('shortname', $lang)->first();

        return $language
            ? $this->hasMany(FaqLang::class, 'faq_id')->where('language_id', $language->id)
            : $this->hasMany(FaqLang::class, 'faq_id');
    }

    public function langsAll()
    {
        return $this->hasMany(FaqLang::class, 'faq_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFilter(Builder $builder, array $filters): void
    {
        $options = array_merge([
            'status' => null,
            'q' => null,
        ], $filters);

        $builder->when($options['status'], fn (Builder $q, $v) => $q->where('status', $v));

        $builder->when($options['q'], function (Builder $q, $v) {
            $q->whereHas('langsAll', function (Builder $langQ) use ($v) {
                $langQ->where('question', 'like', '%'.$v.'%');
            });
        });
    }
}
