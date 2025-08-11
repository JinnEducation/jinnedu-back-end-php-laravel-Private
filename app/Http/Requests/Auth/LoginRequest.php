<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required',
            'remember' => 'boolean'
        ];
    }

    public function messages()
    {
        return [   
            'email.required' => "Email is Required",
            'email.email' => "email should be an correct email format",

            'password.required' => "Password is Required"
        ];
    }
}
