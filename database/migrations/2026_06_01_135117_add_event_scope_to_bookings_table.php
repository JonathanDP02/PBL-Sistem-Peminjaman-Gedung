<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds event_scope column to bookings to determine which tier of workflows
     * needs to be stitched together during dynamic bridging.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('event_scope', 50)
                ->default('Internal')
                ->after('event_description')
                ->comment('Internal = Tier 1+2 only; Lintas Jurusan = Tier 1+2+3 (BEM+Pusat)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('event_scope');
        });
    }
};
