<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\HistoryLelang;
use App\Models\Lelang;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HistoryLelang>
 */
class HistoryLelangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_barang' => Barang::inRandomOrder()->first()->id_barang,
            'id_user' => User::inRandomOrder()->first()->id_user,
            'id_lelang' => Lelang::inRandomOrder()->first()->id_lelang,
            'penawaran_harga' => fake()->numberBetween(1000 , 5000000)
        ];
    }
}
