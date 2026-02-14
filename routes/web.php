<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RiwayatSuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) return redirect()->route('dashboard');
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('surat-cuti', \App\Http\Controllers\SuratCutiController::class);


    Route::resource('surat-tugas', \App\Http\Controllers\SuratTugasController::class)->parameters(['surat-tugas' => 'surat_tugas']);


    Route::get('/riwayat-surat', [RiwayatSuratController::class, 'index'])->name('riwayat-surat');

    Route::resource('pegawai', PegawaiController::class);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
