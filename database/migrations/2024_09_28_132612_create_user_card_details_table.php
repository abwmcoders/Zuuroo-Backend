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
        Schema::create('user_card_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('account_name')->nullable(); 
            $table->string('authorization_code')->nullable();
            $table->string('bank')->nullable(); 
            $table->string('bin')->nullable(); 
            $table->string('brand')->nullable(); 
            $table->string('card_type')->nullable();
            $table->string('country_code')->nullable(); 
            $table->string('exp_month')->nullable(); 
            $table->string('exp_year')->nullable();
            $table->string('last4')->nullable(); 
            $table->string('reusable')->nullable(); 
            $table->string('signature')->nullable(); 
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_card_details');
    }
};
