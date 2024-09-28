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
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->string('countryC_code');
            $table->string('first_name'); 
            $table->string('last_name');
            $table->string('transaction_ref'); 
            $table->string('id_number');
            $table->string('id_type'); 
            $table->string('date_of_birth');
            $table->string('verify_status'); 
            $table->tinyInteger('verificationStatus')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
