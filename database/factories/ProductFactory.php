<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ProductFactory extends Factory {

    public function definition(): array {
        return [
            'en_name' => 'Product',
            'email' => 'hassan@gmail.com',
            'email_verified_at' => now(),
            'verification_code' => '701170',
            'password' => bcrypt('01230123'),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
