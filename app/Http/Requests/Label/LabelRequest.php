<?php

namespace App\Http\Requests\Label;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class LabelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',//'|unique:labels,name,'.$this->id,
            'file' => 'required|string',
        ];
    }

    public function messages()
    {
        return [   
            'name.required' => "name-required",
            //'name.unique' => "name-unique",

            'file.required' => "file-required",
        ];
    }
}
