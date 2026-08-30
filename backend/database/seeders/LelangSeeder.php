<?php

namespace Database\Seeders;

use App\Models\Lelang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LelangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lelang::factory(10)->create();
    }
}
