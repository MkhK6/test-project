<?php

use App\Auth\AuthServiceProvider;
use App\Providers\AppServiceProvider;
use App\Product\ProductServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    ProductServiceProvider::class,
];
