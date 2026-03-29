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
        Schema::create('booking_attachments', function (Blueprint $table) {
            $table->uuid('attachment_id')->primary();
            $table->foreignUuid('booking_id')->references('booking_id')->on('bookings')->cascadeOnDelete();
            $table->foreignId('requirement_id')->nullable()->references('requirement_id')->on('workflow_requirements');
            $table->foreignId('uploader_id')->references('user_id')->on('users');
            $table->string('document_type', 150);
            $table->string('file_path', 255);
            $table->timestamp('uploaded_at')->useCurrent();

            // Indexing
            $table->index(['booking_id', 'uploader_id'], 'idx_attachments_booking');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
