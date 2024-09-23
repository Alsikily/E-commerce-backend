<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Images implements ValidationRule {

    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (!is_array($value) || count($value) == 0 || count($value) > 15) {
            $fail('The :attribute must contain 0 - 15 image');
        }
    }

}
