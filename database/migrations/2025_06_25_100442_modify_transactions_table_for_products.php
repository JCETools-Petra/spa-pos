<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('transactions', function (Blueprint $table) {
            // Jadikan package_id bisa kosong (nullable), karena transaksi bisa saja hanya penjualan produk
            $table->foreignId('package_id')->nullable()->change();
        });
    }
    public function down(): void {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable(false)->change();
        });
    }
};