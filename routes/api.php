<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function (){
  Route::get('/user', function(Request $request){
    return $request->user();
  });

  Route::get('/users', [AuthController::class, 'index']);

  Route::get('/profile', function (Request $request) {
    return response()->json($request->user());
  });

  Route::get('v1/products', [ProductController::class, 'index' ]);
  Route::post('v1/products', [ProductController::class, 'store' ]);
  Route::get('v1/product/{id}', [ProductController::class, 'show' ]);
  Route::put('v1/product/{id}', [ProductController::class, 'update' ]);
  Route::delete('v1/product/{id}', [ProductController::class, 'destroy' ]);

});



