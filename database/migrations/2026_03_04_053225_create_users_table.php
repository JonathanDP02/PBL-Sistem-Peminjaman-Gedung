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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->references('id')->on('units');
            $table->foreignId('position_id')->nullable()->references('id')->on('positions');
            $table->foreignId('role_id')->references('id')->on('roles');
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->text('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
