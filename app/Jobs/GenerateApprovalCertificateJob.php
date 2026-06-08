<?php

namespace App\Jobs;

use App\Mail\ApprovalCertificateMail;
use App\Models\Booking;
use App\Support\QrCodeHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
            'user.unit',
            'workflow',
            'approvals.bookingStep.position',
            'approvals.bookingStep.position.unit',
            'approvals.step.position',
            'approvals.approver.position',
        ])->findOrFail($this->bookingId);

        // Ambil approval terakhir (pejabat tertinggi yang approve) excluding Wadir II
        $lastApproval = $booking->approvals
            ->where('approval_status', 'Approved')
            ->filter(function ($a) {
                $posName = strtolower($a->bookingStep->position->name ?? $a->step->position->name ?? '');
                $hasTwo = str_contains($posName, 'wadir ii') || str_contains($posName, 'wadir 2') || str_contains($posName, 'wakil direktur ii');
                $hasThree = str_contains($posName, 'wadir iii') || str_contains($posName, 'wadir 3') || str_contains($posName, 'wakil direktur iii');
                $isWadir2 = $hasTwo && ! $hasThree;

                return ! $isWadir2;
            })
            ->sortByDesc(fn ($a) => $a->bookingStep->step_order ?? $a->step->step_order ?? 0)
            ->first();

        // Generate QR Code menggunakan GD (tidak perlu Imagick)
        $qrCode = QrCodeHelper::generateBase64(config('app.url').'/validate/'.$booking->id);

        // Render Blade ke PDF
        $pdf = Pdf::loadView('pdf.surat-izin', [
            'booking' => $booking,
            'lastApproval' => $lastApproval,
            'qrCode' => $qrCode,
        ])->setPaper('a4', 'portrait');

        // Save ke storage private dengan path: certificates/BOOKING-{id}-{date}.pdf
        $fileName = "BOOKING-{$booking->id}-".now()->format('Y-m-d').'.pdf';
        $pdfPath = "certificates/{$fileName}";
        Storage::disk('private')->put($pdfPath, $pdf->output());

        // Update booking dengan pdf_path
        $booking->update([
            'pdf_path' => $pdfPath,
        ]);

        // Kirim email ke peminjam dengan PDF attachment
        Mail::to($booking->user->email)->send(new ApprovalCertificateMail($booking, $pdfPath));
    }
}
