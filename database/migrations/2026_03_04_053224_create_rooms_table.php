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
            $table->id();
            $table->foreignId('building_id')->references('id')->on('buildings')->cascadeOnDelete();
            
            //Siapa "Unit" pemilik sah ruangan ini?
            $table->foreignId('unit_id')->references('id')->on('units');
            
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
