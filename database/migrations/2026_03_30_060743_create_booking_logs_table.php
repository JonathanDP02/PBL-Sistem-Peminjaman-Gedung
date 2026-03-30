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
        Schema::create('booking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            
            // Siapa aktor yang melakukan tindakan ini? (Bisa Mahasiswa, Admin, atau Approver)
            $table->foreignId('actor_id')->references('id')->on('users'); 
            
            // Di tahap mana tindakan ini terjadi? 
            // Nullable karena jika Mahasiswa yang merevisi/mengajukan, mereka tidak terikat step persetujuan.
            $table->foreignId('step_id')->nullable()->references('id')->on('workflow_steps');
            
            // Kata kerja mutlak: 'SUBMITTED', 'APPROVED', 'REJECTED', 'REVISED', 'DISPOSITION_ASKED', 'DISPOSITION_ADDED'
            $table->string('action', 50); 
            
            // Penjelasan detail: Alasan penolakan, catatan disposisi, atau pesan revisi dari mahasiswa
            $table->text('notes')->nullable(); 
            
            $table->timestamps();

            // Indexing krusial: Mempercepat query saat UI Mahasiswa ingin merender "Timeline Status"
            $table->index(['booking_id', 'created_at'], 'idx_booking_timeline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_logs');
    }
};
