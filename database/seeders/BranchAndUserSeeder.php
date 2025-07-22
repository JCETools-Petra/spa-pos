<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class BranchAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // === BUAT DATA CABANG CONTOH ===
        $branchJKT = Branch::create([
            'name' => 'SPA Cabang Jakarta Pusat',
            'address' => 'Jl. Jenderal Sudirman No. 123, Jakarta Pusat',
            'phone_number' => '021-555-1111'
        ]);

        $branchBDG = Branch::create([
            'name' => 'SPA Cabang Bandung',
            'address' => 'Jl. Braga No. 45, Bandung',
            'phone_number' => '022-555-2222'
        ]);

        // === BUAT DATA PENGGUNA UNTUK CABANG JAKARTA ===
        User::create([
            'name' => 'Kasir Jakarta',
            'email' => 'kasir.jakarta@example.com',
            'password' => Hash::make('password'),
            'role' => 'branch_user',
            'branch_id' => $branchJKT->id
        ]);
        
        User::create([
            'name' => 'Terapis Jakarta',
            'email' => 'terapis.jakarta@example.com',
            'password' => Hash::make('password'),
            'role' => 'branch_user',
            'branch_id' => $branchJKT->id
        ]);

        // === BUAT DATA PENGGUNA UNTUK CABANG BANDUNG ===
        User::create([
            'name' => 'Kasir Bandung',
            'email' => 'kasir.bandung@example.com',
            'password' => Hash::make('password'),
            'role' => 'branch_user',
            'branch_id' => $branchBDG->id
        ]);

        User::create([
            'name' => 'Terapis Bandung',
            'email' => 'terapis.bandung@example.com',
            'password' => Hash::make('password'),
            'role' => 'branch_user',
            'branch_id' => $branchBDG->id
        ]);

        // Anda bisa menambahkan lebih banyak cabang atau user di sini
    }
}