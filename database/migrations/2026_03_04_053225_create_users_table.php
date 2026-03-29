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
            $table->uuid('user_id')->primary();
            $table->foreignUuid('unit_id')->references('unit_id')->on('units');
            $table->foreignUuid('position_id')->nullable()->references('position_id')->on('positions');
            $table->foreignUuid('role_id')->references('role_id')->on('roles');
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
