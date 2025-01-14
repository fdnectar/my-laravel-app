<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductPriceRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regex = '/^(\d+|\d+(\.\d{1,2})?|(\.\d{1,2}))$/';
        if(!preg_match($regex, $value)) {
            $fail("The {$attribute} is invalid.");
        }
    }

    public function message()
    {
        return 'The :attribute must be a positive number with up to 2 decimal places.';
    }
}
