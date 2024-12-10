<?php

namespace App\Rules;

use App\Models\City;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CityValidationRule implements ValidationRule
{

    public $state_id;

    public function __construct($state_id)
    {
        $this->state_id = $state_id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (City::where('state_id', $this->state_id)->where('id', $value)->get()->count() == 0) {
            $fail('City Selected Is Invalid');
        }
    }
}
