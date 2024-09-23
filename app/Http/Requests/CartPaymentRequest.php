<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CartPaymentRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {

        return [
            "PaymentMethod" => "required|string|in:credit,cash",
            "address"       => "required|string"
        ];

    }

    protected function failedValidation(Validator $validator) {

        throw new HttpResponseException(response() -> json([
            'data' => [
                'status' => 'error',
                'messages' => $validator -> errors()
            ]
        ]));

    }

}
