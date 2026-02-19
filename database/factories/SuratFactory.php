<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Surat>
 */
class SuratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nomor_surat' => fake()->unique()->bothify('B-####/Kk.13/KP.01.1/##/2026'),
            'jenis_surat' => fake()->randomElement(['cuti', 'tugas']),
            'tanggal_surat' => fake()->date(),
            'perihal' => fake()->sentence(),
            'nama_lengkap_pegawai' => fake()->name(),
            'nip_pegawai' => fake()->bothify('###########'),
            'pangkat_golongan_pegawai' => fake()->randomElement(['III/a', 'III/b', 'III/c', 'III/d', 'IV/a', 'IV/b', 'IV/c', 'IV/d']),
            'jabatan_pegawai' => fake()->jobTitle(),
            'bidang_seksi_pegawai' => fake()->city(),
            'status_pegawai' => fake()->randomElement(['PNS', 'PPPK']),

            'nama_lengkap_kepala_pegawai' => fake()->name(),
            'nip_kepala_pegawai' => fake()->bothify('###########'),
            'jabatan_kepala_pegawai' => fake()->jobTitle(),
        ];
    }

    /**
     * Indicate that the surat is a Surat Cuti.
     */
    public function cuti(): static
    {
        return $this->state(function (array $attributes) {
            $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
            $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(1, 5) . ' days');
            $jenisCuti = fake()->randomElement(['tahunan', 'sakit', 'melahirkan', 'alasan_penting', 'besar']);

            return [
                'jenis_surat' => 'cuti',
                'jenis_cuti' => $jenisCuti,
                'tanggal_mulai_cuti' => $startDate->format('Y-m-d'),
                'tanggal_selesai_cuti' => $endDate->format('Y-m-d'),
                'keterangan_cuti' => fake()->paragraph(),
                'tembusan' => in_array($jenisCuti, ['tahunan', 'alasan_penting', 'besar']) ? "1. Kepala Kantor Wilayah Kementerian Agama Prov. Aceh\n2. Arsip" : null,
            ];
        });
    }

    /**
     * Indicate that the surat is a Surat Tugas.
     */
    public function tugas(): static
    {
        return $this->state(function (array $attributes) {
            $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
            $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(1, 4) . ' days');

            return [
                'jenis_surat' => 'tugas',
                'dasar_hukum' => fake()->sentence(),
                'tujuan_tugas' => fake()->sentence(),
                'lokasi_tugas' => fake()->city(),
                'tanggal_mulai_tugas' => $startDate->format('Y-m-d'),
                'tanggal_selesai_tugas' => $endDate->format('Y-m-d'),
            ];
        });
    }
}
