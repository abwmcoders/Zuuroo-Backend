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
        Schema::create('max_limits', function (Blueprint $table) {
            $table->id();
            $table->string('topup')->nullable(); 
            $table->string('limit_value')->nullable(); 
            $table->unsignedBigInteger('admin')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('max_limits');
    }
};
