<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Constants\CurrencyController;
use Laravel\Sanctum\PersonalAccessToken;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;
class GroupClass extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $guarded = [];
    protected $appends = ['is_favourite', 'is_ordered', 'is_out_dated'];

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
                $this->hasMany(GroupClassLang::class,'classid')->where('language_id', $language->id) 
                : $this->hasMany(GroupClassLang::class,'classid');
    }

    public function langsAll()
    {
        return $this->hasMany(GroupClassLang::class, 'classid');
    }
    
    public function reviews()
    {
        return $this->hasMany(GroupClassReview::class,'class_id');
    }
    
    public function imageInfo()
    {
       return $this->belongsTo(Media::class,'image');
    }
    
    public function outlines()
    {
       return $this->hasMany(GroupClassOutline::class,'class_id');
    }
    
    public function dates()
    {
       return $this->hasMany(GroupClassDate::class,'class_id');
    }
    
    public function level()
    {
        return $this->belongsTo(Level::class,'level_id');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    
    public function frequency()
    {
        return $this->belongsTo(Frequency::class,'frequency_id');
    }
    
    public function tutor()
    {
        return $this->belongsTo(Tutor::class,'tutor_id');
    }
    
    public function students()
    {
        return $this->hasMany(GroupClassStudent::class,'class_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function attachment()
    {
       return $this->belongsTo(Media::class,'attachment');
    }

    public function appliedTutors()
    {
       return $this->hasMany(GroupClassTutor::class,'group_class_id');
    }

    public function getPriceAttribute($price){

        $currency_id = Request::header('currency_id');
        $currency = new CurrencyController();
        $currencyResponse = $currency->latestExchange($currency_id, false);
        if($currencyResponse['success']) {
            $currency_exchange = $currencyResponse['result']->exchange;
            $price = round($price*$currency_exchange,2);
        }

        return $price;
    }

    public function getIsFavouriteAttribute(){
        $authorization = \Request::header('Authorization');
        $authorization = substr($authorization,7);
        $token = PersonalAccessToken::findToken($authorization);
        if($token){
            $favourite = UserFavorite::where('ref_id',$this->id)->where('type',3)->where('user_id',$token->tokenable_id)->first();
            if($favourite){
                return $favourite->id;
            }
            return 0;
        }
        return 0;
    }
    
    public function getIsOrderedAttribute()
    {
        $authorization = \Request::header('Authorization');
        $authorization = substr($authorization, 7);
        $token = PersonalAccessToken::findToken($authorization);
    
        if ($token) {
            $userId = $token->tokenable_id;
    
            $isOrdered = Order::where([
                'user_id' => $userId,
                'ref_type' => 1,
                'ref_id' => $this->id
            ])->exists();
    
            if ($isOrdered) {
                return 1;
            }
    
            return 0;
        }
    
        return 0;
    }
    
    public function getIsOutDatedAttribute()
    {
        $authorization = \Request::header('Authorization');
        $authorization = substr($authorization, 7);
        $token = PersonalAccessToken::findToken($authorization);
    
        if ($token) {
            $userId = $token->tokenable_id;
    
            $firstDate = optional($this->dates()->orderBy('class_date')->first())->class_date;
    
            if ($firstDate && Carbon::now()->gt(Carbon::parse($firstDate))) {
                return 1;
            }
    
            return 0;
        }
    
        return 0;
    }
   
    public function conferences()
    {
        return $this->hasMany(Conference::class, 'ref_id')->where('ref_type', 1);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class, 'group_class_id');
    }
}