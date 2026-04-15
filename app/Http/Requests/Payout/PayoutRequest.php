<?php

namespace App\Http\Requests\Payout;

use Illuminate\Foundation\Http\FormRequest;

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
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', 'in:bank,paypal'],
            'bank_name' => ['required_if:method,bank'],
            'bank_account_name' => ['required_if:method,bank'],
            'account_no' => ['required_if:method,bank'],
            'iban' => ['required_if:method,bank'],
            'swift_code' => ['required_if:method,bank'],
            'country' => ['required_if:method,bank'],
            'paypal_account' => ['required_if:method,paypal'],
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.gt' => 'The amount must be greater than zero.',
            'method.required' => 'The method field is required.',
            'method.in' => 'The selected method is invalid.',
            'bank_name.required_if' => 'The bank name field is required when payment method is set to bank.',
            'bank_account_name.required_if' => 'The bank account name field is required when payment method is set to bank.',
            'account_no.required_if' => 'The account number field is required when payment method is set to bank.',
            'iban.required_if' => 'The IBAN field is required when payment method is set to bank.',
            'swift_code.required_if' => 'The SWIFT code field is required when payment method is set to bank.',
            'country.required_if' => 'The country field is required when payment method is set to bank.',
            'paypal_account.required_if' => 'The PayPal account field is required when payment mode is set to PayPal.',
        ];
    }
}
