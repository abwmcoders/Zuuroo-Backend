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
        Schema::create('loan_record', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable(); 
            $table->string('reference_id')->nullable();
            $table->string('loan_amount')->nullable(); 
            $table->integer('status')->default(0);
            $table->date('repayment')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_record');
    }
};
