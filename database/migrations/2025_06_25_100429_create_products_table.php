<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->nullable()->comment('Stock Keeping Unit');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('selling_price')->comment('Harga jual default');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};