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
        Schema::create('user_bank_details', function (Blueprint $table) {
            $table->bigIncrements('id'); 
            $table->string('res_reference'); 
            $table->string('user_name'); 
            $table->string('user_email'); 
            $table->string('account_name'); 
            $table->string('account_number'); 
            $table->string('bank_name');
            $table->string('bank_code');
            $table->string('account_status'); 
            $table->timestamps();

            $table->index('user_email'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bank_details');
    }
};
