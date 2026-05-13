<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::apiResource('perfume', \App\Http\Controllers\Api\PerfumeController::class)->only(['index', 'show']);
Route::apiResource('brand', \App\Http\Controllers\Api\BrandController::class)->only(['index', 'show']);
Route::apiResource('type', \App\Http\Controllers\Api\TypeController::class)->only(['index', 'show']);
Route::apiResource('season', \App\Http\Controllers\Api\SeasonController::class)->only(['index', 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('perfume', \App\Http\Controllers\Api\PerfumeController::class)->except(['index', 'show']);
    Route::apiResource('brand', \App\Http\Controllers\Api\BrandController::class)->except(['index', 'show']);
    Route::apiResource('cart', \App\Http\Controllers\Api\CartController::class);
    Route::apiResource('order', \App\Http\Controllers\Api\OrderController::class);
    Route::apiResource('transaction', \App\Http\Controllers\Api\TransactionController::class);
    Route::apiResource('purchase', \App\Http\Controllers\Api\PurchaseController::class);
    Route::apiResource('profile', \App\Http\Controllers\Api\ProfileController::class);
    Route::apiResource('chat', \App\Http\Controllers\Api\ChatController::class);
    Route::apiResource('type', \App\Http\Controllers\Api\TypeController::class)->except(['index', 'show']);
    Route::apiResource('season', \App\Http\Controllers\Api\SeasonController::class)->except(['index', 'show']);
});
