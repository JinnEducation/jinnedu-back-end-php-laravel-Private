<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png'
        ];
    }

    public function messages()
    {
        return [   
            'first_name.required' => 'First Name is required.',
            'last_name.required' => 'Last Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'avatar.image' => 'Avatar must be an image file.',
            'avatar.mimes' => 'Avatar must be a JPG, JPEG, or PNG file.'
        ];
    }
}
