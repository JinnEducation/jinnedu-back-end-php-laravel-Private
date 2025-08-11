<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\PersonalAccessToken;

class Tutor extends User
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $appends = ['is_favourite', 'number_of_reviews'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tutors';


    public function reviews()
    {
        return $this->hasMany(TutorReview::class, 'tutor_id');
    }
        
    public function getIsFavouriteAttribute(){
        $authorization = \Request::header('Authorization');
        $authorization = substr($authorization,7);
        $token = PersonalAccessToken::findToken($authorization);
        if($token){
            $favourite = UserFavorite::where('ref_id',$this->id)->where('type',1)->where('user_id',$token->tokenable_id)->first();
            if($favourite){
                return $favourite->id;
            }
            return 0;
        }
        return 0;
    }

    public function getNumberOfReviewsAttribute() {
        return $this->reviews()->count();
    }

    public function groupClasses()
    {
        return $this->belongsToMany(GroupClass::class, 'group_class_tutors', 'tutor_id', 'group_class_id')
                    ->withPivot('status');
    }
}
