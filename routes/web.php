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

    Route::resource('surat-cuti', \App\Http\Controllers\SuratCutiController::class);
    Route::get('/surat-cuti/{surat}/download', [\App\Http\Controllers\SuratCutiController::class, 'download'])->name('surat-cuti.download');

    Route::resource('surat-tugas', \App\Http\Controllers\SuratTugasController::class);
    Route::get('/surat-tugas/{surat}/download', [\App\Http\Controllers\SuratTugasController::class, 'download'])->name('surat-tugas.download');

    Route::get('/riwayat-surat', function () {
        return view('dashboard');
    })->name('riwayat-surat');

    Route::resource('pegawai', PegawaiController::class);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
