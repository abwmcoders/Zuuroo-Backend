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
        Schema::create('airtime_products', function (Blueprint $table) {
            $table->id();
            $table->string('ProviderCode')->nullable();
            $table->string('SkuCode')->nullable();
            $table->string('DefaultDisplayText')->nullable();
            $table->string('LocalizationKey')->nullable();
            $table->string('CommissionRate')->nullable();
            $table->string('ProcessingMode')->nullable();
            $table->string('RedemptionMechanism')->nullable();
            $table->string('UatNumber')->nullable();
            $table->string('RegionCode')->nullable();
            $table->string('DistributorFee')->nullable();
            $table->string('ReceiveValue')->nullable();
            $table->string('ReceiveCurrencyIso')->nullable();
            $table->string('SendValue')->nullable();
            $table->string('SendCurrencyIso')->nullable();
            $table->string('ValidityPeriodIso')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airtime_products');
    }
};
