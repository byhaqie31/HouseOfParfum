<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('main');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Controller Routes
Route::resource('perfume', '\App\Http\Controllers\PerfumeController');
Route::resource('type', '\App\Http\Controllers\TypeController');
Route::resource('season', '\App\Http\Controllers\SeasonController');
Route::resource('brand', '\App\Http\Controllers\BrandController');
Route::resource('cart', '\App\Http\Controllers\CartController');
Route::resource('order', '\App\Http\Controllers\OrderController');
Route::resource('transaction', '\App\Http\Controllers\TransactionController');
Route::resource('purchase', '\App\Http\Controllers\PurchaseController');
Route::resource('profile', '\App\Http\Controllers\ProfileController');
Route::resource('chat', '\App\Http\Controllers\ChatController');


//Pages Routes
Route::get('/main', function () {
    return view('main');
});
Route::get('/contact', function () {
    return view('contact');
});
Route::get('/about', function () {
    return view('about');
});




