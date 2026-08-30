<?php

namespace Database\Factories;

use App\Models\Barang;
use App\Models\KategoriBarang;
use App\Models\Petugas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Barang>
 */
class BarangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_barang' => fake()->unique()->name(),
            'tgl' => fake()->dateTime(now(), 'Asia/Jakarta'),
            'harga_awal' => fake()->numberBetween(10000, 1000000),
            'deskripsi_barang' => fake()->words(10, true),
            'thumbnail' => fake()->imageUrl( 200, 200, 'product'),
            'id_kategori_barang' => KategoriBarang::inRandomOrder()->first()->id_kategori_barang,
            'id_petugas' => Petugas::inRandomOrder()->first()->id_petugas
        ];
    }
}
