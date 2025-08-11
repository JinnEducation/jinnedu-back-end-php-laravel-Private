<?php

namespace App\Http\Requests\Translation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class TranslationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'langid' => 'required',
            'labelid' => 'required',
            'title' => 'required',
        ];
    }

    public function messages()
    {
        return [   
            'langid.required' => "langid-required",
            'labelid.required' => "labelid-required",
            'title.required' => "title-required",
        ];
    }
}
