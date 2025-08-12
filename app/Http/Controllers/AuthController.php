<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use Illuminate\Auth\Events\Registered;

use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use App\Models\Menu;
use App\Models\LoginSessionLog;
use App\Models\Country;

// use Notification;
use App\Notifications\GeneralNotification;

use Bouncer;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Mail;
use App\Rules\PhoneNumber;

class AuthController extends Controller
{
    public function checkAbilities()
    {
        $user = Auth::user();
        $roles = $user->getRoles();
        $abilities = $user->getAbilities();

        return response()->json([$abilities,$roles]);
    }
    public function checkMail(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $request->user_id ?? null ,
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
            'phone' => $country ? $country->phonecode . $request->phone : $request->phone 
        ]);

        // Assign roles based on user type
        if ($request->type == "1") {
            $user->assign('student');
        } elseif ($request->type == "2") {
            $user->assign('tutor');
        } elseif ($request->type == "3") {
            $user->assign('parent');
        }

        // Create a login session log
        $token = $user->createToken('main')->plainTextToken;
        $user->remember_token = $token;
         
        $log = new LoginSessionLog();
        $log->user_id = $user->id;
        $log->session = $token;
        $log->ipaddress = $request->ip();
        $log->browser = $request->userAgent();
        $log->os = 'register';
        $log->save();

        // Upload avatar if provided
        if (!empty($request->avatar)) {
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
                'token' => $token
            ]
        ]);
    }

    public function login(Request $request)
    {
                
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required',
            'remember' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $data = $request->only(['email','password']);
        $remember = $request->remember ?? false;

        if(!Auth::attempt($data, $remember)) {
            return response([
                'success' => false,
                'message' => 'The Login info is not correct',
                'msg-code' => '111',
            ], 500);
        }

        $user = Auth::user();

        if(($request->type == 0 && $user->type != 0) || ($request->type != 0 && !in_array($user->type, [1, 2])) ){

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
        //$user->avatar = '/src/assets/media/avatars/300-1.jpg';
        $token = $user->createToken('main')->plainTextToken;
        //$user->api_token=$token;

        $user->remember_token = $token;
        $user->fcm = $request->fcm ?? $user->fcm;
        $user->save();

        $notifyData = new GeneralNotification(1, 0);
        Notification::send($user, $notifyData);

        $log = new LoginSessionLog();
        $log->user_id = $user->id;
        $log->session = $token;
        $log->ipaddress = $request->ip();
        $log->browser = $request->userAgent();
        $log->os = 'login';
        $log->save();

        //{"id":2,"first_name":"German","last_name":"Stark","email":"admin@demo.com","email_verified_at":"2022-07-14T11:37:39.000000Z","created_at":"2022-07-14T11:37:39.000000Z","updated_at":"2022-07-14T11:37:39.000000Z","api_token":"$2y$10$lzYGs3CVjxdlR2ERLfZOyezaXM8qXLGd5fHEkjoBmDxznEl.CvAdC"}

        //return response($user);

        $item=null;
    
        foreach($user->roles as $index=>$role) {
            if(Bouncer::is($user)->a($role->name)) {
                $item = Bouncer::role()->find($role->id);
            }
            
            $menus=Menu::parents()->get();
            foreach($menus as $menu){
                if($menu->type==''){
                    $menu->childrens = $menu->childes()->get();
                    foreach($menu->childrens as $submenu){
                        $submenu->childrens = $submenu->childes()->get();
                        foreach($submenu->childrens as $subnav){
                            $subnav->checked = false;
                            if(isset($item)) $subnav->checked = $item->can($subnav->name, $subnav->type);
                        }
                    }
                }else{
                    $menu->childrens = $menu->childes()->get();
                    foreach($menu->childrens as $subnav){
                        $subnav->checked = false;
                        if(isset($item)) $subnav->checked = $item->can($subnav->name, $subnav->type);
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
                'token' => $token
            ]
        ]);
    }

    public function logout()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }

    public function changePassword(Request $request)
    {
        # Validation
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

        #Match The Old Password
        if(!Hash::check($request->old_password, auth()->user()->password)) {
            return response([
                'success' => false,
                'message' => 'Old Password Doesn\'t match!',
                'msg-code' => '111',
            ], 200);
        }


        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notifyData = new GeneralNotification(3, 0);
        Notification::send($user, $notifyData);

        return response([
            'success' => true
        ]);
    }

    public function changeEmail(Request $request)
    {
        # Validation
        $request->validate([
            'email' => 'required | email | unique:users,email,' . auth()->user()->id,
        ]);
        $user = Auth::user();

        #Update the new Email
        User::whereId(auth()->user()->id)->update([
            'email' => $request->email
        ]);

        $notifyData = new GeneralNotification(4, 0);
        Notification::send($user, $notifyData);

        return response([
            'success' => true
        ]);
    }

    public function changeName(Request $request)
    {
        # Validation
        $request->validate([
            'name' => 'required',
        ]);

        $user = Auth::user();

        #Update the new Name
        User::whereId(auth()->user()->id)->update([
            'name' => $request->name
        ]);

        $notifyData = new GeneralNotification(5, 0);
        Notification::send($user, $notifyData);

        return response([
            'success' => true
        ]);
    }

    public function changeAvatar(Request $request)
    {
        $data = $request->only(['photo']);//,'password'
        $user = Auth::user();

        $imageUrl = uploadImg($request->photo);
        $user->avatar = $imageUrl;
        $user->save();

        $notifyData = new GeneralNotification(6, 0);
        Notification::send($user, $notifyData);

        return response([
            'url' => url('/') . $user->avatar
        ]);
    }

    public function profile(Request $request)
    {
        /** @var User $user */
        $user = $request->user_id ? User::find($request->user_id) : Auth::user();
        //======================================================
        $user->abouts =  $user->abouts()->get();
        if($user->abouts) {
            foreach($user->abouts as $about) {
                $about->country;
                $about->level;
                $about->language;
                $about->subject;
                $about->experience;
                $about->situation;
            }
        }
        //======================================================
        $user->availabilities =  $user->availabilities()->get();
        if($user->availabilities) {
            foreach($user->availabilities as $availability) {
                $availability->timezone;
                $availability->day;
            }
        }
        //======================================================
        $user->certifications =  $user->certifications()->get();
        if($user->certifications) {
            foreach($user->certifications as $certification) {
                $certification->subject;
            }
        }
        //======================================================
        $user->descriptions =  $user->descriptions()->get();
        //======================================================
        $user->educations =  $user->educations()->get();
        if($user->educations) {
            foreach($user->educations as $education) {
                $education->degreeType;
            }
        }
        //======================================================
        $user->hourlyPrices =  $user->hourlyPrices()->get();
        //======================================================
        $user->languages =  $user->languages()->get();
        if($user->languages) {
            foreach($user->languages as $language) {
                $language->language;
                $language->level;
            }
        }
        //======================================================
        $user->videos =  $user->videos()->get();
        //======================================================

        return response([
            'success' => true,
            'message' => 'Profile retrieved Successfully',
            'result' => $user
        ]);
    }

    public function updateProfile(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required_without_all:first_name,last_name|string',
            'first_name' => 'required_without:name|string',
            'last_name' => 'required_without:name|string',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date_format:Y-m-d',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $name = trim($request->name);

        if(!empty($name)){

            $nameParts = explode(" ", $name);
            $first_name = $nameParts[0];
            $last_name = implode(" ", array_slice($nameParts, 1));

        }else{
            $name = $request->first_name . ' ' . $request->last_name;
            $first_name = $request->first_name;
            $last_name = $request->last_name;
        }

        $user = Auth::user();
        $user->name = $name;
        $user->email = $request->email;
        $user->save();

        #Update userAbout
        $age = $request->date_of_birth ? date('Y') - date('Y', strtotime($request->date_of_birth)) : 0;

        if($user_about = $user->abouts()->first()){

            $user_about->first_name = $first_name;
            $user_about->last_name = $last_name;
            $user_about->phone = $request->phone;
            $user_about->date_of_birth = $request->date_of_birth ?? $user_about->date_of_birth;
            $user_about->age = $age;
            $user_about->save();

        }else{

            $user->abouts()->create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'phone' => $request->phone,
                'date_of_birth' =>$request->date_of_birth,
                'age' => $age
            ]);

        }

        // Upload avatar if provided
        if (!empty($request->avatar)) {
            $user->avatar = uploadFile($request->avatar, ['jpg', 'jpeg', 'png'], 'users');
            $user->save();
        }

                    
        return response([
            'success' => true,
            'message' => 'User Profile Updated Successfully',
            'result' => [
                'user' => $user
            ]
        ], 200);
    }
    public function forgotPassword(Request $request)
    {

        $request->validate(['email' => 'required|email']);
        $checkEmail = User::where('email', $request->email)->first();
        if(!$checkEmail) {
            return response([
                    'success' => false,
                    'message' => 'email-not-exist',
                    'msg-code' => '111'
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
                'msg-code' => '222'
            ], 200);
        }


        return response([
                'success' =>  $status === Password::RESET_LINK_SENT ? true : false,
                'result' => __($status)
        ]);

    }

    public function resetPassword($token)
    {

        return response([
            'success' => true,
            'token' => $token
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
                    'password' => Hash::make($password)
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
                        'msg-code' => '222'
                    ], 200);
                }
            }
        );

        $success =  ($status === Password::PASSWORD_RESET) ? true : false;
        return response([
                'success' => $success,
                'result' => __($status)
        ], 200);

    }

        
    public function socialLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'provider_name' => 'required',
            'provider_id' => 'required',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $user = User::where('email', $request->email)->first();

        if($user){

            $token = $user->createToken('main')->plainTextToken;

            $user->remember_token = $token;
            $user->fcm = $request->fcm ?? $user->fcm;
            $user->email_verified_at = $request->provider_name != 'apple' ? ($user->email_verified_at ??  now() ) : null;
            $user->save();

            $log = new LoginSessionLog();
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
                    'token' => $token
                ]
            ]);
        }else{
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->email),
                'provider_name' => $request->provider_name,
                'provider_id' => $request->provider_id,
                'type' => $request->type,
                'fcm'=>$request->fcm,
                'email_verified_at' => $request->provider_name != 'apple' ? now() : null
            ]);

            // Assign roles based on user type
            if ($request->type == "1") {
                $user->assign('student');
            } elseif ($request->type == "2") {
                $user->assign('tutor');
            } elseif ($request->type == "3") {
                $user->assign('parent');
            }

            // Create a login session log
            $token = $user->createToken('main')->plainTextToken;
            $log = new LoginSessionLog();
            $log->user_id = $user->id;
            $log->session = $token;
            $log->ipaddress = $request->ip();
            $log->browser = $request->userAgent();
            $log->os = 'register';
            $log->save();

            // Upload avatar if provided
            if (!empty($request->avatar)) {
                // Extract the filename from the avatar URL
                $filename = basename($request->avatar);

                // Download the avatar image
                $avatarContents = file_get_contents($request->avatar);

                // Specify the directory to save the avatar image
                $directory = 'users/';

                // Save the avatar image to the specified directory
                $tempPath  = $directory . $filename;
                
                file_put_contents(public_path($tempPath), $avatarContents);

                $datePath = date('Y').'/'. date('m').'/'. date('d');
                
                $destinationDirectory = '/home/jinnedu/public_html/jinntest.jinnedu.com/server/' . $directory . $datePath;

                $moved = move_uploaded_file($tempPath, $destinationDirectory . '/' . $filename);

                if ($moved) {
                    unlink(public_path($tempPath));
                }

                $user->avatar = $directory . $filename;
                $user->save();
            }

            return response([
                'success' => true,
                'message' => 'Register Successfully',
                'result' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]);
        }
    }

    public function sendCode(Request $request) {

        $user = auth()->user();

        $code = create_random_code(4,[
            'upper_case' => false,
            'lower_case' => false,
            'number' => true,
            'special_character' => false,
        ]);

        $user->code = $code;
        $user->save();

        $data = array('user'=>$user);

        Mail::send('emails.verify_email', $data, function($mail) use ($user){
            $mail->to($user->email,$user->name)
                    ->subject('Verify Email');
        });

        return response([
                        'success' => true,
                        'message' => 'code-send-successfully'
                ] , 200);
    }

    public function verifyEmail(Request $request) {
               
        $validator = Validator::make($request->all(), [
            'code' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'msg-code' => 'validation-error',
            ], 200);
        }

        $user = auth()->user();

        if($user->code !== $request->code){
            return response([
                        'success' => false,
                        'message' => 'Wrong Code'
                ] , 200);
        }

        $user->markEmailAsVerified();
        $user->code = null;
        $user->save();
        
        return response([
                        'success' => true,
                        'message' => 'email-verified-successfully',
                        'result' => [
                            'user' => $user
                        ]
                ] , 200);
    }
    
    public function deleteAccount(Request $request) {
        
        $user = auth()->user();
        
        if(!$user) {
            return response([
                'success' => false,
                'message' => 'User Not Found',
                'msg-code' => '111',
            ], 200);
        }
        
        $user->delete();
        
        return response([
                        'success' => true,
                        'message' => 'user-deleted-successfully'
                ] , 200);
        
    }

}