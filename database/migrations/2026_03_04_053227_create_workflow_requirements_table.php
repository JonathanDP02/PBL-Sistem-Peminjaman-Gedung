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
         Schema::create('workflow_requirements', function (Blueprint $table) {
            $table->uuid('requirement_id')->primary();
            $table->foreignUuid('workflow_id')->references('workflow_id')->on('workflows')->cascadeOnDelete();
            $table->string('document_name', 150);
            $table->boolean('is_mandatory')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_requirements');
    }
};
