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
        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('room_id')->primary();
            $table->foreignUuid('building_id')->references('building_id')->on('buildings')->cascadeOnDelete();
            
            //Siapa "Unit" pemilik sah ruangan ini?
            $table->foreignUuid('unit_id')->references('unit_id')->on('units');
            
            $table->string('room_name', 150);
            $table->integer('capacity')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
