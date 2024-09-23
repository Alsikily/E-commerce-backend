<?php

namespace App\Http\Requests\Product;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuantity extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'quantity' => 'required|numeric|between:1,65535'
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
