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
        Schema::create('data_pricing', function (Blueprint $table) {
            $table->id();
            $table->string('data_quant', 255)->nullable();
            $table->string('network_code', 255)->nullable();
            $table->string('data_price', 255)->nullable();
            $table->string('duration', 255)->nullable();
            $table->string('interest', 255)->nullable();
            $table->string('loan_price', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pricing');
    }
};
