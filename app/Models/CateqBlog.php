<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class CateqBlog extends Model
{
    use HasFactory;

     protected $table = 'categ_blog';

    protected $fillable = [
        'name','slug','sort_order','is_active','user_id'
    ];

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'categ_blog_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            'user_id' => null,
        ], $filters);

         $builder->when($options['user_id'], function($builder, $value) {
            $builder->where('user_id', $value);
        });
       
}
}
