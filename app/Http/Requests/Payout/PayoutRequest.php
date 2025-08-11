<?php

namespace App\Http\Requests\Payout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class PayoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => ['required'],
            'method'   => ['required', 'in:bank,paypal'],
            'bank_name' => ['required_if:method,bank'],
            'account_no' => ['required_if:method,bank'],
            'paypal_account' => ['required_if:method,paypal']    
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'The amount field is required.',
            'method.required' => 'The method field is required.',
            'method.in' => 'The selected method is invalid.',
            'bank_name.required_if' => 'The bank name field is required when payment method is set to bank.',
            'account_no.required_if' => 'The account number field is required when payment method is set to bank.',
            'paypal_account.required_if' => 'The PayPal account field is required when payment mode is set to PayPal.',
        ];
    }
}
