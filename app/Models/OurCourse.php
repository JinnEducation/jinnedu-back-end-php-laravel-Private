<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Request;
use Cviebrock\EloquentSluggable\Sluggable;
class OurCourse extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $guarded = [];
    protected $appends = ['is_favourite', 'images_media'];
    
    protected $casts = [
        'images' => 'array',
    ];
    
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    
    public function langs()
    {
        $lang = Request::header('lang');

        $language = Language::where('shortname', $lang)->first();

        return $language ? 
                $this->hasMany(OurCourseLang::class,'our_course_id')->where('language_id', $language->id) 
                : $this->hasMany(OurCourseLang::class,'our_course_id');
    }
    
    public function reviews()
    {
        return $this->hasMany(OurCourseReview::class,'course_id');
    }
    
    public function imageInfo()
    {
       return $this->belongsTo(Media::class,'image');
    }
    
    public function levels()
    {
       return $this->hasMany(OurCourseLevel::class,'our_course_id');
    }
    
    public function tutors()
    {
       return $this->hasMany(OurCourseTutor::class,'our_course_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
        
    public function getIsFavouriteAttribute(){
        $authorization = \Request::header('Authorization');
        $authorization = substr($authorization,7);
        $token = PersonalAccessToken::findToken($authorization);
        if($token){
            $favourite = UserFavorite::where('ref_id',$this->id)->where('type',2)->where('user_id',$token->tokenable_id)->first();
            if($favourite){
                return $favourite->id;
            }
            return 0;
        }
        return 0;
    }

    public function attachment()
    {
       return $this->belongsTo(Media::class,'attachment');
    }

    public function loadImageInfo($languageId = null)
    {
        if (empty($this->images)) {
            $this->imageInfo;
            return;
        }

        $query = Media::whereIn('id', $this->images);

        if (!empty($languageId)) {
            $query->where('language_id', $languageId);
        }

        $this->image_info = $query->first();
    }
    
    public function getImagesMediaAttribute()
    {
        if (empty($this->images) || !is_array($this->images)) {
            return null;
        }
    
        return Media::whereIn('id', $this->images)->get();
    }


}