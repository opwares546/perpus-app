<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\DashboardController; // Tambahkan baris ini

Route::get('/', [HomeController::class, 'index']);
Route::get('/tentang', [HomeController::class, 'tentang']);
Route::get('/kontak', [HomeController::class, 'kontak']);

Route::middleware(['auth'])->group(function () {
    // Route Dashboard diarahkan ke DashboardController
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    Route::get('books/search', [BookController::class, 'search'])->name('books.search');
    Route::get('books/cek-kode', [BookController::class, 'cekKode'])->name('books.cek-kode');
    Route::get('members/search', [MemberController::class, 'search'])->name('members.search');
    Route::get('members/cek-nis', [MemberController::class, 'cekNis'])->name('members.cek-nis');

    Route::resource('books', BookController::class);
    Route::resource('members', MemberController::class);
    Route::resource('loans', LoanController::class)->only(['index', 'create', 'store']);
    
    // Route Pengembalian (PATCH)
    Route::patch('loans/{loan}/kembalikan', [LoanController::class, 'kembalikan'])
        ->name('loans.kembalikan');
});

require __DIR__.'/auth.php';