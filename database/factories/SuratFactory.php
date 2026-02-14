<?php

namespace Database\Factories;

use App\Models\Pegawai;
use App\Models\User;
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
            'pegawai_id' => Pegawai::factory(),
            'penandatangan_id' => Pegawai::factory(),
            'created_by_user_id' => User::factory(),
            'file_path' => null,
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

            return [
                'jenis_surat' => 'cuti',
                'jenis_cuti' => fake()->randomElement(['tahunan', 'sakit', 'melahirkan']),
                'tanggal_mulai_cuti' => $startDate->format('Y-m-d'),
                'tanggal_selesai_cuti' => $endDate->format('Y-m-d'),
                'keterangan_cuti' => fake()->paragraph(),
            ];
        });
    }

    /**
     * Indicate that the surat is a Surat Tugas.
     */
    public function tugas(): static
    {
        return $this->state(function (array $attributes) {
            $startDate = fake()->dateTimeBetween('now', '+1 month');
            $endDate = (clone $startDate)->modify('+' . fake()->numberBetween(1, 3) . ' days');

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
