<?php

namespace App\Services;

use App\Models\BookingLog;
use Illuminate\Support\Facades\Auth;

class LoggerService
{
    /**
     * Mencatat setiap pergerakan status/dokumen pada reservasi.
     *
     * @param int $bookingId ID Reservasi (Booking)
     * @param string $action Aksi (Gunakan huruf kapital, ex: 'SUBMITTED', 'APPROVED', 'REVISED')
     * @param int|null $stepId ID dari workflow_steps jika ada hubungannya dengan rantai pejabat
     * @param string|null $notes Catatan (Alasan tolak, pesan revisi, dll)
     */
    public static function logAction($bookingId, $action, $stepId = null, $notes = null)
    {
        BookingLog::create([
            'booking_id' => $bookingId,
            'actor_id'   => Auth::id(), // Otomatis mengambil user yang sedang login
            'step_id'    => $stepId,
            'action'     => strtoupper($action), // Paksa menjadi huruf besar agar seragam di database
            'notes'      => $notes,
        ]);
    }
}