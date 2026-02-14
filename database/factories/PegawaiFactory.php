<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pegawai>
 */
class PegawaiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_lengkap' => fake()->name(),
            'nip' => fake()->unique()->numerify('19##########00##'),
            'pangkat_golongan' => fake()->randomElement(['Penata Muda (III/a)', 'Penata Muda Tk. I (III/b)', 'Penata (III/c)', 'Penata Tk. I (III/d)', 'Pembina (IV/a)']),
            'jabatan' => fake()->jobTitle(),
            'bidang_seksi' => fake()->randomElement(['Subbagian Tata Usaha', 'Seksi Pendidikan Madrasah', 'Seksi Pendidikan Agama Islam', 'Seksi Penyelenggaraan Haji dan Umrah']),
            'is_kepala' => false,
            'status_pegawai' => fake()->randomElement(['PNS', 'PPPK']),
        ];
    }

    /**
     * Indicate that the user is a head of office.
     */
    public function kepala(): static
    {
        return $this->state(fn (array $attributes) => [
            'jabatan' => 'Kepala Kantor',
            'bidang_seksi' => 'Kantor Kementerian Agama',
            'is_kepala' => true,
        ]);
    }
}
