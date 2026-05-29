<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel utama untuk semua jenis surat (Cuti dan Tugas)
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais', 'id')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('pegawais', 'id')->onDelete('set null');
            $table->string('nomor_surat')->unique();
            $table->enum('jenis_surat', ['cuti', 'tugas']);
            $table->date('tanggal_surat');
            $table->string('perihal');

            // Kolom Khusus untuk status
            $table->enum('status', ['draft', 'diajukan', 'diproses', 'disetujui', 'ditolak'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->text('ditolak_alasan')->nullable();


            // Kolom Khusus untuk Surat Cuti (nullable)
            $table->enum('jenis_cuti', ['tahunan', 'sakit', 'melahirkan', 'alasan_penting', 'besar'])->nullable();
            $table->date('tanggal_mulai_cuti')->nullable();
            $table->date('tanggal_selesai_cuti')->nullable();
            $table->text('keterangan_cuti')->nullable();
            $table->text('tembusan')->nullable();

            // Kolom Khusus untuk Surat Tugas (nullable)
            $table->text('dasar_hukum')->nullable();
            $table->text('tujuan_tugas')->nullable();
            $table->string('lokasi_tugas')->nullable();
            $table->date('tanggal_mulai_tugas')->nullable();
            $table->date('tanggal_selesai_tugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surats');
    }
};
