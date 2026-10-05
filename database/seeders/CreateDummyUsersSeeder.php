<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateDummyUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@situbondo.go.id',
            'role' => 'admin',
            'password' => Hash::make('12345678'),
        ]);

        // Regular User
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@situbondo.go.id',
            'role' => 'user',
            'password' => Hash::make('12345678'),
        ]);

        // Kepala Bidang TIK User
        User::create([
            'name' => 'Kepala Bidang TIK',
            'email' => 'kepalatik@situbondo.go.id',
            'role' => 'kepala_bidang_tik',
            'password' => Hash::make('12345678'),
        ]);
    }
}