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
        Schema::create('network_providers', function (Blueprint $table) {
            $table->id();
            $table->string('ProviderCode')->nullable(); 
            $table->string('CountryIso')->nullable(); 
            $table->string('Name')->nullable(); 
            $table->string('ValidationRegex')->nullable(); 
            $table->string('LogoUrl')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_providers');
    }
};
