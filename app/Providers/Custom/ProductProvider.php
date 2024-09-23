<?php

namespace App\Providers\Custom;

use Illuminate\Support\ServiceProvider;

// Repo
use App\Repository\Product\ProductRepoInterface;
use App\Repository\Product\ProductRepo;

class ProductProvider extends ServiceProvider {

    public function register(): void {
        $this -> app -> bind(ProductRepoInterface::class, ProductRepo::class);
    }

    public function boot(): void {

    }

}
