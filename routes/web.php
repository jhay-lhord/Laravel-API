<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('api/v1/products', [ProductController::class, 'index' ]);
// Route::post('/login', [AuthController::class, 'login']);
