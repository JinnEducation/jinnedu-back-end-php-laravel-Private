<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PhoneNumber implements Rule
{
    protected $countryId;

    public function __construct($countryId)
    {
        $this->countryId = $countryId;
    }

    public function passes($attribute, $value)
    {
        $country = DB::table('countries')->where('id', $this->countryId)->first();

        if (!$country || !isset($country->phonecode) || !isset($country->phone_length)) {
            return false;
        }

        // $phoneCode = preg_quote($country->phonecode, '/');
        // $phoneLength = $country->phone_length;
        // $pattern = "/^{$phoneCode}\d{{$phoneLength}}$/"; 

        // return preg_match($pattern, $value);
        return strlen($value) == $country->phone_length;
    }

    public function message()
    {
        return 'The phone number format is invalid for the selected country.';
    }
}
