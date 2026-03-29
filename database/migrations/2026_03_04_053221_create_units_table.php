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
            $table->id('unit_id')->primary();
            $table->unsignedBigInteger('parent_id')->nullable(); 
            
            $table->enum('level', ['Pusat', 'Jurusan', 'Organisasi'])->default('Organisasi');
            $table->string('unit_name', 150);
            $table->string('description', 255)->nullable();
            $table->timestamps();
        });

        // Eksekusi relasi setelah tabel dipastikan ada
        Schema::table('units', function (Blueprint $table) {
            $table->foreign('parent_id')->references('unit_id')->on('units')->cascadeOnDelete();
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
