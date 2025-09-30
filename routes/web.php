<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthController;

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

// Route::get('spreaker/show', [SpreakerShowIndexController::class, 'index'])->middleware('auth')->name('spreaker.show.index');
// Route::get('spreaker/create', [SpreakerShowCreateController::class, 'create'])->middleware('auth')->name('spreaker.show.create');
// Route::post('spreaker/create', [SpreakerShowStoreController::class, 'store'])->middleware('auth')->name('spreaker.show.store');
});






require __DIR__.'/auth.php';
