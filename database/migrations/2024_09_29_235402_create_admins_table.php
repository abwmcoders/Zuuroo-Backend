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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('username')->nullable();
            $table->string('gender')->nullable();
            $table->string('password')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->timestamp('email_verified_at')->useCurrent()->nullable();
            $table->integer('role')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
