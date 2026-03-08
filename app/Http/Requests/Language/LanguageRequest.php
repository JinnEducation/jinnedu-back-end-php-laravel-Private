<?php

namespace App\Http\Requests\Language;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class LanguageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:languages,name,'.$this->id,
            'shortname' => 'required|string|unique:languages,shortname,'.$this->id,
        ];
    }

    public function messages()
    {
        return [   
            'name.required' => __("admin.name-required"),
            'name.unique' => __("admin.name-unique"),

            'shortname.required' => __("admin.shortname-required"),
            'shortname.unique' => __("admin.shortname-unique"),
        ];
    }
}
