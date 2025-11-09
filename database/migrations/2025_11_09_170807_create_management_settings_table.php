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
        Schema::create('management_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('expense_forecast', 14, 2)->default(0);
            $table->string('reference_period')->nullable();
            $table->unsignedTinyInteger('credit_card_default_terms')->default(30);
            $table->unsignedTinyInteger('pix_debit_default_terms')->default(0);
            $table->unsignedTinyInteger('safety_stock_days')->default(7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_settings');
    }
};
