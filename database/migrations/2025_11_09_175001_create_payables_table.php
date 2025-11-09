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
        Schema::create('payables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_entry_id')->nullable()->constrained()->nullOnDelete();
            $table->string('document_no', 40)->nullable();
            $table->date('issue_date')->nullable();
            $table->date('due_date');
            $table->decimal('amount', 14, 2);
            $table->enum('status', ['open','paid','overdue'])->default('open');
            $table->index(['due_date','status']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payables');
    }
};
