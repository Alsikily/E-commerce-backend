<?php

return [

    App\Providers\AppServiceProvider::class,

    // Custom Providers
    App\Providers\Custom\AuthProvider::class,
    App\Providers\Custom\ProductProvider::class,
    App\Providers\Custom\CustomerProductProvider::class,

];
