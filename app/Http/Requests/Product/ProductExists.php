<?php

namespace App\Http\Requests\Product;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ProductExists extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'productID' => 'requried|exists:products,id',
        ];
    }

    public function validationData() {
        return array_merge($this->route()->parameters(), $this->all());
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
