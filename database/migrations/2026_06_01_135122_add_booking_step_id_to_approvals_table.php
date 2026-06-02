<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * - Makes step_id nullable (backward compat for legacy approvals linked to workflow_steps).
     * - Adds booking_step_id FK pointing to the new instantiated booking_steps table.
     *   New approvals will use booking_step_id; step_id is kept for old records.
     */
    public function up(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            // Make step_id nullable so old and new records can coexist safely
            $table->foreignId('step_id')->nullable()->change();

            // New FK for booking_steps (instantiated chain)
            $table->foreignId('booking_step_id')
                ->nullable()
                ->after('step_id')
                ->constrained('booking_steps')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('booking_step_id');
            $table->foreignId('step_id')->nullable(false)->change();
        });
    }
};
