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
        Schema::create('units', function (Blueprint $table) {
            $table->uuid('unit_id')->primary();
            
            //(Bisa null jika dia adalah Pusat / Level 1)
            $table->foreignUuid('parent_id')->nullable()->references('unit_id')->on('units')->cascadeOnDelete();
            
            // Tingkatan hierarki
            $table->enum('level', ['Pusat', 'Jurusan', 'Organisasi'])->default('Organisasi');
            
            $table->string('unit_name', 150);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
