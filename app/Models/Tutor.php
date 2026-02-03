<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Str;

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

    protected $append = [
        'full_name',
        'avatar'
    ];

    public function reviews()
    {
        return $this->hasMany(TutorReview::class, 'tutor_id');
    }

    public function getIsFavouriteAttribute()
    {
        $authorization = \Request::header('Authorization');
        $authorization = substr($authorization, 7);
        $token = PersonalAccessToken::findToken($authorization);
        if ($token) {
            $favourite = UserFavorite::where('ref_id', $this->id)->where('type', 1)->where('user_id', $token->tokenable_id)->first();
            if ($favourite) {
                return $favourite->id;
            }
            return 0;
        }
        return 0;
    }

    public function getNumberOfReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    public function groupClasses()
    {
        return $this->belongsToMany(GroupClass::class, 'group_class_tutors', 'tutor_id', 'group_class_id')
            ->withPivot('status');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function tutorProfile()
    {
        return $this->hasOne(TutorProfile::class, 'user_id', 'id');
    }

    // Accessor for name
    public function getFullNameAttribute()
    {
        return isset($this->profile?->first_name) ? $this->profile?->first_name . ' ' . $this->profile?->last_name : ($this->name ?? 'Unknown');
    }
    public function getAvatarAttribute() // $user->avatar
    {
        if (Str::startsWith($this->profile?->avatar_path, ['http', 'https'])) {
            return $this->profile?->avatar_path;
        }
        if ($this->profile?->avatar_path) {
            return asset('storage/' . $this->profile?->avatar_path);
        }

        return asset('front/assets/imgs/tutors/1.jpg');
    }
}
