<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the booking_steps table which stores the instantiated approval chain
     * for each booking. Each row represents one approval step that was dynamically
     * bridged at booking creation time from the unit/jurusan/pusat workflows.
     */
    public function up(): void
    {
        Schema::create('booking_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('position_id')->constrained('positions');
            $table->integer('step_order');
            $table->boolean('requires_attachment')->default(false);
            $table->string('tier_label', 100)->nullable(); // e.g. 'Internal', 'Jurusan', 'Pusat'
            $table->timestamps();

            // Fast lookup: find active step for a booking
            $table->index(['booking_id', 'step_order'], 'idx_booking_steps_lookup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_steps');
    }
};
