<?php

namespace App\Http\Requests\Tutor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class TutorSignUpRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|string|unique:users,email',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()//,'confirmed'
            ]
        ];
    }

    public function messages()
    {
        return [   
            'email.required' => "Email is Required",
            'email.email' => "Email should be an correct email format",

            'password.required' => "Password is Required",
            'password.min' => "Min password length is 8 characters",
            'password.regex' => "password must contains at leat one uppercaser char, one lowercase char, one spetial char and one digit number",
            //'password.confirmed' => "Password and confirmed password must equals",
        ];
    }
}
