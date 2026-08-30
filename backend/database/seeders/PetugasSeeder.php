<?php

namespace Database\Seeders;

use App\Models\Petugas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Petugas::create([
            'nama_petugas' => 'Bangkit Bayu Prasetyo',
            'username' => 'bayuadmin1',
            'password' => Hash::make('bayuadmin1'),
            'telp' => '085813385224',
            'id_level' => 1
        ]);
        Petugas::create([
            'nama_petugas' => 'Bangkit Bayu Prasetyo',
            'username' => 'bayupetugas1',
            'password' => Hash::make('bayupetugas1'),
            'telp' => '085813385224',
            'id_level' => 2
        ]);
    }
}
