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
        User::firstOrCreate(
            ['email' => 'admin@situbondo.go.id'],
            [
                'name' => 'Admin Sistem',
                'role' => 'admin',
                'password' => Hash::make('12345678'),
            ]
        );

        // Regular User
        User::firstOrCreate(
            ['email' => 'user@situbondo.go.id'],
            [
                'name' => 'User Biasa',
                'role' => 'user',
                'password' => Hash::make('12345678'),
            ]
        );

        // Kepala Bidang TIK User
        User::firstOrCreate(
            ['email' => 'kepalatik@situbondo.go.id'],
            [
                'name' => 'Kepala Bidang TIK',
                'role' => 'kepala_bidang_tik',
                'password' => Hash::make('12345678'),
            ]
        );
    }
}