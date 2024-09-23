<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Seeders
use Database\Seeders\Categories;

// Factories
use Database\Factories\UserFactory;
use Database\Factories\ProductFactory;

class DatabaseSeeder extends Seeder {

    public function run(): void {

        $this -> call([
            Categories::class,
        ]);

    }
}
