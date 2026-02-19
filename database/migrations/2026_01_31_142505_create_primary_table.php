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
        // Tabel utama untuk semua jenis surat (Cuti dan Tugas)
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->enum('jenis_surat', ['cuti', 'tugas']);
            $table->date('tanggal_surat');
            $table->string('perihal');

            // Manual input pegawai & kepala pegawai
            $table->string('nama_lengkap_pegawai')->nullable();
            $table->string('nip_pegawai', 25)->nullable();
            $table->string('pangkat_golongan_pegawai')->nullable();
            $table->string('jabatan_pegawai')->nullable();
            $table->string('bidang_seksi_pegawai')->nullable();
            $table->enum('status_pegawai', ['PNS', 'PPPK'])->default('PNS');

            $table->string('nama_lengkap_kepala_pegawai')->nullable();
            $table->string('nip_kepala_pegawai', 25)->nullable();
            $table->string('jabatan_kepala_pegawai')->nullable();

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
        Schema::dropIfExists('kop_surats');
    }
};
