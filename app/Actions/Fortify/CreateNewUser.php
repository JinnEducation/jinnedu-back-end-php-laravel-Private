<?php

namespace App\Actions\Fortify;

use App\Models\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        DB::beginTransaction();
        try {
            // // ✅ 1. التحقق من الحقول الأساسية
            // Validator::make($input, [
            //     'account-type' => ['required', 'in:student,tutor'],
            //     'first_name' => ['required', 'string', 'max:100'],
            //     'last_name' => ['required', 'string', 'max:100'],
            //     'email' => ['required', 'email', 'max:255', Rule::unique(User::class)],
            //     'password' => $this->passwordRules(),
            //     'confirm_terms' => ['accepted'],
            // ])->validate();
            // dd($input);

            // ✅ 2. إنشاء المستخدم الأساسي
            $user = User::create([
                'type' => $input['account-type'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            if (isset($input['avatar']) && $input['avatar'] != null) {
                $avatar = $input['avatar'];
                // $avatar = $avatar->store('avatars');
            } else {
                $avatar = null;
            }

            // ✅ 3. إنشاء الملف العام (User Profile)
            $user->profile()->create([
                'first_name' => $input['first_name'] ?? null,
                'last_name' => $input['last_name'] ?? null,
                'email_display' => $input['email'] ?? null,
                'country' => $input['country'] ?? null,
                'contact_number' => $input['phone'] ?? null,
                'avatar_path' => $avatar ?? null,
                'terms_agreed' => isset($input['confirm_terms']),
            ]);

            // ✅ 4. لو المستخدم معلم، نضيف بيانات المعلم
            if ($input['account-type'] === 'tutor') {
                $availability = isset($input['availability'])
                    ? json_encode($input['availability'])
                    : null;

                if (isset($input['certification_file']) && $input['certification_file'] != null) {
                    $certification_file = $input['certification_file'];
                    // $certification_file = $certification_file->store('certification_files');
                } else {
                    $certification_file = null;
                }

                // بناء JSON للشهادة
                $certifications = [
                    [
                        'name' => $input['certification_name'] ?? null,
                        'description' => $input['certification_description'] ?? null,
                        'issued_by' => $input['certification_issued_by'] ?? null,
                        'year_from' => $input['certification_year_from'] ?? null,
                        'year_to' => $input['certification_year_to'] ?? null,
                        'file_path' => $certification_file ?? null,
                    ],
                ];

                if (isset($input['video_file']) && $input['video_file'] != null) {
                    $video_path = $input['video_file'];
                    // $video_path = $video_path->store('video_files');
                } else {
                    $video_path = null;
                }

                $user->tutorProfile()->create([
                    'dob' => ! empty($input['date_of_birth']) ? date('Y-m-d', strtotime($input['date_of_birth'])) : null,
                    'tutor_country' => $input['countty_tutor'] ?? null,
                    'native_language' => $input['language'] ?? null,
                    'teaching_subject' => $input['teaching_subject'] ?? null,
                    'teaching_experience' => $input['teaching_experience'] ?? null,
                    'situation' => $input['situation'] ?? null,

                    'headline' => $input['headline'] ?? null,
                    'interests' => $input['interests'] ?? null,
                    'motivation' => $input['motivation'] ?? null,
                    'specializations' => $input['specializations'] ?? null,

                    'experience_bio' => $input['experience'] ?? null,
                    'methodology' => $input['methodology'] ?? null,

                    'availability_json' => $availability,
                    'hourly_rate' => $input['hourly_rate'] ?? null,
                    'certifications_json' => json_encode($certifications),

                    'video_path' => $video_path ?? null,
                    'video_terms_agreed' => isset($input['agree_terms']),
                ]);
            }

            DB::commit();
            return $user;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }
        
    }
}
