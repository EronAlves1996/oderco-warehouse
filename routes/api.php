<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::get('/{product:public_id}', [ProductController::class, 'show']);
    Route::put('/{product:public_id', [ProductController::class, 'update']);
    Route::delete('/{product:plubic_id}', [ProductController::class, 'destroy']);
});
