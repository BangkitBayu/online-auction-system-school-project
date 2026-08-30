<?php

namespace Database\Seeders;

use App\Models\KategoriBarang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriBarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriBarang::create([
            'nama_kategori_barang' => 'Kendaraan'
        ]);
        KategoriBarang::create([
            'nama_kategori_barang' => 'Alat Berat'
        ]);
        KategoriBarang::create([
            'nama_kategori_barang' => 'Elektronik dan Inventaris'
        ]);
        KategoriBarang::create([
            'nama_kategori_barang' => 'Komoditas'
        ]);
        KategoriBarang::create([
            'nama_kategori_barang' => 'Tanah'
        ]);
        KategoriBarang::create([
            'nama_kategori_barang' => 'Bangunan'
        ]);
        KategoriBarang::create([
            'nama_kategori_barang' => 'Kapal'
        ]);
    }
}
