<?php

namespace App\Http\Requests\Product;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class GetProducts extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            // 'priceFrom' => 'sometimes|nullable|numeric|min:1|max:1000000',
            // 'priceFrom' => 'sometimes|nullable|numeric|min:1|max:1000000',
            // 'discountFrom' => 'sometimes|nullable|numeric|min:1|max:100',
            // 'discountTo' => 'sometimes|nullable|numeric|min:1|max:100',
            // 'status' => 'sometimes|nullable|in:like new,good,fair,poor,damaged,refurbished,parts only',
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
