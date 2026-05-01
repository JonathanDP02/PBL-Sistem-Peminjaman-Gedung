<?php

namespace App\Jobs;

use App\Mail\ApprovalCertificateMail;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateApprovalCertificateJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $bookingId
    ) {}

    /**
     * Generate PDF certificate dan kirim email ke peminjam
     */
    public function handle(): void
    {
        $booking = Booking::with([
            'room',
            'user',
            'workflow',
            'approvals.step.position',
            'approvals.approver',
        ])->findOrFail($this->bookingId);

        // Generate QR Code as PNG Base64 for PDF compatibility
        $qrCode = base64_encode(QrCode::format('png')
            ->size(120)
            ->generate(url(route('booking.validate', $booking->id))));

        // Render Blade ke PDF
        $pdf = Pdf::loadView('pdf.surat-izin', [
            'booking' => $booking,
            'qrCode' => $qrCode,
        ])->setPaper('a4', 'portrait');

        // Save ke storage private dengan path: certificates/BOOKING-{id}-{date}.pdf
        $fileName = "BOOKING-{$booking->id}-".now()->format('Y-m-d').'.pdf';
        $pdfPath = "certificates/{$fileName}";
        Storage::disk('local')->put($pdfPath, $pdf->output());

        // Update booking dengan pdf_path
        $booking->update([
            'pdf_path' => $pdfPath,
        ]);

        // Kirim email ke peminjam dengan PDF attachment
        Mail::send(new ApprovalCertificateMail($booking, $pdfPath));
    }
}
