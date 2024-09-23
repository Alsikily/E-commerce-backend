<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource {

    public function toArray(Request $request): array {
        return [
            'status' => $this -> resource['status'],
            'message' => $this -> resource['message'],
            'user' => [
                'id' => $this -> resource['user']['id'],
                'email' => $this -> resource['user']['email'],
                'name' => $this -> resource['user']['name'],
                'email_verified_at' => $this -> resource['user']['email_verified_at']
            ],
            'authorisation' => [
                'token' => $this -> resource['authorisation']['token'],
                'type' => $this -> resource['authorisation']['type'],
            ]
        ];
    }
}
