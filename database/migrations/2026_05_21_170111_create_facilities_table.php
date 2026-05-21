<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel rooms (Gedung & Ruangan)
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            
            // Detail Fasilitas
            $table->string('name', 150); // Contoh: Proyektor Laser
            $table->string('category', 100); // Contoh: Visual, Audio, IT, Furnitur
            $table->integer('quantity')->default(1); // Jumlah barang
            
            // Status kondisi barang
            $table->enum('status', ['Tersedia', 'Dipakai', 'Maintenance', 'Rusak'])->default('Tersedia');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};