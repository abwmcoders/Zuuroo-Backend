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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->string('user_id'); 
            $table->string('plan'); 
            $table->string('purchase');
            $table->string('country_code'); 
            $table->string('operator_code'); 
            $table->string('product_code'); 
            $table->string('transfer_ref'); 
            $table->string('phone_number'); 
            $table->string('distribe_ref'); 
            $table->bigInteger('cost_price');
            $table->string('selling_price')->default('0'); 
            $table->string('receive_value'); 
            $table->string('send_value'); 
            $table->string('receive_currency'); 
            $table->string('commission_applied'); 
            $table->string('startedUtc'); 
            $table->string('completedUtc'); 
            $table->string('processing_state'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
