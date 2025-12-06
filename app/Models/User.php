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
            protected $filterDate = null;
            protected $period = 60;

            public function __construct(User $user)
            {
                $this->user = $user;
            }

            /**
             * تحديد التاريخ للفلترة (اختياري)
             */
            public function forDate($date, $period = 60)
            {
                $this->filterDate = $date;
                $this->period = $period;
                return $this;
            }

            /**
             * Eloquent: $user->availabilities()->get()
            */
            public function get()
            {
                /*
                #items: array:7 [▼
                    0 => {#2128 ▼
                        +"day": {#2122 ▶}
                        +"hour_from": "09:00"
                        +"hour_to": "10:00"
                        +"timezone": null
                    }
                    1 => {#2153 ▼
                        +"day": {#2122 ▶}
                        +"hour_from": "11:00"
                        +"hour_to": "17:00"
                        +"timezone": null
                    }
                    2 => {#2113 ▼
                        +"day": {#2145 ▶}
                        +"hour_from": "09:00"
                        +"hour_to": "19:00"
                        +"timezone": null
                    }
                    3 => {#2151 ▼
                        +"day": {#2149 ▶}
                        +"hour_from": "09:00"
                        +"hour_to": "10:00"
                        +"timezone": null
                    }
                    4 => {#2144 ▼
                        +"day": {#2148 ▶}
                        +"hour_from": "09:00"
                        +"hour_to": "19:00"
                        +"timezone": null
                    }
                    5 => {#2114 ▼
                        +"day": {#2120 ▶}
                        +"hour_from": "09:00"
                        +"hour_to": "19:00"
                        +"timezone": null
                    }
                    6 => {#2146 ▼
                        +"day": {#2120 ▶}
                        +"hour_from": "09:00"
                        +"hour_to": "19:00"
                        +"timezone": null
                    }
                    ]
                */
                $profile = $this->user->tutorProfile;
                $availability_json = $profile?->availability_json ? json_decode($profile?->availability_json, true) : [];
                if ($profile && is_array($availability_json)) {
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

                    // جلب الأوقات المحجوزة إذا تم تحديد تاريخ
                    $bookedSlots = [];
                    if ($this->filterDate) {
                        $bookedConferences = \App\Models\Conference::where('tutor_id', $this->user->id)
                            ->whereDate('start_date_time', $this->filterDate)
                            ->whereNotNull('start_date_time')
                            ->whereNotNull('end_date_time')
                            ->get(['start_date_time', 'end_date_time']);

                        foreach ($bookedConferences as $conference) {
                            $start = new \DateTime($conference->start_date_time);
                            $end = new \DateTime($conference->end_date_time);
                            
                            $startMinutes = (int)$start->format('H') * 60 + (int)$start->format('i');
                            $endMinutes = (int)$end->format('H') * 60 + (int)$end->format('i');
                            
                            for ($min = $startMinutes; $min < $endMinutes; $min++) {
                                $bookedSlots[$min] = true;
                            }
                        }
                    }

                    $dateObj = $this->filterDate ? new \DateTime($this->filterDate) : null;
                    $dayName = $dateObj ? strtolower($dateObj->format('l')) : null;

                    foreach ($availability_json as $dayKey => $slots) {
                        if (! is_array($slots)) {
                            continue;
                        }

                        $normalizedKey = strtolower((string) $dayKey);
                        $availabilityDayName = strtolower($normalizedKey);
                        $dayNameFormatted = ucfirst($normalizedKey); // Sunday, Monday, ...
                        $dayId = $dayIds[$normalizedKey] ?? 0;

                        // إذا تم تحديد تاريخ، فلتر حسب اليوم
                        if ($dayName && $availabilityDayName != $dayName) {
                            continue;
                        }

                        $dayObject = (object) [
                            'id' => $dayId,
                            'name' => $dayNameFormatted,
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

                            // إذا تم تحديد تاريخ، فلتر الأوقات المحجوزة
                            if ($this->filterDate && !empty($bookedSlots)) {
                                $fromMinutes = $this->timeToMinutes($from);
                                $toMinutes = $this->timeToMinutes($to);
                                
                                // فحص إذا كانت الفترة متاحة (يوجد على الأقل فترة واحدة غير محجوزة)
                                $hasAvailableSlot = false;
                                $checkInterval = 30; // فحص كل 30 دقيقة
                                
                                for ($checkTime = $fromMinutes; $checkTime <= ($toMinutes - $this->period); $checkTime += $checkInterval) {
                                    $slotEnd = $checkTime + $this->period;
                                    $isBooked = false;
                                    
                                    for ($min = $checkTime; $min < $slotEnd; $min++) {
                                        if (isset($bookedSlots[$min])) {
                                            $isBooked = true;
                                            break;
                                        }
                                    }
                                    
                                    if (!$isBooked) {
                                        $hasAvailableSlot = true;
                                        break;
                                    }
                                }
                                
                                if (!$hasAvailableSlot) {
                                    continue; // تخطي هذه الفترة لأنها محجوزة بالكامل
                                }
                            }

                            $result[] = (object) [
                                'day' => $dayObject,
                                'hour_from' => $from,
                                'hour_to' => $to,
                                'timezone' => null,
                            ];
                        }
                    }

                    return collect(value: $result);
                }


                return collect(value: []);
            }

            /**
             * تحويل الوقت إلى دقائق
             */
            private function timeToMinutes($time)
            {
                [$hours, $minutes] = explode(':', $time);
                return (int)$hours * 60 + (int)$minutes;

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

    /**
     * الحصول على الأوقات المتاحة بعد استبعاد الأوقات المحجوزة
     * هذه الدالة ترجع جميع الأوقات المتاحة مع استبعاد الأوقات المحجوزة في الأيام القادمة
     * 
     * @param int $daysForward عدد الأيام القادمة للتحقق من الأوقات المحجوزة (افتراضي 30 يوم)
     * @param int $period مدة الحصة بالدقائق (افتراضي 60)
     * @return \Illuminate\Support\Collection
     */
    public function getFilteredAvailabilities($daysForward = 30, $period = 60)
    {
        $allAvailabilities = $this->availabilities()->get();
        
        // إذا لم يكن هناك أوقات متاحة، ارجع collection فارغة
        if ($allAvailabilities->isEmpty()) {
            return $allAvailabilities;
        }

        // جلب جميع الأوقات المحجوزة للمعلم في الأيام القادمة
        $now = now();
        $futureDate = $now->copy()->addDays($daysForward);
        
        $bookedConferences = \App\Models\Conference::where('tutor_id', $this->id)
            ->whereBetween('start_date_time', [$now->format('Y-m-d H:i:s'), $futureDate->format('Y-m-d H:i:s')])
            ->whereNotNull('start_date_time')
            ->whereNotNull('end_date_time')
            ->get(['start_date_time', 'end_date_time']);

        // تجميع الأوقات المحجوزة حسب اليوم والوقت
        $bookedSlotsByDay = [];
        foreach ($bookedConferences as $conference) {
            $start = new \DateTime($conference->start_date_time);
            $dayName = strtolower($start->format('l')); // monday, tuesday, etc.
            
            if (!isset($bookedSlotsByDay[$dayName])) {
                $bookedSlotsByDay[$dayName] = [];
            }
            
            $startMinutes = (int)$start->format('H') * 60 + (int)$start->format('i');
            $end = new \DateTime($conference->end_date_time);
            $endMinutes = (int)$end->format('H') * 60 + (int)$end->format('i');
            
            // إضافة جميع الدقائق المحجوزة
            for ($min = $startMinutes; $min < $endMinutes; $min++) {
                $bookedSlotsByDay[$dayName][$min] = true;
            }
        }

        // دالة مساعدة لتحويل الوقت إلى دقائق
        $timeToMinutes = function($time) {
            [$hours, $minutes] = explode(':', $time);
            return (int)$hours * 60 + (int)$minutes;
        };

        // فلترة الأوقات المتاحة
        return $allAvailabilities->filter(function($availability) use ($bookedSlotsByDay, $period, $timeToMinutes) {
            if (!isset($availability->day) || !isset($availability->day->name)) {
                return true; // إذا لم يكن هناك معلومات اليوم، نرجعها كما هي
            }
            
            $dayName = strtolower($availability->day->name);
            
            // إذا لم يكن هناك أوقات محجوزة لهذا اليوم، نرجع الفترة كما هي
            if (empty($bookedSlotsByDay[$dayName])) {
                return true;
            }

            // التحقق من أن الفترة المتاحة غير محجوزة بالكامل
            $fromMinutes = $timeToMinutes($availability->hour_from);
            $toMinutes = $timeToMinutes($availability->hour_to);
            
            // فحص إذا كانت هناك فترة واحدة متاحة على الأقل
            $checkInterval = 30; // فحص كل 30 دقيقة
            for ($checkTime = $fromMinutes; $checkTime <= ($toMinutes - $period); $checkTime += $checkInterval) {
                $slotEnd = $checkTime + $period;
                $isBooked = false;
                
                for ($min = $checkTime; $min < $slotEnd; $min++) {
                    if (isset($bookedSlotsByDay[$dayName][$min])) {
                        $isBooked = true;
                        break;
                    }
                }
                
                // إذا وجدنا فترة واحدة متاحة على الأقل، نرجع true
                if (!$isBooked) {
                    return true;
                }
            }
            
            // إذا كانت جميع الفترات محجوزة، نرجع false
            return false;
        });
    }

}
