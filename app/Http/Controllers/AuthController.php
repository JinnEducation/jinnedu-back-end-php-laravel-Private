<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Experience;
use App\Models\Language;
use App\Models\LoginSessionLog;
use App\Models\Menu;
use App\Models\Situation;
use App\Models\Specialization;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\GeneralNotification;
use App\Rules\PhoneNumber;
use Bouncer;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
// use Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Mail;

class AuthController extends Controller
{
    public function checkAbilities()
    {
        $user = Auth::user();
        $roles = $user->getRoles();
        $abilities = $user->getAbilities();

        return response()->json([$abilities, $roles]);
    }

    public function checkMail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,'.$request->user_id ?? null,
        ]);

        // // If validation fails, throw an exception
        // if ($validator->fails()) {
        //     throw new ValidationException($validator);
        // }

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        // Validation passed, return a success response
        return response()->json(['success' => true, 'message' => 'Validation passed successfully'], 200);

    }

    public function emailCheck(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->exists();
        if ($user) {
            return response()->json(['success' => false, 'message' => 'Email already exists', 'isAvailable' => false], 200);
        }

        return response()->json(['success' => true, 'message' => 'Email is available', 'isAvailable' => true], 200);
    }

    public function register(Request $request)
    {
        // return response($request->all());
        $countryId = $request->phoneCountryId;
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'type' => 'required',
            'phoneCountryId' => 'required|exists:countries,id',
            'phone' => ['required', new PhoneNumber($countryId)],
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'fcm' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $country = Country::find($countryId);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'fcm' => $request->fcm,
            'phone' => $country ? $country->phonecode.$request->phone : $request->phone,
        ]);

        // Assign roles based on user type
        if ($request->type == '1') {
            $user->assign('student');
        } elseif ($request->type == '2') {
            $user->assign('tutor');
        } elseif ($request->type == '3') {
            $user->assign('parent');
        }

        // Create a login session log
        $token = $user->createToken('main')->plainTextToken;
        $user->remember_token = $token;

        $log = new LoginSessionLog;
        $log->user_id = $user->id;
        $log->session = $token;
        $log->ipaddress = $request->ip();
        $log->browser = $request->userAgent();
        $log->os = 'register';
        $log->save();

        // Upload avatar if provided
        if (! empty($request->avatar)) {
            $user->avatar = uploadFile($request->avatar, ['jpg', 'jpeg', 'png'], 'users');
            $user->save();
        }

        // Send a general notification
        $notifyData = new GeneralNotification(2, 0);
        Notification::send($user, $notifyData);

        // Fire the Registered event
        try {
            @event(new Registered($user));
        } catch (\Exception $e) {
            return response([
                'success' => false,
                'message' => 'Cannot send mail. Incorrect domain or server issue.',
                'msg-code' => 'mail-send-error',
            ], 400);
        }

        return response([
            'success' => true,
            'message' => 'Register Successfully',
            'result' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required',
            'remember' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $data = $request->only(['email', 'password']);
        $remember = $request->remember ?? false;

        if (! Auth::attempt($data, $remember)) {
            return response([
                'success' => false,
                'message' => 'The Login info is not correct',
                'msg-code' => '111',
            ], 401);
        }

        $user = Auth::user();

        if (($request->type == 0 && $user->type != 0) || ($request->type != 0 && ! in_array($user->type, [1, 2]))) {

            return response([
                'success' => false,
                'message' => 'The Login info is not correct',
                'msg-code' => '111',
            ], 500);

        }

        /*if(!$user->verified) return response([
                'success' => false,
                'message' => 'email-not-verified',
                'msg-code' => '111'
            ] , 400);*/
        // $user->avatar = '/src/assets/media/avatars/300-1.jpg';
        $token = $user->createToken('main')->plainTextToken;
        // $user->api_token=$token;

        $user->remember_token = $token;
        $user->fcm = $request->fcm ?? $user->fcm;
        $user->save();

        $notifyData = new GeneralNotification(1, 0);
        Notification::send($user, $notifyData);

        $log = new LoginSessionLog;
        $log->user_id = $user->id;
        $log->session = $token;
        $log->ipaddress = $request->ip();
        $log->browser = $request->userAgent();
        $log->os = 'login';
        $log->save();

        // {"id":2,"first_name":"German","last_name":"Stark","email":"admin@demo.com","email_verified_at":"2022-07-14T11:37:39.000000Z","created_at":"2022-07-14T11:37:39.000000Z","updated_at":"2022-07-14T11:37:39.000000Z","api_token":"$2y$10$lzYGs3CVjxdlR2ERLfZOyezaXM8qXLGd5fHEkjoBmDxznEl.CvAdC"}

        // return response($user);

        $item = null;

        foreach ($user->roles as $index => $role) {
            if (Bouncer::is($user)->a($role->name)) {
                $item = Bouncer::role()->find($role->id);
            }

            $menus = Menu::parents()->get();
            foreach ($menus as $menu) {
                if ($menu->type == '') {
                    $menu->childrens = $menu->childes()->get();
                    foreach ($menu->childrens as $submenu) {
                        $submenu->childrens = $submenu->childes()->get();
                        foreach ($submenu->childrens as $subnav) {
                            $subnav->checked = false;
                            if (isset($item)) {
                                $subnav->checked = $item->can($subnav->name, $subnav->type);
                            }
                        }
                    }
                } else {
                    $menu->childrens = $menu->childes()->get();
                    foreach ($menu->childrens as $subnav) {
                        $subnav->checked = false;
                        if (isset($item)) {
                            $subnav->checked = $item->can($subnav->name, $subnav->type);
                        }
                    }
                }
            }

            $user->roles[$index]->permissions = $menus;
        }

        return response([
            'success' => true,
            'message' => 'Login Successfully',
            'result' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    public function logout()
    {

        /** @var User $user */
        // $user = Auth::user();
        // $user->currentAccessToken()->delete();

        return response([
            'success' => true,
        ]);
    }

    public function redirectToDashboard(Request $request)
    {
        // لازم المستخدم يكون داخل مسبقاً
        if (! Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        // $type = $user->type; // 1 = student, 2 = teacher ...
        $type = 1; // 1 = student, 2 = teacher ...

        // نوع المستخدم يحدد المسار في Vue
        $redirectPathReq = $request->redirect_to ?? '';
        $redirectPath = 'me/dashboard'.$redirectPathReq;

        // ✳️ أنشئ التوكن بالطريقة المضمونة
        $tokenResult = $user->createToken('main');

        // ✳️ احفظ التوكن الكامل (اللي فيه id|hash)
        $plainTextToken = $tokenResult->plainTextToken;

        // ✳️ خزن النص الكامل في remember_token (لإعادة الاستخدام)
        $user->remember_token = $plainTextToken;
        $user->save();

        // 🔹 عنوان مشروع Vue المحلي
        $vueApp = in_array(env('APP_ENV'), ['local', 'development']) ? 'http://localhost:5173/me' : env('APP_URL').'me'; // محلي

        // 🔹 نحول المستخدم إلى صفحة التحقق داخل Vue
        $redirectUrl = "{$vueApp}/auth/sign-in-check?token={$plainTextToken}&email={$user->email}&to={$redirectPath}";

        return redirect()->away($redirectUrl);
    }

    public function changePassword(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }
        $user = Auth::user();

        // Match The Old Password
        if (! Hash::check($request->old_password, auth()->user()->password)) {
            return response([
                'success' => false,
                'message' => 'Old Password Doesn\'t match!',
                'msg-code' => '111',
            ], 200);
        }

        // Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        $notifyData = new GeneralNotification(3, 0);
        Notification::send($user, $notifyData);

        return response([
            'success' => true,
        ]);
    }

    public function changeEmail(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required | email | unique:users,email,'.auth()->user()->id,
        ]);
        $user = Auth::user();

        // Update the new Email
        User::whereId(auth()->user()->id)->update([
            'email' => $request->email,
        ]);

        $notifyData = new GeneralNotification(4, 0);
        Notification::send($user, $notifyData);

        return response([
            'success' => true,
        ]);
    }

    public function changeName(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required',
        ]);

        $user = Auth::user();

        // Update the new Name
        User::whereId(auth()->user()->id)->update([
            'name' => $request->name,
        ]);

        $notifyData = new GeneralNotification(5, 0);
        Notification::send($user, $notifyData);

        return response([
            'success' => true,
        ]);
    }

    public function changeAvatar(Request $request)
    {
        $data = $request->only(['photo']); // ,'password'
        $user = Auth::user();

        $imageUrl = uploadImg($request->photo);
        $user->avatar = $imageUrl;
        $user->save();

        $notifyData = new GeneralNotification(6, 0);
        Notification::send($user, $notifyData);

        return response([
            'url' => url('/').$user->avatar,
        ]);
    }

    public function profileUser(Request $request)
    {
        $user = $request->user_id
            ? User::with(['profile', 'tutorProfile'])->find($request->user_id)
            : Auth::user()?->load(['profile', 'tutorProfile']);

        if (! $user) {
            return response([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $profile = $user->profile;       // user_profiles
        $tutor = $user->tutorProfile;  // tutor_profiles

        // ===== Avatar (Vue يستخدم user.avatar)
        $avatarPath = $profile?->avatar_path;
        $userAvatar = $avatarPath ? (str_starts_with($avatarPath, 'http') ? $avatarPath : Storage::url($avatarPath)) : null;

        // ===== Country object (Vue يتوقع country.name)
        $countryValue = $profile?->country; // ممكن تكون id أو نص
        $countryObj = null;

        if ($countryValue !== null && $countryValue !== '') {
            if (is_numeric($countryValue)) {
                // لو عندك Country model فعلي:
                // $c = Country::find((int) $countryValue);
                // $countryObj = $c ? ['id' => $c->id, 'name' => $c->name] : ['id' => (int)$countryValue, 'name' => (string)$countryValue];

                // بدون جدول دول: رجّع id + name كـ fallback
                $countryObj = ['id' => (int) $countryValue, 'name' => (string) $countryValue];
            } else {
                $countryObj = ['id' => null, 'name' => (string) $countryValue];
            }
        }

        // ===== DOB + AGE (Vue يستخدم age)
        $dob = $tutor?->dob;
        $age = null;
        if (! empty($dob)) {
            try {
                $age = Carbon::parse($dob)->age;
            } catch (\Throwable $e) {
                $age = null;
            }
        }

        // ========= abouts[]
        $abouts = [];
        if ($profile) {
            $abouts[] = (object) [
                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,
                'email' => $user->email,

                'phone' => $profile->contact_number,
                'age' => $age,

                // Vue عندك يستخدم profile.abouts[0].country.name
                'country' => $countryObj ? (object) $countryObj : null,

                // خليهم موجودين للتوافق
                'country_id' => is_numeric($countryValue) ? (int) $countryValue : null,
                'language_id' => null,
                'subject_id' => null,
                'experience_id' => null,
                'situation_id' => null,

                'date_of_birth' => $dob,

                // توافق إضافي قديم
                'contact_number' => $profile->contact_number,
                'avatar' => $userAvatar,
            ];
        }
        $user->abouts = $abouts;

        // ========= Root avatar
        $user->avatar = $userAvatar;

        // ========= availabilities[]
        $dayMap = [
            'saturday' => 1,
            'sunday' => 2,
            'monday' => 3,
            'tuesday' => 4,
            'wednesday' => 5,
            'thursday' => 6,
            'friday' => 7,
        ];

        $availabilities = [];
        $availabilityJson = $tutor?->availability_json;

        // الأفضل: اعمل cast بالموديل (بوضح تحت)، بس هنا نخليه safe
        if (is_string($availabilityJson)) {
            $availabilityJson = json_decode($availabilityJson, true);
        }

        if (is_array($availabilityJson)) {
            foreach ($availabilityJson as $dayKey => $slots) {
                $dayId = $dayMap[strtolower($dayKey)] ?? null;
                if (! $dayId || ! is_array($slots)) {
                    continue;
                }

                foreach ($slots as $slot) {
                    $from = $slot['from'] ?? null;
                    $to = $slot['to'] ?? null;
                    if (! $from || ! $to) {
                        continue;
                    }

                    $availabilities[] = (object) [
                        'id' => null,
                        'day_id' => $dayId,
                        'day' => (object) [
                            'id' => $dayId,
                            'name' => ucfirst(strtolower($dayKey)),
                        ],
                        'hour_from' => $from,
                        'hour_to' => $to,
                    ];
                }
            }
        }
        $user->availabilities = $availabilities;

        // ========= descriptions[]
        $descriptions = [];
        if ($tutor) {
            $descriptions[] = (object) [
                'headline' => $tutor->headline,
                'interests' => $tutor->interests,
                'motivation' => $tutor->motivation,
                'specialization_id' => null,
                'experience' => $tutor->experience_bio,
                'methodology' => $tutor->methodology,
            ];
        }
        $user->descriptions = $descriptions;

        // ========= hourlyPrices[]
        $hourlyPrices = [];
        if ($tutor && $tutor->hourly_rate !== null) {
            $hourlyPrices[] = (object) ['price' => $tutor->hourly_rate];
        }
        $user->hourlyPrices = $hourlyPrices;

        // ========= certifications/videos (اختياري)
        $certifications = [];

        $certs = $tutor?->certifications_json;

        if (is_string($certs)) {
            $certs = json_decode($certs, true);
        }

        if (is_array($certs)) {
            foreach ($certs as $cert) {
                $certifications[] = (object) [
                    'name' => $cert['name'] ?? null,
                    'description' => $cert['description'] ?? null,
                    'issued_by' => $cert['issued_by'] ?? null,
                    'year_from' => $cert['year_from'] ?? null,
                    'year_to' => $cert['year_to'] ?? null,
                    'file_path' => ! empty($cert['file_path'])
                        ? Storage::url($cert['file_path'])
                        : null,
                ];
            }
        }

        // اربطها مرة وحدة فقط
        $user->setAttribute('certifications', $certifications);

        $videos = [];

        if ($tutor && $tutor->video_path) {
            $videos[] = [
                'file' => Storage::url($tutor->video_path),
                'approved' => (bool) $tutor->video_terms_agreed,
            ];
        }

        // اربطها مرة وحدة
        $user->setAttribute('videos', $videos);

        return response([
            'success' => true,
            'message' => 'Profile retrieved Successfully',
            'result' => $user,
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user_id ? User::find($request->user_id) : Auth::user();

        if (! $user) {
            return response([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $profile = $user->profile;       // جدول user_profiles (الجديد)
        $tutor = $user->tutorProfile;  // جدول tutor_profiles (الجديد)

        // ========= 1) Root avatar (Vue يستخدم user.avatar)
        $user->avatar = $profile?->avatar_path;

        // ========= 2) abouts[] (Vue يستخدم abouts[0].first_name ... country_id ... phone ... date_of_birth)
        $abouts = [];
        if ($profile) {
            $abouts[] = (object) [
                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,
                'email' => $user->email,

                // Vue متوقع phone و country_id (خليهم موجودين حتى لو null)
                'phone' => $profile->contact_number,
                'country_id' => $profile->country, // إذا عندك country_id فعلي بالموديل بدل country عدّلها
                'language_id' => null,
                'subject_id' => null,
                'experience_id' => null,
                'situation_id' => null,

                // Vue متوقع date_of_birth
                'date_of_birth' => $tutor?->dob, // أو إذا عندك بالـ profile عدّلها

                // (اختياري) خليك متوافق كمان مع القديم
                'country' => $profile->country,
                'contact_number' => $profile->contact_number,
                'avatar' => $profile->avatar_path,
            ];
        }
        $user->abouts = $abouts;

        // ========= 3) availabilities[] (Vue متوقع day.id + hour_from/hour_to)
        $dayMap = [
            'saturday' => 1,
            'sunday' => 2,
            'monday' => 3,
            'tuesday' => 4,
            'wednesday' => 5,
            'thursday' => 6,
            'friday' => 7,
        ];

        $availabilities = [];
        $availabilityJson = $tutor?->availability_json; // بعد cast صارت array

        if (is_array($availabilityJson)) {
            foreach ($availabilityJson as $dayKey => $slots) {
                $dayId = $dayMap[strtolower($dayKey)] ?? null;

                if (! is_array($slots)) {
                    continue;
                }

                foreach ($slots as $slot) {
                    $from = $slot['from'] ?? null;
                    $to = $slot['to'] ?? null;

                    if (! $from || ! $to) {
                        continue;
                    }

                    $availabilities[] = (object) [
                        'id' => null, // القديم كان عنده id من جدول availabilities؛ هنا خليها null
                        'day_id' => $dayId,
                        'day' => (object) [
                            'id' => $dayId,
                            'name' => ucfirst(strtolower($dayKey)),
                        ],
                        'hour_from' => $from,
                        'hour_to' => $to,
                    ];
                }
            }
        }
        $user->availabilities = $availabilities;

        // ========= 4) descriptions[] (Vue متوقع headline/interests/motivation/specialization_id/experience/methodology)
        $descriptions = [];
        if ($tutor) {
            $descriptions[] = (object) [
                'headline' => $tutor->headline,
                'interests' => $tutor->interests,
                'motivation' => $tutor->motivation,
                'specialization_id' => null, // إذا عندك specialization_id فعلي خزّنه ورجّعه
                'experience' => $tutor->experience_bio, // Vue يستخدم descriptions[0].experience
                'methodology' => $tutor->methodology,
            ];
        }
        $user->descriptions = $descriptions;

        // ========= 5) hourlyPrices[] (Vue متوقع price)
        $hourlyPrices = [];
        if ($tutor && $tutor->hourly_rate !== null) {
            $hourlyPrices[] = (object) [
                'price' => $tutor->hourly_rate,
            ];
        }
        $user->hourlyPrices = $hourlyPrices;

        // ========= (اختياري) certifications/videos/languages إذا الفرونت يحتاجهم لاحقًا
        $user->certifications = [];
        $certs = $tutor?->certifications_json;
        if (is_array($certs)) {
            foreach ($certs as $cert) {
                $user->certifications[] = (object) [
                    'name' => $cert['name'] ?? null,
                    'description' => $cert['description'] ?? null,
                    'issued_by' => $cert['issued_by'] ?? null,
                    'year_from' => $cert['year_from'] ?? null,
                    'year_to' => $cert['year_to'] ?? null,
                    'file_path' => $cert['file_path'] ?? null,
                ];
            }
        }

        $videos = [];

        if ($tutor && $tutor->video_path) {
            $videos[] = [
                'file' => $tutor->video_path,
                'approved' => (bool) $tutor->video_terms_agreed,
            ];
        }

        $user->videos = $videos;

        return response([
            'success' => true,
            'message' => 'Profile retrieved Successfully',
            'result' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email,'.auth()->id(),
            'phone' => 'nullable|string|max:50',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // ========= 1) تحليل الاسم
            $nameParts = explode(' ', trim($request->name), 2);
            $firstName = $nameParts[0] ?? null;
            $lastName = $nameParts[1] ?? null;

            // ========= 2) تحديث users
            $user->update([
                'name' => trim($request->name),
                'email' => $request->email,
            ]);

            // ========= 3) تجهيز الصورة (نفس register)
            $avatarPath = $user->profile?->avatar_path;

            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }

            // ========= 4) تحديث user_profiles
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email_display' => $request->email,
                    'contact_number' => $request->phone,
                    'avatar_path' => $avatarPath,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function forgotPassword(Request $request)
    {

        $request->validate(['email' => 'required|email']);
        $checkEmail = User::where('email', $request->email)->first();
        if (! $checkEmail) {
            return response([
                'success' => false,
                'message' => 'email-not-exist',
                'msg-code' => '111',
            ], 200);
        }

        try {

            $status = Password::sendResetLink(
                $request->only('email')
            );

        } catch (\Exception $e) {

            return response([
                'success' => false,
                'message' => 'cannot-send-mail-incoreect-domain-server',
                'msg-code' => '222',
            ], 200);
        }

        return response([
            'success' => $status === Password::RESET_LINK_SENT ? true : false,
            'result' => __($status),
        ]);

    }

    public function resetPassword($token)
    {

        return response([
            'success' => true,
            'token' => $token,
        ]);

    }

    public function passwordUpdate(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                $notifyData = new GeneralNotification(7, 0);
                Notification::send($user, $notifyData);

                event(new PasswordReset($user));
                try {

                    @event(new PasswordReset($user));

                } catch (\Exception $e) {

                    return response([
                        'success' => false,
                        'message' => 'cannot-send-mail-incoreect-domain-server',
                        'msg-code' => '222',
                    ], 200);
                }
            }
        );

        $success = ($status === Password::PASSWORD_RESET) ? true : false;

        return response([
            'success' => $success,
            'result' => __($status),
        ], 200);

    }

    public function socialLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'provider_name' => 'required',
            'provider_id' => 'required',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {

            $token = $user->createToken('main')->plainTextToken;

            $user->remember_token = $token;
            $user->fcm = $request->fcm ?? $user->fcm;
            $user->email_verified_at = $request->provider_name != 'apple' ? ($user->email_verified_at ?? now()) : null;
            $user->save();

            $log = new LoginSessionLog;
            $log->user_id = $user->id;
            $log->session = $token;
            $log->ipaddress = $request->ip();
            $log->browser = $request->userAgent();
            $log->os = 'login';
            $log->save();

            return response([
                'success' => true,
                'message' => 'Login Successfully',
                'result' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ]);
        } else {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->email),
                'provider_name' => $request->provider_name,
                'provider_id' => $request->provider_id,
                'type' => $request->type,
                'fcm' => $request->fcm,
                'email_verified_at' => $request->provider_name != 'apple' ? now() : null,
            ]);

            // Assign roles based on user type
            if ($request->type == '1') {
                $user->assign('student');
            } elseif ($request->type == '2') {
                $user->assign('tutor');
            } elseif ($request->type == '3') {
                $user->assign('parent');
            }

            // Create a login session log
            $token = $user->createToken('main')->plainTextToken;
            $log = new LoginSessionLog;
            $log->user_id = $user->id;
            $log->session = $token;
            $log->ipaddress = $request->ip();
            $log->browser = $request->userAgent();
            $log->os = 'register';
            $log->save();

            // Upload avatar if provided
            if (! empty($request->avatar)) {
                // Extract the filename from the avatar URL
                $filename = basename($request->avatar);

                // Download the avatar image
                $avatarContents = file_get_contents($request->avatar);

                // Specify the directory to save the avatar image
                $directory = 'users/';

                // Save the avatar image to the specified directory
                $tempPath = $directory.$filename;

                file_put_contents(public_path($tempPath), $avatarContents);

                $datePath = date('Y').'/'.date('m').'/'.date('d');

                $path = storage_path('app/public/');
                $destinationDirectory = $path.$directory.$datePath;

                $moved = move_uploaded_file($tempPath, $destinationDirectory.'/'.$filename);

                if ($moved) {
                    unlink(public_path($tempPath));
                }

                $user->avatar = $directory.$filename;
                $user->save();
            }

            return response([
                'success' => true,
                'message' => 'Register Successfully',
                'result' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ]);
        }
    }

    public function sendCode(Request $request)
    {

        $user = auth()->user();

        $code = create_random_code(4, [
            'upper_case' => false,
            'lower_case' => false,
            'number' => true,
            'special_character' => false,
        ]);

        $user->code = $code;
        $user->save();

        $data = ['user' => $user];

        Mail::send('emails.verify_email', $data, function ($mail) use ($user) {
            $mail->to($user->email, $user->name)
                ->subject('Verify Email');
        });

        return response([
            'success' => true,
            'message' => 'code-send-successfully',
        ], 200);
    }

    public function verifyEmail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $user = auth()->user();

        if ($user->code !== $request->code) {
            return response([
                'success' => false,
                'message' => 'Wrong Code',
            ], 200);
        }

        $user->markEmailAsVerified();
        $user->code = null;
        $user->save();

        return response([
            'success' => true,
            'message' => 'email-verified-successfully',
            'result' => [
                'user' => $user,
            ],
        ], 200);
    }

    public function deleteAccount(Request $request)
    {

        $user = auth()->user();

        if (! $user) {
            return response([
                'success' => false,
                'message' => 'User Not Found',
                'msg-code' => '111',
            ], 200);
        }

        $user->delete();

        return response([
            'success' => true,
            'message' => 'user-deleted-successfully',
        ], 200);

    }

    public function editProfile()
    {
        $user = Auth::user();

        // العلاقات
        $profile = $user->profile;
        $tutorProfile = $user->tutorProfile;

        // dropdowns (بدل ما تجيبهم من داخل Blade)
        $countries = Country::get();
        $languages = Language::get();
        $subjects = Subject::get();
        $experiences = Experience::get();
        $situations = Situation::get();
        $specializations = Specialization::get();

        // تجهيز قيم JSON للمدرس (عشان prefill في Blade بسهولة)
        $availability = [];
        $certifications = [];

        if ($user->type == 2 && $tutorProfile) {
            $availability = $tutorProfile->availability_json
                ? json_decode($tutorProfile->availability_json, true) : [];

            $certifications = $tutorProfile->certifications_json
                ? json_decode($tutorProfile->certifications_json, true) : [];
        }

        // ملاحظة: type=0 و type=1 نفس الشيء (Step 2 فقط)
        return view('auth.edit-profile', compact(
            'user',
            'profile',
            'tutorProfile',
            'countries',
            'languages',
            'subjects',
            'experiences',
            'situations',
            'specializations',
            'availability',
            'certifications'
        ));
    }

    public function editProfileStore(Request $request)
    {
        $user = Auth::user();

        DB::beginTransaction();
        try {

            /*
            |--------------------------------------------------------------------------
            | 1. Update User (email لا يتغير هنا)
            |--------------------------------------------------------------------------
            */
            $user->update([
                'type' => $request->type, // ثابت غالبًا، لكن نخليه
                'phone' => $request->phone,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 2. Handle Avatar (only if uploaded)
            |--------------------------------------------------------------------------
            */
            $avatarPath = $user->profile?->avatar_path;

            if ($request->hasFile('avatar')) {
                // حذف القديم
                if ($avatarPath && Storage::exists($avatarPath)) {
                    Storage::delete($avatarPath);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Update or Create Profile
            |--------------------------------------------------------------------------
            */
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email_display' => $user->email,
                    'country' => $request->country,
                    'contact_number' => $request->phone,
                    'avatar_path' => $avatarPath,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 4. Tutor Profile (type = 2)
            |--------------------------------------------------------------------------
            */
            if ($request->type == 2) {

                // availability
                $availability = $request->filled('availability')
                    ? json_encode($request->availability)
                    : $user->tutorProfile?->availability_json;
                /*
                | Certification file (only if uploaded)
                */
                $certifications = [];

                $oldCerts = $user->tutorProfile?->certifications_json
                    ? json_decode($user->tutorProfile->certifications_json, true)
                    : [];

                $certFilePath = $oldCerts[0]['file_path'] ?? null;

                if ($request->hasFile('certification_file')) {
                    if ($certFilePath && Storage::exists($certFilePath)) {
                        Storage::delete($certFilePath);
                    }

                    $certFilePath = $request->file('certification_file')
                        ->store('certification_files', 'public');
                }

                $certifications[] = [
                    'subject' => $request->certification_subject ?? null,
                    'name' => $request->certification_name ?? null,
                    'description' => $request->certification_description ?? null,
                    'issued_by' => $request->certification_issued_by ?? null,
                    'year_from' => $request->certification_year_from ?? null,
                    'year_to' => $request->certification_year_to ?? null,
                    'file_path' => $certFilePath,
                ];

                /*
                | Video file (only if uploaded)
                */
                $videoPath = $user->tutorProfile?->video_path;

                if ($request->hasFile('video_file')) {
                    if ($videoPath && Storage::exists($videoPath)) {
                        Storage::delete($videoPath);
                    }

                    $videoPath = $request->file('video_file')
                        ->store('video_files', 'public');
                }

                /*
                | Update or Create Tutor Profile
                */
                $user->tutorProfile()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'dob' => $request->filled('date_of_birth')
                            ? date('Y-m-d', strtotime($request->date_of_birth))
                            : null,

                        'tutor_country' => $request->countty_tutor,
                        'native_language' => $request->language,
                        'teaching_subject' => $request->teaching_subject,
                        'teaching_experience' => $request->teaching_experience,
                        'situation' => $request->situation,

                        'headline' => $request->headline,
                        'interests' => $request->interests,
                        'motivation' => $request->motivation,
                        'specializations' => $request->specializations,

                        'experience_bio' => $request->experience,
                        'methodology' => $request->methodology,

                        'availability_json' => $availability,
                        'hourly_rate' => $request->hourly_rate,
                        'certifications_json' => json_encode($certifications),

                        'video_path' => $videoPath,
                        'video_terms_agreed' => $request->has('agree_terms'),
                    ]
                );

            }

            DB::commit();

            return redirect()
                ->route('home')
                ->with('success', __('site.Update Profile'));

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // Redirect to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Callback
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $data = [
            'provider' => 'google',
            'first_name' => $googleUser->user['given_name'] ?? null,
            'last_name' => $googleUser->user['family_name'] ?? null,
            'email' => $googleUser->email,
            'avatar' => $googleUser->avatar,
            'google_id' => $googleUser->id,
            'loginNow' => false,
        ];

        $user = User::where('google_id', $data['google_id'])
            ->where('email', $data['email'])
            ->first();

        if ($user) {
            $data['loginNow'] = true;
            Auth::login($user, true);
        }

        return view('auth.oauth-popup-close', compact('data'));
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);
        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return $request->wantsJson()
                        ? new JsonResponse([], 204)
                        : redirect()->route('home')->with('verified', true);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // if ($response = $this->verified($request)) {
        //     return $response;
        // }

        return $request->wantsJson()
                    ? new JsonResponse([], 204)
                    : redirect()->route('home')->with('verified', true);
    }
}
