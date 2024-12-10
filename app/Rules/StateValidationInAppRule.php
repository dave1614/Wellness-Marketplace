<?php

namespace App\Rules;

use App\Models\State;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StateValidationInAppRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        
        $state_id = $value['id'];
        $state = State::find($state_id);
        if(is_null($state)){
            $fail('State Selected Is Invalid');
        }
    }
}
