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
        Schema::create('recurring_charges', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('user_email')->nullable();
            $table->string('authorization_code')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('card_type')->nullable();
            $table->string('last4')->nullable();
            $table->string('exp_month')->nullable();
            $table->string('exp_year')->nullable();
            $table->string('bin')->nullable();
            $table->string('channel')->nullable();
            $table->string('signature')->nullable();
            $table->string('reusable')->nullable(); 
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_charges');
    }
};
