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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('barcode')->index()->nullable();
            $table->enum('unit', ['UN', 'KG', 'L', 'M2', 'M3'])->default('UN');
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->decimal('avg_cost', 12, 2)->nullable();
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->unsignedInteger('min_stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['name', 'category', 'brand']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
