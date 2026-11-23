<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/tentang', [HomeController::class, 'tentang']);
Route::get('/kontak', [HomeController::class, 'kontak']);
Route::resource('loans', LoanController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    })->name('dashboard');

    Route::get('books/search', [BookController::class, 'search'])->name('books.search');
    Route::get('books/cek-kode', [BookController::class, 'cekKode'])->name('books.cek-kode');
    Route::get('members/search', [MemberController::class, 'search'])->name('members.search');
    Route::get('members/cek-nis', [MemberController::class, 'cekNis'])->name('members.cek-nis');

    // Route Resource Utama
    Route::resource('books', BookController::class);
    Route::resource('members', MemberController::class);
});

require __DIR__.'/auth.php';