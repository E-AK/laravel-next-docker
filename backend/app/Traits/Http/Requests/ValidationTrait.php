<?php

namespace App\Traits\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

trait ValidationTrait {
    protected function failedValidation(Validator $validator): void
    {
        throw throw ValidationException::withMessages($validator->errors()->toArray());
    }
}
