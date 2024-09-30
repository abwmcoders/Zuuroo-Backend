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
        Schema::create('mobile_network_list', function (Blueprint $table) {
            $table->id();
            $table->string('Country', 28)->nullable();
            $table->string('Operator', 30)->nullable(); 
            $table->string('Network_code', 24)->nullable(); 
            $table->string('Display_text', 16)->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_network_list');
    }
};
