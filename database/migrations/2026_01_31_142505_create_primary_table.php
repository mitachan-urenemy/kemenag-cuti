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
        Schema::create('kop_surats', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type');
            $table->string('description');
            $table->string('alamat_kop');
            $table->string('image_path')->nullable();
            $table->string('image_path2')->nullable();
            $table->timestamps();
        });
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->foreignId('kop_surat_id')->constrained('kop_surats')->onDelete('cascade');
            $table->string('no_surat');
            $table->string('perihal');
            $table->date('tanggal_surat');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kop_surats');
        Schema::dropIfExists('surats');
    }
};
