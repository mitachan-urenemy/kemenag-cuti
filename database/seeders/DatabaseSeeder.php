<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- 1. Buat Data User ---
        $users = [
            'admin' => User::firstOrCreate(
                ['username' => 'admin'],
                ['role' => 'admin', 'status' => true, 'password' => Hash::make('password')]
            ),
            'kepala' => User::firstOrCreate(
                ['username' => 'kepala'],
                ['role' => 'pimpinan', 'status' => true, 'password' => Hash::make('password')]
            ),
            'ahmad' => User::firstOrCreate(
                ['username' => 'ahmad'],
                ['role' => 'pegawai', 'status' => true, 'password' => Hash::make('password')]
            ),
            'siti' => User::firstOrCreate(
                ['username' => 'siti'],
                ['role' => 'pegawai', 'status' => true, 'password' => Hash::make('password')]
            ),
            'rudi' => User::firstOrCreate(
                ['username' => 'rudi'],
                ['role' => 'pegawai', 'status' => true, 'password' => Hash::make('password')]
            ),
        ];

        // --- 2. Buat Data Pegawai ---
        $pegawais = [
            'admin' => Pegawai::firstOrCreate(
                ['user_id' => $users['admin']->id],
                [
                    'nama_lengkap' => 'Budi Santoso, S.Kom.',
                    'nip' => '198501012010011001',
                    'jenis_kelamin' => 'laki',
                    'unit_kerja' => 'Subbagian Tata Usaha',
                    'jabatan' => 'Analis Kepegawaian',
                    'pangkat_golongan' => 'Penata (III/c)',
                    'is_atasan' => false,
                    'status_kepegawaian' => 'PNS',
                    'email' => 'budi.santoso@kemenag.go.id',
                    'nomor_hp' => '081234567890'
                ]
            ),
            'kepala' => Pegawai::firstOrCreate(
                ['user_id' => $users['kepala']->id],
                [
                    'nama_lengkap' => 'Drs. H. Wahyudi, M.A.',
                    'nip' => '197004041995031001',
                    'jenis_kelamin' => 'laki',
                    'unit_kerja' => 'Kantor Kementerian Agama Kabupaten Bener Meriah',
                    'jabatan' => 'Kepala Kantor',
                    'pangkat_golongan' => 'Pembina Utama Muda (IV/c)',
                    'is_atasan' => true,
                    'status_kepegawaian' => 'PNS',
                    'email' => 'wahyudi.kepala@kemenag.go.id',
                    'nomor_hp' => '081112223334'
                ]
            ),
            'ahmad' => Pegawai::firstOrCreate(
                ['user_id' => $users['ahmad']->id],
                [
                    'nama_lengkap' => 'Ahmad Fauzi, S.Pd.I.',
                    'nip' => '199002022015041002',
                    'jenis_kelamin' => 'laki',
                    'unit_kerja' => 'Seksi Pendidikan Islam',
                    'jabatan' => 'Staf Pelaksana',
                    'pangkat_golongan' => 'Penata Muda (III/a)',
                    'is_atasan' => false,
                    'status_kepegawaian' => 'PNS',
                    'email' => 'ahmad.fauzi@kemenag.go.id',
                    'nomor_hp' => '085211223344'
                ]
            ),
            'siti' => Pegawai::firstOrCreate(
                ['user_id' => $users['siti']->id],
                [
                    'nama_lengkap' => 'Siti Nurhaliza, S.E.',
                    'nip' => '199203032018012003',
                    'jenis_kelamin' => 'perempuan',
                    'unit_kerja' => 'Seksi Penyelenggaraan Haji dan Umrah',
                    'jabatan' => 'Penyusun Bahan Penyelenggaraan Haji',
                    'pangkat_golongan' => 'Penata Muda Tingkat I (III/b)',
                    'is_atasan' => false,
                    'status_kepegawaian' => 'PNS',
                    'email' => 'siti.nurhaliza@kemenag.go.id',
                    'nomor_hp' => '081344556677'
                ]
            ),
            'rudi' => Pegawai::firstOrCreate(
                ['user_id' => $users['rudi']->id],
                [
                    'nama_lengkap' => 'Rudi Hermawan',
                    'nip' => '199505052022211005',
                    'jenis_kelamin' => 'laki',
                    'unit_kerja' => 'Seksi Bimbingan Masyarakat Islam',
                    'jabatan' => 'Penyuluh Agama Honorer',
                    'pangkat_golongan' => '-',
                    'is_atasan' => false,
                    'status_kepegawaian' => 'PPPK',
                    'email' => 'rudi.hermawan@kemenag.go.id',
                    'nomor_hp' => '082155667788'
                ]
            ),
        ];

        // --- 3. Buat Beberapa Surat ---
        $tahun = (int) date('Y');
        Surat::firstOrCreate(
            ['nomor_surat' => '001/TUGAS/V/' . $tahun],
            [
                'pegawai_id' => $pegawais['ahmad']->id,
                'jenis_surat' => 'tugas',
                'tanggal_surat' => Carbon::now()->subDays(5),
                'perihal' => 'Penyuluhan Moderasi Beragama',
                'status' => 'disetujui',
                'approved_by' => $pegawais['kepala']->id,
                'dasar_hukum' => 'DIPA Kementerian Agama Kab. Bener Meriah Tahun ' . $tahun,
                'tujuan_tugas' => 'Memberikan penyuluhan moderasi beragama tingkat kecamatan',
                'lokasi_tugas' => 'Kecamatan Bandar',
                'tanggal_mulai_tugas' => Carbon::now()->subDays(2),
                'tanggal_selesai_tugas' => Carbon::now()->addDays(2),
            ]
        );

        Surat::firstOrCreate(
            ['nomor_surat' => '002/TUGAS/V/' . $tahun],
            [
                'pegawai_id' => $pegawais['siti']->id,
                'jenis_surat' => 'tugas',
                'tanggal_surat' => Carbon::now()->subDays(10),
                'perihal' => 'Rapat Koordinasi Haji',
                'status' => 'disetujui',
                'approved_by' => $pegawais['kepala']->id,
                'dasar_hukum' => 'Undangan Kakanwil Kemenag Prov. Aceh No. 123/2026',
                'tujuan_tugas' => 'Menghadiri rapat persiapan operasional haji tahun berjalan',
                'lokasi_tugas' => 'Banda Aceh',
                'tanggal_mulai_tugas' => Carbon::now()->subDays(8),
                'tanggal_selesai_tugas' => Carbon::now()->subDays(6),
            ]
        );

        // Contoh Surat Cuti
        Surat::firstOrCreate(
            ['nomor_surat' => '001/CUTI/V/' . $tahun],
            [
                'pegawai_id' => $pegawais['rudi']->id,
                'jenis_surat' => 'cuti',
                'tanggal_surat' => Carbon::now()->subDays(1),
                'perihal' => 'Permohonan Cuti Tahunan',
                'status' => 'diproses',
                'jenis_cuti' => 'tahunan',
                'tanggal_mulai_cuti' => Carbon::now()->addDays(3),
                'tanggal_selesai_cuti' => Carbon::now()->addDays(5),
                'keterangan_cuti' => 'Acara pernikahan adik kandung di Medan',
                'tembusan' => '1. Kepala KUA Kecamatan\n2. Arsip',
            ]
        );

        $this->command->info('Database seeding completed dengan data interaktif (Budi, Ahmad, Siti, dsb)!');
    }
}
