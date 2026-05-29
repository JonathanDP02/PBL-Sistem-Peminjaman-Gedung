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
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['workflow_id']);
            $table->dropColumn('workflow_id');
        });

        Schema::table('workflows', function (Blueprint $table) {
            $table->foreignId('room_id')->nullable()->after('unit_id')->constrained('rooms')->nullOnDelete();
            
            // Validate: 1 unit can only have 1 workflow per room
            $table->unique(['unit_id', 'room_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workflows', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropUnique(['unit_id', 'room_id']);
            $table->dropColumn('room_id');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreignId('workflow_id')->nullable()->constrained('workflows')->nullOnDelete();
        });
    }
};
