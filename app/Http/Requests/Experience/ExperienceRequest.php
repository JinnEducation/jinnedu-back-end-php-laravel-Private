<?php

namespace App\Http\Requests\Experience;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class ExperienceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:experiences,name,'.$this->id,
        ];
    }

    public function messages()
    {
        return [   
            'name.required' => "name-required",
            'name.unique' => "name-unique",
        ];
    }
}
