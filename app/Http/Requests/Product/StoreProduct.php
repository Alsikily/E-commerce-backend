<?php

namespace App\Http\Requests\Product;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

// Rules
use App\Rules\Images;

class StoreProduct extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'en_name'           => 'required|string|max:255',
            'ar_name'           => 'required|string|max:255',
            'quantity'          => 'required|numeric|min:1|max:1000000',
            'unit_price'        => 'required|numeric|between:0,100000000.00',
            'discount'          => 'sometimes|nullable|numeric|max:100',
            'en_description'    => 'sometimes|nullable|string|max:2000',
            'ar_description'    => 'sometimes|nullable|string|max:2000',
            'delivery'          => 'required|in:free,paid',
            'delivery_charge'   => 'required_if:delivery,==,paid|numeric|between:0,1000000.00',
            'status'            => 'required|in:like new,good,fair,poor,damaged,refurbished,parts only',
            'cat_id'            => 'required|exists:categories,id',
            'images'            => [
                                    'required',
                                    new Images
                                ],
            'images.*'          => 'image|mimes:jpg,png,jpeg,gif|max:2048'
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
