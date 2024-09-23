<?php

namespace App\Http\Controllers;

// Requests
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyEmailRequest;

// Interfaces
use App\Repository\Auth\AuthRepoInterface;

// Models
use App\Models\User;

class AuthController extends Controller {

    private $AuthRepo;

    public function __construct(AuthRepoInterface $AuthRepo) {

        $this -> AuthRepo = $AuthRepo;

    }

    public function login(LoginRequest $request) {

        return $this -> AuthRepo -> login($request);

    }

    public function register(RegisterRequest $request) {
        return $this -> AuthRepo -> register($request);
    }

    public function sendVerificationCode() {
        return $this -> AuthRepo -> sendVerificationCode();
    }

    public function verifyEmail(VerifyEmailRequest $request) {
        return $this -> AuthRepo -> verifyEmail($request);
    }

    public function logout() {
        return $this -> AuthRepo -> logout();
    }

    // public function refresh() {

    //     return $this -> AuthRepo -> refresh();

    // }

}
