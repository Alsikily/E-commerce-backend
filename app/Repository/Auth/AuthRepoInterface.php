<?php

namespace App\Repository\Auth;

interface AuthRepoInterface {

    public function login($request);
    public function register($request);
    public function sendVerificationCode();
    public function verifyEmail($request);
    public function logout();
    // public function refresh();

}