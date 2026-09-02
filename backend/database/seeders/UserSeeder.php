<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama_lengkap' => 'Bangkit Bayu Prasetyo',
            'username' => 'bayuuser1',
            'password' => Hash::make('bayuuser1'),
            'telp' => '085813385224',
            'email' => 'bayuuser1@example.com'
        ]);
    }
}
