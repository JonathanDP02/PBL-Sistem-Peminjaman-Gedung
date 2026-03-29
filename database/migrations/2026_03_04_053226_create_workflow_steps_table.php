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
       Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id('step_id')->primary();
            $table->foreignId('workflow_id')->references('workflow_id')->on('workflows')->cascadeOnDelete();
            $table->foreignId('position_id')->references('position_id')->on('positions');
            $table->integer('step_order');
            $table->boolean('requires_attachment')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
    }
};
