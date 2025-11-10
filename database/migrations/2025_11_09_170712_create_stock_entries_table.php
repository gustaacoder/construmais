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
        Schema::create('stock_entries', function (Blueprint $table) {
            $table->id();
            $table->date('entry_date');
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity');
            $table->decimal('purchase_price', 12, 2);
            $table->unsignedInteger('supplier_payment_terms')->default(0);

            $table->string('invoice_number', 32)->nullable();
            $table->string('invoice_series', 16)->nullable();
            $table->string('batch')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('warehouse')->nullable();

            $table->timestamps();

            $table->index(['entry_date', 'supplier_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_entries');
    }
};
