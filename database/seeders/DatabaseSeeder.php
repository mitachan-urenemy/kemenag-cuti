<?php

namespace Database\Seeders;

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
        $kepala = [
            'nama_lengkap' => 'Dr. H. Budi Santoso, M.Ag.',
            'nip' => '1234567890123456',
            'bidang_seksi' => 'Kantor Kementerian Agama',
            'jabatan' => 'Kepala Kantor',
            'pangkat_golongan' => 'Pembina Utama Muda (IV/c)',
            'is_kepala' => true,
            'status_pegawai' => 'PNS',
        ];

        $staff1 = [
            'nama_lengkap' => 'Ahmad Riyadi, S.Kom.',
            'nip' => '1234567890123456',
            'jabatan' => 'Pranata Komputer',
            'bidang_seksi' => 'Subbagian Tata Usaha',
            'pangkat_golongan' => 'Penata Muda (III/a)',
            'is_kepala' => false,
            'status_pegawai' => 'PNS',
        ];

        $staff2 = [
            'nama_lengkap' => 'Siti Aminah, S.E.',
            'nip' => '1234567890123456',
            'jabatan' => 'Analis Keuangan',
            'bidang_seksi' => 'Subbagian Tata Usaha',
            'pangkat_golongan' => 'Penata Muda (III/a)',
            'is_kepala' => false,
            'status_pegawai' => 'PPPK',
        ];

        // --- 2. Buat Data User dan Tautkan ke Pegawai ---
        $adminUser = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['username' => 'ahmad'],
            [
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

                'nama_lengkap_pegawai' => $staff1['nama_lengkap'],
                'nip_pegawai' => $staff1['nip'],
                'pangkat_golongan_pegawai' => $staff1['pangkat_golongan'],
                'jabatan_pegawai' => $staff1['jabatan'],
                'bidang_seksi_pegawai' => $staff1['bidang_seksi'],
                'status_pegawai' => $staff1['status_pegawai'],

                'nama_lengkap_kepala_pegawai' => $kepala['nama_lengkap'],
                'nip_kepala_pegawai' => $kepala['nip'],
                'jabatan_kepala_pegawai' => $kepala['jabatan'],
                // Kolom khusus cuti
                'jenis_cuti' => 'tahunan',
                'tanggal_mulai_cuti' => '2026-02-10',
                'tanggal_selesai_cuti' => '2026-02-15',
                'tanggal_selesai_cuti' => '2026-02-15',
                'keterangan_cuti' => 'Keperluan keluarga mendesak di luar kota.',
                'tembusan' => "1. Kepala Kantor Wilayah Kementerian Agama Prov. Aceh\n2. Arsip",
            ]
        );

        // --- 4. Buat Contoh Surat Tugas (Static) ---
        $suratTugas = Surat::firstOrCreate(
            ['nomor_surat' => 'ST-001/Kk.13/KP.01.1/03/2026'],
            [
                'jenis_surat' => 'tugas',
                'tanggal_surat' => '2026-02-20',
                'perihal' => 'Perintah Tugas',

                'nama_lengkap_pegawai' => $staff2['nama_lengkap'],
                'nip_pegawai' => $staff2['nip'],
                'pangkat_golongan_pegawai' => $staff2['pangkat_golongan'],
                'jabatan_pegawai' => $staff2['jabatan'],
                'bidang_seksi_pegawai' => $staff2['bidang_seksi'],
                'status_pegawai' => $staff2['status_pegawai'],

                'nama_lengkap_kepala_pegawai' => $kepala['nama_lengkap'],
                'nip_kepala_pegawai' => $kepala['nip'],
                'jabatan_kepala_pegawai' => $kepala['jabatan'],
                // Kolom khusus tugas
                'dasar_hukum' => 'Surat Undangan Pelatihan No. UND-123/XYZ/2026',
                'tujuan_tugas' => 'Mengikuti Bimbingan Teknis Aplikasi SAKTI di Jakarta',
                'lokasi_tugas' => 'Jakarta',
                'tanggal_mulai_tugas' => '2026-03-01',
                'tanggal_selesai_tugas' => '2026-03-05',
            ]
        );

        // 50 Random Surat Cuti
        Surat::factory(50)
            ->cuti()
            ->create(function () use ($kepala) {
                return [
                    'nama_lengkap_pegawai' => fake()->name(),
                    'nip_pegawai' => fake()->bothify('###########'),
                    'pangkat_golongan_pegawai' => fake()->randomElement(['Penata Muda (III/a)', 'Penata Muda Tingkat I (III/b)', 'Penata (III/c)', 'Penata Tingkat I (III/d)', 'Pembina (IV/a)', 'Pembina Tingkat I (IV/b)', 'Pembina Utama Muda (IV/c)', 'Pembina Utama Madya (IV/d)']),
                    'bidang_seksi_pegawai' => fake()->randomElement(['Subbagian Umum', 'Seksi Keuangan', 'Seksi Dayamas', 'Seksi Rehabilitasi', 'Seksi Pemberantasan']),
                    'jabatan_pegawai' => fake()->jobTitle(),
                    'status_pegawai' => fake()->randomElement(['PNS', 'PPPK']),

                    'nama_lengkap_kepala_pegawai' => $kepala['nama_lengkap'],
                    'nip_kepala_pegawai' => $kepala['nip'],
                    'jabatan_kepala_pegawai' => $kepala['jabatan'],
                ];
            });

        // 50 Random Surat Tugas
        Surat::factory(50)
            ->tugas()
            ->create(function () use ($kepala) {
                return [
                    'nama_lengkap_pegawai' => fake()->name(),
                    'nip_pegawai' => fake()->bothify('###########'),
                    'pangkat_golongan_pegawai' => fake()->randomElement(['Penata Muda (III/a)', 'Penata Muda Tingkat I (III/b)', 'Penata (III/c)', 'Penata Tingkat I (III/d)', 'Pembina (IV/a)', 'Pembina Tingkat I (IV/b)', 'Pembina Utama Muda (IV/c)', 'Pembina Utama Madya (IV/d)']),
                    'bidang_seksi_pegawai' => fake()->randomElement(['Subbagian Umum', 'Seksi Keuangan', 'Seksi Dayamas', 'Seksi Rehabilitasi', 'Seksi Pemberantasan']),
                    'jabatan_pegawai' => fake()->jobTitle(),
                    'status_pegawai' => fake()->randomElement(['PNS', 'PPPK']),

                    'nama_lengkap_kepala_pegawai' => $kepala['nama_lengkap'],
                    'nip_kepala_pegawai' => $kepala['nip'],
                    'jabatan_kepala_pegawai' => $kepala['jabatan'],
                ];
            });

        $this->command->info('Database seeding completed with static and random data!');
    }
}
