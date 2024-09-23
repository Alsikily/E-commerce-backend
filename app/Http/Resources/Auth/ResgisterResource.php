<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResgisterResource extends JsonResource {

    public function toArray(Request $request): array {
        return [
            'status' => 'success',
            'message' => 'تم إنشاء الحساب بنجاح',
            'user' => [
                'id' => $this -> resource['user']['id'],
                'email' => $this -> resource['user']['email'],
                'name' => $this -> resource['user']['name']
            ],
            'authorisation' => [
                'token' => $this -> resource['authorisation']['token'],
                'type' => 'bearer',
            ]
        ];
    }
}
