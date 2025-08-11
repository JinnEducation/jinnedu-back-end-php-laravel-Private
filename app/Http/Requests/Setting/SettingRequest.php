<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class SettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:settings,name,'.$this->id
        ];
    }

    public function messages()
    {
        return [   
            'name.required' => "name-is-required",
            'name.unique' => "name-duplicated"
        ];
    }
}
