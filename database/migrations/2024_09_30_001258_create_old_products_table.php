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
        Schema::create('old_products', function (Blueprint $table) {
            $table->id();
            $table->string('category_code'); 
            $table->string('country_code');
            $table->string('operator_code'); 
            $table->string('product_code');
            $table->text('product_name'); 
            $table->string('product_price'); 
            $table->string('loan_price');
            $table->string('send_value');
            $table->string('send_currency'); 
            $table->string('receive_value'); 
            $table->string('receive_currency'); 
            $table->string('commission_rate');
            $table->string('uat_number'); 
            $table->string('validity'); 
            $table->tinyInteger('status'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('old_products');
    }
};
