<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\KopSurat;
use App\Models\Surat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1. Buat Data Pegawai ---
        $kepala = Pegawai::create([
            'nama_lengkap' => 'Dr. H. Budi Santoso, M.Ag.',
            'nip' => '198001012005011001',
            'jabatan' => 'Kepala Kantor',
            'bidang_seksi' => 'Kantor Kementerian Agama',
            'pangkat_golongan' => 'Pembina Utama Muda (IV/c)',
            'is_kepala' => true,
        ]);

        $staff1 = Pegawai::create([
            'nama_lengkap' => 'Ahmad Riyadi, S.Kom.',
            'nip' => '199002022015022002',
            'jabatan' => 'Pranata Komputer',
            'bidang_seksi' => 'Subbagian Tata Usaha',
            'pangkat_golongan' => 'Penata Muda (III/a)',
            'is_kepala' => false,
        ]);

        $staff2 = Pegawai::create([
            'nama_lengkap' => 'Siti Aminah, S.E.',
            'nip' => '199203032016032003',
            'jabatan' => 'Analis Keuangan',
            'bidang_seksi' => 'Seksi Keuangan',
            'pangkat_golongan' => 'Penata Muda (III/a)',
            'is_kepala' => false,
        ]);

        // --- 2. Buat Data User dan Tautkan ke Pegawai ---
        $adminUser = User::create([
            'pegawai_id' => $kepala->id,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'pegawai_id' => $staff1->id,
            'username' => 'ahmad',
            'email' => 'ahmad.riyadi@kemenag.go.id',
            'password' => Hash::make('password'),
        ]);

        // --- 3. Buat Data Kop Surat ---
        $kopSurat = KopSurat::create([
            'title' => 'KEMENTERIAN AGAMA REPUBLIK INDONESIA',
            'type' => 'KANTOR KEMENTERIAN AGAMA KABUPATEN CONTOH',
            'description' => 'Jl. Pahlawan No. 123, Kota Contoh, Provinsi Kode Pos 12345',
            'alamat_kop' => 'Telp. (021) 1234567, Email: kabcontoh@kemenag.go.id',
            'image_path' => null,
        ]);

        // --- 4. Buat Contoh Surat Cuti ---
        $suratCuti = Surat::create([
            'nomor_surat' => 'B-001/Kk.13/KP.01.1/02/2026',
            'jenis_surat' => 'cuti',
            'tanggal_surat' => '2026-02-05',
            'perihal' => 'Pemberian Cuti Tahunan',
            'kop_surat_id' => $kopSurat->id,
            'penandatangan_id' => $kepala->id,
            'created_by_user_id' => $adminUser->id,
            // Kolom khusus cuti
            'jenis_cuti' => 'tahunan',
            'tanggal_mulai_cuti' => '2026-02-10',
            'tanggal_selesai_cuti' => '2026-02-15',
            'keterangan_cuti' => 'Keperluan keluarga mendesak di luar kota.',
        ]);
        // Tautkan surat dengan pegawai yang bersangkutan
        $suratCuti->pegawais()->attach($staff1->id);

        // --- 5. Buat Contoh Surat Tugas untuk 2 Orang ---
        $suratTugas = Surat::create([
            'nomor_surat' => 'ST-001/Kk.13/KP.01.1/03/2026',
            'jenis_surat' => 'tugas',
            'tanggal_surat' => '2026-02-20',
            'perihal' => 'Perintah Tugas',
            'kop_surat_id' => $kopSurat->id,
            'penandatangan_id' => $kepala->id,
            'created_by_user_id' => $adminUser->id,
            // Kolom khusus tugas
            'dasar_hukum' => 'Surat Undangan Pelatihan No. UND-123/XYZ/2026',
            'tujuan_tugas' => 'Mengikuti Bimbingan Teknis Aplikasi SAKTI di Jakarta',
            'lokasi_tugas' => 'Jakarta',
            'tanggal_mulai_tugas' => '2026-03-01',
            'tanggal_selesai_tugas' => '2026-03-05',
        ]);
        // Tautkan surat dengan DUA pegawai sekaligus
        $suratTugas->pegawais()->attach([$staff1->id, $staff2->id]);
    }
}
