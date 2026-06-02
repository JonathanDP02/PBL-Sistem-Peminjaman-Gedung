<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('booking_end_date')->nullable()->after('booking_date');
        });

        // Copy existing booking_date values to booking_end_date
        DB::table('bookings')->update([
            'booking_end_date' => DB::raw('booking_date'),
        ]);

        Schema::table('bookings', function (Blueprint $table) {
            $table->date('booking_end_date')->nullable(false)->change();

            // Drop old single-day conflict index
            $table->dropIndex('idx_bookings_conflict');

            // Add new multi-day conflict index
            $table->index(
                ['room_id', 'booking_date', 'booking_end_date', 'start_time', 'end_time', 'status'],
                'idx_bookings_conflict'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('idx_bookings_conflict');

            $table->index(
                ['room_id', 'booking_date', 'start_time', 'end_time', 'status'],
                'idx_bookings_conflict'
            );

            $table->dropColumn('booking_end_date');
        });
    }
};
