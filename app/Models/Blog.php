<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;


    protected $table = 'blog';
    protected $fillable = [
        'categ_blog_id',
        'title',
        'slug',
        'description',
        'image',
        'date',
        'status',
        'user_id'

    ];

    protected $append = ['image_url'];

    public function category()
    {
        return $this->belongsTo(CateqBlog::class, 'categ_blog_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    public function courses()
    {
        return $this->hasMany(OurCourse::class, 'blog_id');
    }

    public function getImageUrlAttribute() // $this->image_url
    {
        if (Str::startsWith($this->image, ['http', 'https'])) {
            return $this->image;
        }
        if (empty($this->image)) {
            return null;
        }
        return asset('storage/' . $this->image);
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published')
            ->whereDate('date', '<=', now());
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            'user_id' => null,
            'category_id' => null,
            'course_id' => null,
        ], $filters);

        $builder->when($options['user_id'], function ($builder, $value) {
            $builder->where('user_id', $value);
        });
        // $builder->when($options['category_id'], function($builder, $value) {
        //     $builder->where('category_id', $value);
        // });
        $builder->when($options['category_id'], fn($q, $v) => $q->where('categ_blog_id', $v));
        // $builder->when($options['course_id'], function($builder, $value) {
        //     $builder->where('course_id', $value);
        // });
        $builder->when($options['course_id'], function ($q, $v) {
            $q->whereHas('courses', fn($qq) => $qq->where('id', $v));
        });
    }
}
