<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Notifications\PasswordReset;
use App\Notifications\VerifyEmail;
use Cviebrock\EloquentSluggable\Sluggable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRolesAndAbilities;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'avatar',
        'provider_name',
        'provider_id',
        'fcm'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'deleted_at',
        'updated_at',
        'code',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'two_factor_secret'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail());
        //$this->notify(new \App\Notifications\Auth\QueuedVerifyEmail);
        //\App\Jobs\QueuedVerifyEmailJob::dispatch($this);
    }

    // public function chatContacts()
    // {
    //     return $this->hasMany(ChatContact::class, 'user_id', 'id');
    // }

    public function chats()
    {
        return $this->hasMany(Chat::class, 'from_user', 'id');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'from_user', 'id');
    }

    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'to_user', 'id');
    }

    public function chatContacts()
    {
        return $this->hasMany(ChatContact::class, 'user_id', 'id');
    }





    public function abouts()
    {
        return $this->hasOne(UserAbout::class, 'user_id');
    }

    public function availabilities()
    {
        return $this->hasMany(UserAvailability::class, 'user_id');
    }

    public function certifications()
    {
        return $this->hasMany(UserCertification::class, 'user_id');
    }

    public function descriptions()
    {
        return $this->hasMany(UserDescription::class, 'user_id');
    }

    public function educations()
    {
        return $this->hasMany(UserEducation::class, 'user_id');
    }

    public function hourlyPrices()
    {
        return $this->hasMany(UserHourlyPrice::class, 'user_id');
    }

    public function languages()
    {
        return $this->hasMany(UserLanguage::class, 'user_id');
    }

    public function videos()
    {
        return $this->hasMany(UserVideo::class, 'user_id');
    }

    public function parents()
    {
        return $this->hasMany(ParentInvitation::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ParentInvitation::class, 'child_id');
    }

    public function complaints()
    {
        return $this->hasMany(ConferenceComplaint::class, 'user_id');
    }

    public function tutorComplaints()
    {
        return $this->hasMany(ConferenceComplaint::class, 'tutor_id');
    }

    public function studentComplaints()
    {
        return $this->hasMany(ConferenceComplaint::class, 'student_id');
    }

    public function reviews()
    {
        return $this->hasMany(ConferenceReview::class, 'user_id');
    }

    public function tutorReviews()
    {
        return $this->hasMany(ConferenceReview::class, 'tutor_id');
    }


    public function studentReviews()
    {
        return $this->hasMany(ConferenceReview::class, 'student_id');
    }

    public function favorites()
    {
        return $this->hasMany(UserFavorites::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(UserFavorites::class, 'tutor_id');
    }

    public function interests()
    {
        return $this->hasMany(UserInterests::class, 'user_id');
    }

    public function wallets()
    {
        return $this->hasMany(UserWallet::class, 'user_id');
    }

    // Recently added
    public function groupClasses()
    {
        return $this->hasMany(GroupClass::class, 'tutor_id');
    }

    public function courses()
    {
        return $this->hasMany(OurCourseTutor::class, 'tutor_id');
    }

    // public function getAvatarAttribute($avatar)
    // {

    //     return asset(($avatar ?? 'images/default.png'));

    // }
}
