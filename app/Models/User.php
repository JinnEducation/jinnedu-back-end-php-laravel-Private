<?php

namespace App\Models;

use App\Notifications\PasswordReset;
use App\Notifications\VerifyEmail;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasRolesAndAbilities;
    use Notifiable;
    use Sluggable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // 'name',
        // 'email',
        // 'password',
        // 'type',
        // 'avatar',
        // 'provider_name',
        // 'provider_id',
        // 'fcm',
        'type',
        'email',
        'password',
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
        // 'code',
        // 'two_factor_recovery_codes',
        // 'two_factor_confirmed_at',
        // 'two_factor_secret',
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
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    // public function sendPasswordResetNotification($token)
    // {
    //     $this->notify(new PasswordReset($token));
    // }

    // public function sendEmailVerificationNotification()
    // {
    //     $this->notify(new VerifyEmail);
    //     // $this->notify(new \App\Notifications\Auth\QueuedVerifyEmail);
    //     // \App\Jobs\QueuedVerifyEmailJob::dispatch($this);
    // }

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

    public function cateqblogs()
    {
        return $this->hasMany(CateqBlog::class);
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

    

    /**
     * Unified availabilities accessor.
     *
     * بدلاً من الاعتماد على جدول user_availabilities فقط، هذه الدالة تقرأ
     * من حقل JSON الجديد الموجود في جدول tutor_profiles.availability_json
     * وتُرجِع بيانات بنفس الشكل الذي كان يرجع من موديل UserAvailability تقريباً،
     * مع دعم الاستدعاء الحالي في المشروع:
     *
     *   $tutor->availabilities()->get();
     *
     * حيث أن:
     *   - availabilities() ترجع كائن بسيط يحتوي على دالة get()
     *   - get() ترجع Collection من كائنات فيها:
     *       $slot->day->name  (Sunday, Monday, ...)
     *       $slot->day->id    (1..7 تقريبياً)
     *       $slot->hour_from  (09:00)
     *       $slot->hour_to    (19:00)
     */
    public function availabilities()
    {
        $user = $this;

        return new class($user)
        {
            protected $user;

            public function __construct(User $user)
            {
                $this->user = $user;
            }

            /**
             * يحاكي استدعاء الـ Eloquent: $user->availabilities()->get()
             */
            public function get()
            {
                $profile = $this->user->tutorProfile;

                // لو عندنا JSON جديد في tutor_profiles نستخدمه
                if ($profile && is_array($profile->availability_json)) {
                    $result = [];

                    // خريطة أيام الأسبوع إلى أرقام تقريبية مثل جدول week_days
                    $dayIds = [
                        'sunday' => 1,
                        'monday' => 2,
                        'tuesday' => 3,
                        'wednesday' => 4,
                        'thursday' => 5,
                        'friday' => 6,
                        'saturday' => 7,
                    ];

                    foreach ($profile->availability_json as $dayKey => $slots) {
                        if (! is_array($slots)) {
                            continue;
                        }

                        $normalizedKey = strtolower((string) $dayKey);
                        $dayName = ucfirst($normalizedKey); // Sunday, Monday, ...
                        $dayId = $dayIds[$normalizedKey] ?? 0;

                        $dayObject = (object) [
                            'id' => $dayId,
                            'name' => $dayName,
                        ];

                        foreach ($slots as $slot) {
                            if (! is_array($slot)) {
                                continue;
                            }

                            $from = $slot['from'] ?? null;
                            $to = $slot['to'] ?? null;

                            if (! $from || ! $to) {
                                continue;
                            }

                            $result[] = (object) [
                                'day' => $dayObject,
                                'hour_from' => $from,
                                'hour_to' => $to,
                                // لم يعد لدينا موديل timezone هنا، فنضعه null للتوافق
                                'timezone' => null,
                            ];
                        }
                    }

                    return collect($result);
                }

                // في حال عدم وجود JSON نرجع للجدول القديم (لو ما زال مستخدماً)
                return UserAvailability::where('user_id', $this->user->id)
                    ->with(['timezone', 'day'])
                    ->get();
            }
        };
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

    // داخل User model
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function tutorProfile()
    {
        return $this->hasOne(TutorProfile::class);
    }

    /**
     * تحديد إذا المستخدم معلم
     */
    public function isTutor(): bool
    {
        return $this->type == 2;
    }

    /**
     * تحديد إذا المستخدم طالب
     */
    public function isStudent(): bool
    {
        return $this->type == 1;
    }
}
