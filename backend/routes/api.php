<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\DiscoveryPerfumeController;
use App\Http\Controllers\Api\JournalController;
use App\Http\Controllers\Api\MoodController;
use App\Http\Controllers\Api\PerfumeController;
use App\Http\Controllers\Api\WardrobeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/almanac', fn () => response()->json(
    \App\Models\AlmanacChapter::orderBy('sort_order')
        ->with(['entries' => fn ($q) => $q->orderBy('sort_order')])
        ->get()
));

// Storefront filter facets (brand list + note vocabulary) — declared before
// the resource so "perfume-facets" can't be mistaken for a {perfume} id.
Route::get('/perfume-facets', [PerfumeController::class, 'facets']);

Route::apiResource('perfume', PerfumeController::class)->only(['index', 'show']);
Route::apiResource('brand', BrandController::class)->only(['index', 'show']);

// Discovery catalogue — read-only, populated by `php artisan discovery:import`.
Route::apiResource('discovery', DiscoveryPerfumeController::class)->only(['index', 'show']);

// Authenticated (sanctum bearer token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $request) => $request->user());
    Route::patch('/user', [AuthController::class, 'updateProfile']);

    Route::apiResource('perfume', PerfumeController::class)->except(['index', 'show']);
    Route::apiResource('brand', BrandController::class)->except(['index', 'show']);

    Route::apiResource('wardrobe', WardrobeController::class)->only(['index', 'store', 'destroy']);
    Route::apiResource('journal', JournalController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/mood/today', [MoodController::class, 'show']);
    Route::post('/mood/today', [MoodController::class, 'store']);

    // Admin-only
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/stats', [AdminController::class, 'stats']);
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/users', [AdminController::class, 'users']);
        Route::patch('/users/{user}', [AdminController::class, 'updateUser']);

        // Admin perfumes (discovery curation)
        Route::get('/perfumes', [AdminController::class, 'perfumeIndex']);
        Route::get('/perfumes/{discoveryPerfume}', [AdminController::class, 'perfumeShow']);
        Route::patch('/perfumes/{discoveryPerfume}', [AdminController::class, 'updatePerfume']);

        // Admin almanac
        Route::get('/almanac', [AdminController::class, 'almanacChapters']);
        Route::post('/almanac', [AdminController::class, 'storeChapter']);
        Route::patch('/almanac/{chapter}', [AdminController::class, 'updateChapter']);
        Route::delete('/almanac/{chapter}', [AdminController::class, 'destroyChapter']);
        Route::post('/almanac/{chapter}/entries', [AdminController::class, 'storeEntry']);
        Route::patch('/almanac/entries/{entry}', [AdminController::class, 'updateEntry']);
        Route::delete('/almanac/entries/{entry}', [AdminController::class, 'destroyEntry']);
    });
});
