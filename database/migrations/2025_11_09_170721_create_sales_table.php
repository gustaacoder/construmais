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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->date('sale_date');
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->enum('payment_method', ['pix', 'debit', 'credit'])->nullable();
            $table->unsignedInteger('custom_terms')->nullable();
            $table->enum('status', ['draft', 'confirmed', 'cancelled'])->default('confirmed');

            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('surcharge_total', 14, 2)->default(0);
            $table->decimal('grand_total', 14, 2)->default(0);

            $table->unsignedTinyInteger('installments')->nullable();
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['sale_date', 'customer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
