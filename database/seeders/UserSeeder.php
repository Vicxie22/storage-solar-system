<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Tambahkan ini

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin
        User::create([
            'name' => 'Administrator Solar',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'role' => 'Admin'
        ]);

        // Membuat akun User / Operator
        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'username' => 'User',
            'password' => Hash::make('123456'),
            'role' => 'User'
        ]);
    }
}