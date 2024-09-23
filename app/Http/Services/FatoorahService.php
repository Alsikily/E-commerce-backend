<?php

namespace App\Http\Services;

// Classes
use Illuminate\Support\Facades\Http;

class FatoorahService {

    private $base_url;
    private $headers;

    public function __construct() {
        $this -> base_url = env('FATORRAH_BASE_URL');
        $this -> headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('FATORRAH_TOKEN')
        ];
    }

    private function buildRequest($uri, $method, $body = []) {

        if (!$body)
            return false;

        $response = Http::withHeaders($this -> headers) -> $method($this -> base_url . $uri, $body);

        if (!$response -> ok())
            return false;

        return $response;
    }

    public function sendPayment($data) {
        return $this -> buildRequest('v2/SendPayment', 'POST', $data) -> json();
    }

    public function getPaymentStatus($data) {
        return $this -> buildRequest('v2/getPaymentStatus', 'POST', $data) -> json();
    }

}