<?php

namespace App\Providers\Custom;

use Illuminate\Support\ServiceProvider;

// Repo
use App\Repository\Product\CustomerProductRepoInterface;
use App\Repository\Product\CustomerProductRepo;

class CustomerProductProvider extends ServiceProvider {

    public function register(): void {
        $this -> app -> bind(CustomerProductRepoInterface::class, CustomerProductRepo::class);
    }

    public function boot(): void {

    }

}
