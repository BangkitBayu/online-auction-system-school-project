<?php

namespace Database\Seeders;

use App\Models\HistoryLelang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistoryLelangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HistoryLelang::factory(20)->create();
    }
}
