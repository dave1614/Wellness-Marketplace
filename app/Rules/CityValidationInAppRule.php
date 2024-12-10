<?php

namespace App\Rules;

use App\Models\City;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CityValidationInAppRule implements ValidationRule
{
    public $state;

    public function __construct($state)
    {
        $this->state = $state;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (City::where('state_id', $this->state['id'])->where('id', $value['id'])->get()->count() == 0) {
            $fail('City Selected Is Invalid');
        }
    }
}
