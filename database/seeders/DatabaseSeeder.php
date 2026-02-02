<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pegawai;
use App\Models\KopSurat;
use App\Models\Surat;
use App\Models\Cuti;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a "Kepala" Pegawai
        $kepala = Pegawai::create([
            'nama' => 'Dr. H. John Doe, M.Ag.',
            'nip' => '198001012005011001',
            'jabatan' => 'Kepala Kantor',
            'unit_kerja' => 'Kantor Kementerian Agama',
            'pangkat_golonganruang' => 'Pembina Utama Muda (IV/c)',
            'kepala' => true,
        ]);

        // 2. Create a regular "Staff" Pegawai
        $staff = Pegawai::create([
            'nama' => 'Jane Roe, S.Kom.',
            'nip' => '199002022015022002',
            'jabatan' => 'Pranata Komputer',
            'unit_kerja' => 'Subbagian Tata Usaha',
            'pangkat_golonganruang' => 'Penata Muda (III/a)',
            'kepala' => false,
        ]);

        // 3. Create an Admin User and link it to the "Kepala" Pegawai
        User::create([
            'pegawai_id' => $kepala->id,
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
        ]);

        // 4. Create a KopSurat
        $kopSurat = KopSurat::create([
            'title' => 'KEMENTERIAN AGAMA REPUBLIK INDONESIA',
            'type' => 'KANTOR WILAYAH PROVINSI',
            'description' => 'Jl. Jenderal Sudirman No. 123, Kota, Provinsi, Kode Pos',
            'alamat_kop' => 'Telp. (0123) 456789, Website: https://kemenag.go.id'
        ]);

        // 5. Create a Cuti request from Staff to Kepala
        Cuti::create([
            'pegawai_id' => $staff->id,
            'jenis_cuti' => 'Cuti Tahunan',
            'alasan_cuti' => 'Keperluan keluarga mendesak di luar kota.',
            'tanggal_mulai' => '2026-02-10',
            'tanggal_selesai' => '2026-02-15',
            'lama_cuti' => 6,
            'alamat_selama_cuti' => 'Jl. Merdeka No. 10, Kota Lain',
            'status_persetujuan' => 'pending',
            'atasan_id' => $kepala->id,
            'pejabat_id' => $kepala->id,
        ]);
    }
}
