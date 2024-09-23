<?php

namespace App\Repository\Auth;

// Classes
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

// Interface
use App\Repository\Auth\AuthRepoInterface;

// Mails
use App\Mail\VerifyMail;

// Jobs
use App\Jobs\ExpireVerificationCode;

// Models
use App\Models\User;

// Resources
use App\Http\Resources\Auth\ResgisterResource;
use App\Http\Resources\Auth\LoginResource;

class AuthRepo implements AuthRepoInterface {

    public function login($request) {

        $credentials = $request -> only("email", "password");
        $token = Auth::attempt($credentials);

        if (!$token) {

            $errors = [
                "data" => ['بيانات غير صحيحة']
            ];

            return response()->json([
                'data' => [
                    'status' => 'error',
                    'messages' => $errors,
                ]
            ], 401);

        }

        $user = Auth::user();

        if ($user -> email_verified_at == null) {
            $user -> generateCode();
        }

        return new LoginResource([
            'status' => 'success',
            'message' => 'تم تسجيل الدخول بنجاح',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }

    public function sendVerificationCode() {

        $user = Auth::user();

        DB::beginTransaction();

        try {

            $user -> generateCode();
            Mail::to($user -> email) -> send(new VerifyMail($user -> verification_code));

            DB::commit();

            return response() -> json([
                'status' => 'success',
                'message' => 'Verification code has been sent'
            ]);

        } catch (\Throwable $th) {

            DB::rollBack();

            return response() -> json([
                'status' => 'error',
                'error' => $th
            ]);

        }

    }

    public function register($request) {

        $request['password'] = Hash::make($request -> password);
        $credentials = $request -> only("name", "email", "password");

        $user = User::create($credentials);
        $token = Auth::login($user);

        // $this -> sendVerificationCode($user);

        return new ResgisterResource([
            'status' => 'success',
            'message' => 'تم إنشاء الحساب بنجاح',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);

    }

    public function verifyEmail($request) {

        $user = Auth::user();
        $input_verfication_code = $request -> input('verifyEmailCode');

        if ($user -> verification_code === $input_verfication_code && now() < $user -> expire_at) {

            $user -> resetCode();
            return response() -> json([
                'status' => 'success',
                'message' => 'email has been verified successfully'
            ], 200);

        }

        return response() -> json([
            'status' => 'error',
            'messages' => ['verifyEmailCode' => ['Invalid verification code']]
        ]);

    }

    public function logout() {

        Auth::logout();
        return response() -> json([
            'status' => 'success',
        ], 200);

    }

    // public function refresh() {

    //     return response()->json([
    //         'status' => 'success',
    //         'user' => Auth::user(),
    //         'authorisation' => [
    //             'token' => Auth::refresh(),
    //             'type' => 'bearer',
    //         ]
    //     ]);

    // }

}