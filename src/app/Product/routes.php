<?php

use App\Product\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class)->only(['index', 'show']);

Route::middleware(['auth:api', 'role:admin'])->group(function (): void {
    Route::apiResource('products', ProductController::class)->only(['store', 'update', 'destroy']);
});
