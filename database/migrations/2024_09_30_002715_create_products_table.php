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
            $table->string('category_code', 255); 
            $table->string('country_code', 255);
            $table->string('operator_code', 255); 
            $table->string('product_code', 255);
            $table->text('product_name');
            $table->bigInteger('cost_price')->default(0); 
            $table->string('product_price', 255); 
            $table->string('loan_price', 255);
            $table->string('send_value', 255);
            $table->string('send_currency', 255); 
            $table->string('receive_value', 255); 
            $table->string('receive_currency', 255); 
            $table->string('commission_rate', 255);
            $table->string('uat_number', 255); 
            $table->string('validity', 255); 
            $table->tinyInteger('status'); 
            $table->timestamps();
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
