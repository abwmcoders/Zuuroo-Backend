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
        Schema::create('data_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_ref', 100);
            $table->string('mobile_recharge', 100);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('balance_bfo');
            $table->unsignedBigInteger('balance_after');
            $table->unsignedBigInteger('amount_debt');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_wallets');
    }
};
