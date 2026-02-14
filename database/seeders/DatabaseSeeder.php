<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1. Buat Data Pegawai (Static/Demo) ---
        $kepala = Pegawai::firstOrCreate(
            ['nip' => '198001012005011001'],
            [
                'nama_lengkap' => 'Dr. H. Budi Santoso, M.Ag.',
                'jabatan' => 'Kepala Kantor',
                'bidang_seksi' => 'Kantor Kementerian Agama',
                'pangkat_golongan' => 'Pembina Utama Muda (IV/c)',
                'is_kepala' => true,
            ]
        );

        $staff1 = Pegawai::firstOrCreate(
            ['nip' => '199002022015022002'],
            [
                'nama_lengkap' => 'Ahmad Riyadi, S.Kom.',
                'jabatan' => 'Pranata Komputer',
                'bidang_seksi' => 'Subbagian Tata Usaha',
                'pangkat_golongan' => 'Penata Muda (III/a)',
                'is_kepala' => false,
            ]
        );

        $staff2 = Pegawai::firstOrCreate(
            ['nip' => '199203032016032003'],
            [
                'nama_lengkap' => 'Siti Aminah, S.E.',
                'jabatan' => 'Analis Keuangan',
                'bidang_seksi' => 'Seksi Keuangan',
                'pangkat_golongan' => 'Penata Muda (III/a)',
                'is_kepala' => false,
            ]
        );

        // --- 2. Buat Data User dan Tautkan ke Pegawai ---
        $adminUser = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'pegawai_id' => $kepala->id,
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'ahmad'],
            [
                'pegawai_id' => $staff1->id,
                'email' => 'ahmad.riyadi@kemenag.go.id',
                'password' => Hash::make('password'),
            ]
        );

        // --- 3. Buat Contoh Surat Cuti (Static) ---
        $suratCuti = Surat::firstOrCreate(
            ['nomor_surat' => 'B-001/Kk.13/KP.01.1/02/2026'],
            [
                'jenis_surat' => 'cuti',
                'tanggal_surat' => '2026-02-05',
                'perihal' => 'Pemberian Cuti Tahunan',
                'pegawai_id' => $staff1->id,
                'penandatangan_id' => $kepala->id,
                'created_by_user_id' => $adminUser->id,
                // Kolom khusus cuti
                'jenis_cuti' => 'tahunan',
                'tanggal_mulai_cuti' => '2026-02-10',
                'tanggal_selesai_cuti' => '2026-02-15',
                'keterangan_cuti' => 'Keperluan keluarga mendesak di luar kota.',
            ]
        );

        // --- 4. Buat Contoh Surat Tugas (Static) ---
        $suratTugas = Surat::firstOrCreate(
            ['nomor_surat' => 'ST-001/Kk.13/KP.01.1/03/2026'],
            [
                'jenis_surat' => 'tugas',
                'tanggal_surat' => '2026-02-20',
                'perihal' => 'Perintah Tugas',
                'pegawai_id' => $staff1->id,
                'penandatangan_id' => $kepala->id,
                'created_by_user_id' => $adminUser->id,
                // Kolom khusus tugas
                'dasar_hukum' => 'Surat Undangan Pelatihan No. UND-123/XYZ/2026',
                'tujuan_tugas' => 'Mengikuti Bimbingan Teknis Aplikasi SAKTI di Jakarta',
                'lokasi_tugas' => 'Jakarta',
                'tanggal_mulai_tugas' => '2026-03-01',
                'tanggal_selesai_tugas' => '2026-03-05',
            ]
        );

        // --- 5. Generate LARGE Test Data (Random) ---
        $this->command->info('Generating random test data...');

        // Create 50 Random Pegawai
        $randomPegawais = Pegawai::factory(50)->create();

        // Combine all pegawais for association
        $allPegawais = Pegawai::all();

        // 50 Random Surat Cuti
        Surat::factory(50)
            ->cuti()
            ->create(function () use ($allPegawais, $kepala, $adminUser) {
                return [
                    'pegawai_id' => $allPegawais->random()->id,
                    'penandatangan_id' => $kepala->id,
                    'created_by_user_id' => $adminUser->id,
                ];
            });

        // 50 Random Surat Tugas
        Surat::factory(50)
            ->tugas()
            ->create(function () use ($allPegawais, $kepala, $adminUser) {
                return [
                    'pegawai_id' => $allPegawais->random()->id,
                    'penandatangan_id' => $kepala->id,
                    'created_by_user_id' => $adminUser->id,
                ];
            });

        $this->command->info('Database seeding completed with static and random data!');
    }
}
