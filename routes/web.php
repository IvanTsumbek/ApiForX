<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Post\IndexController;
use App\Http\Controllers\Post\StoreController;
use App\Http\Controllers\Post\CreateController;

Route::get('/', function () {return view('welcome');});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::get('/redirect', [AuthController::class, 'redirect'])->name('redirect');
Route::get('/callback', [AuthController::class, 'callback'])->name('callback');
Route::get('/posts', [IndexController::class, 'index'])->name('index');
Route::get('/post/create', [CreateController::class, 'create'])->name('create');
Route::post('/post/store', [StoreController::class, 'store'])->name('store');

});






require __DIR__.'/auth.php';
