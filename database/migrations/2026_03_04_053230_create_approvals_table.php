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
        Schema::create('approvals', function (Blueprint $table) {
            $table->uuid('approval_id')->primary();
            $table->foreignUuid('booking_id')->references('booking_id')->on('bookings')->cascadeOnDelete();
            $table->foreignId('approver_id')->references('user_id')->on('users');
            $table->foreignId('step_id')->references('step_id')->on('workflow_steps');
            $table->string('approval_status', 50);
            $table->text('notes')->nullable();
            $table->text('signature_image')->nullable();
            $table->text('qr_code')->nullable();
            $table->timestamp('approved_at')->useCurrent();

            // Indexing
            $table->index(['booking_id', 'step_id'], 'idx_approvals_tracking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_attachments');
    }
};
