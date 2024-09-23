<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {

        return [
            "verifyEmailCode" => "required|numeric|digits:4",
        ];

    }

    protected function failedValidation(Validator $validator) {

        throw new HttpResponseException(response() -> json([
            'status' => 'error',
            'messages' => $validator -> errors()
        ]));

    }

}
