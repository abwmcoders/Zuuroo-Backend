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
        Schema::create('cable_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('provider_name'); 
            $table->string('provider_code');   
            $table->string('country_code');    
            $table->string('status')->default('active'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cable_subscriptions');
    }
};
