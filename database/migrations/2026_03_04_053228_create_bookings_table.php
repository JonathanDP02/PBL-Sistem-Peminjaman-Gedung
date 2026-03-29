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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('room_id')->references('id')->on('rooms')->cascadeOnDelete();
            $table->foreignId('workflow_id')->references('id')->on('workflows');
            $table->string('event_name', 200);
            $table->text('event_description')->nullable();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('current_step')->default(1);
            $table->string('status', 50)->default('Pending');
            $table->timestamps();

            // Indexing
            $table->index(['room_id', 'booking_date', 'start_time', 'end_time', 'status'], 'idx_bookings_conflict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
