<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('/product', function () {
    Route::post('/', [ProductController::class, 'store']);
});
