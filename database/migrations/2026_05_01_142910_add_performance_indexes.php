<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah index unit_id di tabel users (untuk filter approver per unit)
        Schema::table('users', function (Blueprint $table) {
            $table->index('unit_id', 'idx_users_unit_id');
            $table->index('position_id', 'idx_users_position_id'); // sering difilter untuk cari approver
            $table->index('role_id', 'idx_users_role_id');         // sering difilter untuk checkRole middleware
        });

        // Tambah index tambahan di booking_logs untuk query dashboard admin (10 terbaru)
        Schema::table('booking_logs', function (Blueprint $table) {
            $table->index(['created_at'], 'idx_booking_logs_created_at');
            $table->index(['booking_id', 'action'], 'idx_booking_logs_tracking');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_unit_id');
            $table->dropIndex('idx_users_position_id');
            $table->dropIndex('idx_users_role_id');
        });

        Schema::table('booking_logs', function (Blueprint $table) {
            $table->dropIndex('idx_booking_logs_created_at');
            $table->dropIndex('idx_booking_logs_tracking');
        });
    }
};
