<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users,email,'.$this->id,
            /*'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed'
            ]*/
        ];
    }

    public function messages()
    {
        return [   
            'name.required' => "fullname-is-required",
            'email.required' => "email-i-required",
            'email.email' => "not-correct-email-format",
            'email.unique' => "email-duplicated",
            /*'password.required' => "Password is Required",
            'password.min' => "Min password length is 8 characters",
            'password.regex' => "password must contains at leat one uppercaser char, one lowercase char, one spetial char and one digit number",
            'password.confirmed' => "Password and confirmed password must equals",*/
        ];
    }
}
