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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('TransferRef')->nullable();
            $table->integer('TransactionType')->nullable();
            $table->string('DistributorRef')->nullable();
            $table->string('SkuCode')->nullable(); 
            $table->string('Price')->nullable();
            $table->string('CustomerFee')->nullable();
            $table->string('DistributorFee')->nullable();
            $table->string('ReceiveValue')->nullable();
            $table->string('ReceiveCurrencyIso')->nullable();
            $table->string('ReceiveValueExcludingTax')->nullable(); 
            $table->string('TaxRate')->nullable();
            $table->string('RepaymentDay')->nullable(); 
            $table->string('SendValue')->nullable();
            $table->string('Topup')->nullable();
            $table->string('SendCurrencyIso')->nullable(); 
            $table->string('CommissionApplied')->nullable(); 
            $table->string('DataPlan')->nullable(); 
            $table->string('StartedUtc'); 
            $table->string('CompletedUtc')->nullable(); 
            $table->string('ProcessingState')->nullable(); 
            $table->string('AccountNumber')->nullable();
            $table->string('ProviderCode')->nullable(); 
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
