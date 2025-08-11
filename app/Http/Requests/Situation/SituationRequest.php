<?php

namespace App\Http\Requests\Situation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class SituationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:situations,name,'.$this->id,
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
