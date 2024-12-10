<?php

namespace App\Rules;

use App\Models\Business;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class BusinessPhoneEditRule implements ValidationRule
{

    public $business;

    public function __construct(Business $business)
    {
        $this->business = $business;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $business = Business::where('phone', $value)->where('id', '!=', $this->business->id)->get();
        if ($business->count() > 0) {
            $fail('This phone number has been taken by another business');
        }
    }
}
