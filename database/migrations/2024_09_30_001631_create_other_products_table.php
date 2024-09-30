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
        Schema::create('other_products', function (Blueprint $table) {
            $table->id();
            $table->string('Category')->nullable(); 
            $table->string('ServiceName')->nullable();
            $table->string('variation_amount')->nullable();
            $table->string('loan_perc')->nullable(); 
            $table->string('name')->nullable(); 
            $table->string('serviceID')->nullable(); 
            $table->string('variation_code')->nullable(); 
            $table->string('convinience_fee')->nullable();
            $table->string('fixedPrice')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_products');
    }
};
