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
        Schema::create('data_products', function (Blueprint $table) {
            $table->id();
            $table->string('ProviderCode', 255)->nullable();
            $table->string('SkuCode', 255)->nullable();
            $table->string('DefaultDisplayText', 255)->nullable();
            $table->string('LocalizationKey', 255)->nullable();
            $table->string('CommissionRate', 255)->nullable();
            $table->string('ProcessingMode', 255)->nullable();
            $table->string('RedemptionMechanism', 255)->nullable();
            $table->string('UatNumber', 255)->nullable();
            $table->string('RegionCode', 255)->nullable();
            $table->string('DistributorFee', 255)->nullable();
            $table->string('ReceiveValue', 255)->nullable();
            $table->string('ReceiveCurrencyIso', 255)->nullable();
            $table->string('SendValue', 255)->nullable();
            $table->string('SendCurrencyIso', 255)->nullable();
            $table->string('ValidityPeriodIso', 255)->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_products');
    }
};
