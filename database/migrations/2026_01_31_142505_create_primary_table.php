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

            // Foreign Keys
            $table->foreignId('penandatangan_id')->nullable()->constrained('pegawais')->onDelete('set null');
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('cascade');

            // Kolom Khusus untuk Surat Cuti (nullable)
            $table->enum('jenis_cuti', ['tahunan', 'sakit', 'melahirkan'])->nullable();
            $table->date('tanggal_mulai_cuti')->nullable();
            $table->date('tanggal_selesai_cuti')->nullable();
            $table->text('keterangan_cuti')->nullable();

            // Kolom Khusus untuk Surat Tugas (nullable)
            $table->text('dasar_hukum')->nullable();
            $table->text('tujuan_tugas')->nullable();
            $table->string('lokasi_tugas')->nullable();
            $table->date('tanggal_mulai_tugas')->nullable();
            $table->date('tanggal_selesai_tugas')->nullable();

            // Path untuk menyimpan file surat yang di-generate
            $table->string('file_path')->nullable();

            $table->timestamps();
        });

        // Tabel Pivot untuk relasi many-to-many antara surat dan pegawai
        Schema::create('pegawai_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->foreignId('surat_id')->constrained('surats')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_surat');
        Schema::dropIfExists('surats');
        Schema::dropIfExists('kop_surats');
    }
};
