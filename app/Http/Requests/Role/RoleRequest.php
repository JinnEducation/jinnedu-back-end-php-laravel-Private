<?php

namespace App\Http\Requests\Role;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Password;

class RoleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name,'.$this->id
        ];
    }

    public function messages()
    {
        return [   
            'name.required' => 'required',
            'name.uniqe' => 'unique'

        ];
    }
}
