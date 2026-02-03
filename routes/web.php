<?php

use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) return redirect()->route('dashboard');
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Placeholder routes for navigation
    Route::get('/surat-cuti', function () {
        return view('dashboard');
    })->name('surat-cuti');

    Route::get('/surat-tugas', function () {
        return view('dashboard');
    })->name('surat-tugas');

    Route::get('/riwayat-surat', function () {
        return view('dashboard');
    })->name('riwayat-surat');

    Route::resource('pegawai', PegawaiController::class);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
