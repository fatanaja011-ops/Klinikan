<?php

namespace Database\Factories;

use App\Models\Dokter;
use Illuminate\Database\Eloquent\Factories\Factory;

class DokterFactory extends Factory
{
    protected $model = Dokter::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'kode_dokter' => 'DR-' . $this->faker->unique()->numerify('###'),
            'nama_dokter' => $this->faker->name(),
            'spesialisasi' => $this->faker->randomElement(['Umum', 'Mata', 'Gigi', 'Kulit']),
            'no_telepon' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
            'status' => 'aktif',
        ];
    }
}
