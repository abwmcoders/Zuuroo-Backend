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
        Schema::create('sim_servers', function (Blueprint $table) {
            $table->id();
            $table->integer('operator_code')->unsigned();
            $table->string('sim_server'); 
            $table->tinyInteger('status')->default(0); 
            $table->string('client_id');
            $table->string('client_secret');
            $table->string('access_token');
            $table->string('public_key'); 
            $table->string('secret_key'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sim_servers');
    }
};
