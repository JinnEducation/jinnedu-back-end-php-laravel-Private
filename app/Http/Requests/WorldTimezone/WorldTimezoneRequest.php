<?php

namespace App\Http\Requests\WorldTimezone;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class WorldTimezoneRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:world_timezones,name,'.$this->id,
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
