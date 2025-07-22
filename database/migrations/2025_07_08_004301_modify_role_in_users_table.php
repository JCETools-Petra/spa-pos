<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * * Perintah ini akan mengubah kolom 'role' untuk menambahkan 'owner'.
     */
    public function up(): void
    {
        // Mengubah kolom ENUM untuk menambahkan 'owner' tanpa menghapus data
        DB::statement("ALTER TABLE users CHANGE COLUMN role role ENUM('admin', 'owner', 'branch_user') NOT NULL DEFAULT 'branch_user'");
    }

    /**
     * Reverse the migrations.
     * * Perintah ini akan mengembalikan kolom 'role' ke kondisi semula.
     */
    public function down(): void
    {
        // Hati-hati: Jika ada user dengan role 'owner', perintah ini bisa gagal.
        // Sebaiknya, ubah dulu role 'owner' kembali ke 'admin' atau 'branch_user' sebelum rollback.
        DB::statement("ALTER TABLE users CHANGE COLUMN role role ENUM('admin', 'branch_user') NOT NULL DEFAULT 'branch_user'");
    }
};