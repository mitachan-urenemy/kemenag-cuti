<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->string('jenis_cuti'); // e.g., Tahunan, Sakit, Alasan Penting
            $table->text('alasan_cuti');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('lama_cuti');
            $table->text('alamat_selama_cuti');
            $table->string('status_persetujuan')->default('pending'); // pending, disetujui_atasan, disetujui_pejabat, ditolak
            $table->foreignId('atasan_id')->nullable()->constrained('pegawais')->onDelete('set null');
            $table->foreignId('pejabat_id')->nullable()->constrained('pegawais')->onDelete('set null');
            $table->foreignId('surat_id')->nullable()->constrained('surats')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};