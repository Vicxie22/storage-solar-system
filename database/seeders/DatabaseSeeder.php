<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil seeder yang sudah kamu buat di sini
        $this->call([
            UserSeeder::class,
            // Jika nanti kamu buat MasterPenyimpananSeeder, tambahkan di bawahnya
        ]);
    }
}