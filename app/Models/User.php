<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {

    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'expire_at',
        'verification_code'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function generateCode() {

        $this -> timestamps = false;
        $this -> verification_code = rand(1000, 9999);
        $this -> expire_at = now() -> addMinute(15);
        $this -> save();

    }

    public function resetCode() {

        $this -> timestamps = false;
        $this -> verification_code = null;
        $this -> expire_at = null;
        $this -> save();

    }

}