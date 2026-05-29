<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratCutiController;
use App\Http\Controllers\SuratTugasController;
use App\Http\Controllers\RiwayatSuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check())
        return redirect()->route('dashboard');
    return view('auth.login');
});


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Surat Cuti ──────────────────────────────────────────────────────────
    // Semua role bisa akses; role-based logic di controller
    Route::resource('surat-cuti', SuratCutiController::class)
        ->middleware('role:admin,pegawai,pimpinan');

    // Admin: teruskan ke pimpinan (diajukan > diproses)
    Route::post('/surat-cuti/{surat_cuti}/verifikasi', [SuratCutiController::class, 'verifikasi'])
        ->name('surat-cuti.verifikasi')
        ->middleware('role:admin');

    // Pimpinan: setujui / tolak
    Route::post('/surat-cuti/{surat_cuti}/setujui', [SuratCutiController::class, 'setujui'])
        ->name('surat-cuti.setujui')
        ->middleware('role:pimpinan');
    Route::post('/surat-cuti/{surat_cuti}/tolak', [SuratCutiController::class, 'tolak'])
        ->name('surat-cuti.tolak')
        ->middleware('role:pimpinan');

    // ─── Surat Tugas ─────────────────────────────────────────────────────────
    // Admin buat/edit/hapus; pegawai & pimpinan bisa lihat (index/show)
    Route::resource('surat-tugas', SuratTugasController::class)
        ->parameters(['surat-tugas' => 'surat_tugas'])
        ->middleware('role:admin,pegawai,pimpinan');

    // Pimpinan: setujui / tolak surat tugas
    Route::post('/surat-tugas/{surat_tugas}/setujui', [SuratTugasController::class, 'setujui'])
        ->name('surat-tugas.setujui')
        ->middleware('role:pimpinan');
    Route::post('/surat-tugas/{surat_tugas}/tolak', [SuratTugasController::class, 'tolak'])
        ->name('surat-tugas.tolak')
        ->middleware('role:pimpinan');

    // ─── Riwayat ─────────────────────────────────────────────────────────────
    Route::get('/riwayat-surat', [RiwayatSuratController::class, 'index'])->name('riwayat-surat');

    // ─── Manajemen Pegawai (admin only) ──────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::put('/manajemen-pegawai/status/{id}', [PegawaiController::class, 'status'])->name('manajemen-pegawai.status');
        Route::resource('manajemen-pegawai', PegawaiController::class)
            ->names('manajemen-pegawai')
            ->parameters(['manajemen-pegawai' => 'manajemen_pegawai']);
    });
});

require __DIR__ . '/auth.php';
