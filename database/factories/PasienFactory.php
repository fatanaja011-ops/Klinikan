<?php

namespace Database\Factories;

use App\Models\Pasien;
use Illuminate\Database\Eloquent\Factories\Factory;

class PasienFactory extends Factory
{
    protected $model = Pasien::class;

    public function definition(): array
    {
        return [
            'user_id' => null,
            'no_rm' => 'RM-' . strtoupper($this->faker->bothify('######')),
            'nik' => $this->faker->unique()->numerify('320############'),
            'nama' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tanggal_lahir' => $this->faker->date(),
            'alamat' => $this->faker->address(),
            'no_telepon' => $this->faker->phoneNumber(),
        ];
    }
}
