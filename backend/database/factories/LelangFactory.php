<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\Lelang;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lelang>
 */
class LelangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tgl_mulai_lelang' => fake()->date(),
            'tgl_akhir_lelang' => fake()->date(now()),
            'harga_akhir' => fake()->numberBetween(1000, 10000),
            'id_barang' => Barang::inRandomOrder()->first()->id_barang,
            'id_user' => User::inRandomOrder()->first()->id_masyarakat,
            'id_petugas' => Petugas::inRandomOrder()->first()->id_petugas,
            'status' => 'dibuka'
        ];
    }
}
