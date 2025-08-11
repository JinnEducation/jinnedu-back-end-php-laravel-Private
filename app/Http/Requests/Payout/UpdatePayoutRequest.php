<?php

namespace App\Http\Requests\Payout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class UpdatePayoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status'   => ['required', 'in:R,P'],
            'note'   => ['nullable'] 
        ];
    }

    public function messages()
    {
        return [
            'status.required' => 'The status field is required.',
            'status.in' => 'The selected status is invalid.'
        ];
    }
}
