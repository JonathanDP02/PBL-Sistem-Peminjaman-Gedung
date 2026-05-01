<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingPdfController extends Controller
{
    public function generate(int $bookingId): mixed
    {
        $booking = Booking::with([
            'room',
            'user',
            'workflow',
            'approvals.step.position',
            'approvals.approver',
        ])->where('id', $bookingId)
          ->where('user_id', Auth::id())
          ->firstOrFail();

        $qrCode = QrCode::format('svg')
            ->size(120)
            ->generate(url('/validate/' . $booking->id));

        $pdf = Pdf::loadView('pdf.surat-izin', [
            'booking' => $booking,
            'qrCode'  => $qrCode,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("surat-izin-booking-{$booking->id}.pdf");
    }

    public function preview(string $bookingId)
    {
        $bookingId = (int) $bookingId;

        $booking = Booking::with([
            'room',
            'user',
            'workflow',
            'approvals.step.position',
            'approvals.approver',
        ])->findOrFail($bookingId);

        // Cek akses: owner, approver, atau superadmin bisa preview
        if (Auth::check()) {
            $user = Auth::user();
            $isOwner = $user->id === $booking->user_id;
            $isApprover = $user->role->name === 'Approver';
            $isSuperAdmin = $user->role->name === 'SuperAdmin';
            
            if (!$isOwner && !$isApprover && !$isSuperAdmin) {
                abort(403, 'Anda tidak memiliki akses ke resource ini');
            }
        } else {
            abort(401, 'Silakan login terlebih dahulu');
        }

        $qrCode = QrCode::format('svg')
            ->size(120)
            ->generate(url('/validate/' . $booking->id));

        return view('pdf.surat-izin', [
            'booking' => $booking,
            'qrCode'  => $qrCode,
        ]);
    }
}