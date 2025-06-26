<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom 'role' setelah kolom 'email'
            $table->enum('role', ['admin', 'branch_user'])
                  ->default('branch_user')
                  ->after('email');
            
            // Menambahkan relasi ke tabel 'branches'.
            // Bisa NULL karena admin tidak terikat pada cabang manapun.
            // On delete 'set null' berarti jika cabang dihapus, user tidak ikut terhapus, branch_id-nya menjadi NULL.
            $table->foreignId('branch_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('branches')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus foreign key constraint sebelum menghapus kolom
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['role', 'branch_id']);
        });
    }
};