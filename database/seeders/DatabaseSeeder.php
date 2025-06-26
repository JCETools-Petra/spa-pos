<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class, // Seeder admin yang sudah ada
            BranchAndUserSeeder::class, // <-- PANGGIL SEEDER BARU DI SINI
        ]);
    }
}