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
        Schema::create('offer_services', function (Blueprint $table) {
            $table->id();
            $table->string('service_name')->nullable(); 
            $table->string('service_code')->nullable(); 
            $table->string('svalue')->nullable(); 
            $table->string('service_category')->nullable();
            $table->tinyInteger('service_state')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offer_services');
    }
};
