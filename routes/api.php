<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::prefix('v1')
->middleware('auth:sanctum')->group(function (){
  
  Route::get('/user', function(Request $request){
    return $request->user();
  });
  Route::get('/users', [AuthController::class, 'index']);
  Route::get('/profile', function (Request $request) {
    return response()->json($request->user());
  });

  Route::get('/products', [ProductController::class, 'index' ]);
  Route::post('/products', [ProductController::class, 'store' ]);
  Route::get('/product/{id}', [ProductController::class, 'show' ]);
  Route::put('/product/{id}', [ProductController::class, 'update' ]);
  Route::delete('/product/{id}', [ProductController::class, 'destroy' ]);

});



