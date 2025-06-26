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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('package_id')->constrained('packages');
            
            // User yang menjadi terapis (melakukan massage)
            // Relasi ke tabel users. onDelete('restrict') mencegah user dihapus jika masih punya transaksi.
            $table->foreignId('therapist_user_id')->constrained('users')->onDelete('restrict');
            
            // User yang menginput transaksi (kasir)
            $table->foreignId('cashier_user_id')->constrained('users')->onDelete('restrict');
            
            $table->string('customer_name')->nullable()->default('Customer');
            $table->unsignedBigInteger('total_amount');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};