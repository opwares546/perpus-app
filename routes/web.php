<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/tentang', [HomeController::class, 'tentang']);
Route::get('/kontak', [HomeController::class, 'kontak']);

Route::middleware(['auth'])->group(function () {
    // Ini rute dashboard yang hilang, kita balikin lagi
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    })->name('dashboard');

    Route::resource('books', BookController::class);
    Route::resource('members', MemberController::class);
});

require __DIR__.'/auth.php';