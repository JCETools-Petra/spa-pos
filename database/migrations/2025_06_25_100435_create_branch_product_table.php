<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('branch_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('stock_quantity')->default(0);
            $table->unsignedBigInteger('selling_price')->comment('Harga jual spesifik per cabang, bisa beda dari default');
            $table->timestamps();
            $table->unique(['branch_id', 'product_id']); // Pastikan satu produk hanya ada satu entri per cabang
        });
    }
    public function down(): void { Schema::dropIfExists('branch_product'); }
};