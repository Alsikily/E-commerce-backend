<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Models
use App\Models\Category;

class Categories extends Seeder {

    public function run(): void {

        $categories = [];

        for($x = 1; $x <= 10; $x++) {

            $categories[] = [
                'en_cat_name' => 'cat ' . $x,
                'ar_cat_name' => 'قسم ' . $x
            ];

        }

        Category::insert($categories);

    }

}
