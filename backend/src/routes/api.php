<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    route::apiResource('cart', CartController::class)->only(['index', 'store', 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'remove']);
    route::apiResource('orders', OrderController::class)->only(['index', 'store']);
    route::get('/orders/{id}', [OrderController::class, 'show']);
    route::put('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    route::put('/orders/{id}/complete', [OrderController::class, 'complete']);
});
