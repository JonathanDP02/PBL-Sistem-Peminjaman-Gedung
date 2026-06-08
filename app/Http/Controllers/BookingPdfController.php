<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Support\QrCodeHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingPdfController extends Controller
{
    public function generate(int $bookingId): mixed
    {
        $booking = Booking::with([
            'room',
            'user',
            'workflow',
            'approvals.step.position',
            'approvals.bookingStep.position.unit',
            'approvals.approver',
        ])->findOrFail($bookingId);

        $user = Auth::user();
        $isOwner = $user->id === $booking->user_id;
        $isApprover = $user->role->name === 'Penyetuju';
        $isAdminUnit = $user->role->name === 'Administrator Unit' && $booking->room->unit_id === $user->unit_id;
        $isSuperAdmin = $user->role->name === 'Administrator Utama';

        if (! $isOwner && ! $isApprover && ! $isAdminUnit && ! $isSuperAdmin) {
            abort(403, 'Anda tidak memiliki akses ke resource ini.');
        }

        if ($booking->status !== 'Approved') {
            abort(403, 'PDF belum tersedia karena pengajuan belum disetujui sepenuhnya.');
        }

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

        $qrCode = QrCodeHelper::generateBase64(config('app.url').'/validate/'.$booking->id);

        $pdf = Pdf::loadView('pdf.surat-izin', [
            'booking' => $booking,
            'lastApproval' => $lastApproval,
            'qrCode' => $qrCode,
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
            'approvals.bookingStep.position.unit',
            'approvals.approver',
        ])->findOrFail($bookingId);

        // Cek akses: owner, approver, atau superadmin bisa preview
        if (Auth::check()) {
            $user = Auth::user();
            $isOwner = $user->id === $booking->user_id;
            $isApprover = $user->role->name === 'Penyetuju';
            $isSuperAdmin = $user->role->name === 'Administrator Utama';

            if (! $isOwner && ! $isApprover && ! $isSuperAdmin) {
                abort(403, 'Anda tidak memiliki akses ke resource ini');
            }
        } else {
            abort(401, 'Silakan login terlebih dahulu');
        }

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

        $qrCode = QrCodeHelper::generateBase64(config('app.url').'/validate/'.$booking->id);

        return view('pdf.surat-izin', [
            'booking' => $booking,
            'lastApproval' => $lastApproval,
            'qrCode' => $qrCode,
        ]);
    }

    public function bulkDownload(): mixed
    {
        // Hanya Admin_Unit yang boleh akses
        $user = Auth::user();

        $bookings = Booking::with([
            'room',
            'user',
            'approvals.step.position',
            'approvals.approver',
        ])->where('status', 'Approved')
            ->whereHas('room', fn ($q) => $q->where('unit_id', $user->unit_id))
            ->whereNotNull('pdf_path')
            ->get();

        if ($bookings->isEmpty()) {
            return response()->json(['error' => 'Tidak ada surat izin yang tersedia.'], 404);
        }

        $zip = new \ZipArchive;
        $zipFileName = 'surat-izin-bulk-'.now()->format('Ymd-His').'.zip';
        $zipPath = storage_path("app/private/permits/{$zipFileName}");

        // Ensure directory exists
        if (! is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        if ($zip->open($zipPath, \ZipArchive::CREATE) !== true) {
            return response()->json(['error' => 'Gagal membuat file ZIP.'], 500);
        }

        foreach ($bookings as $booking) {
            // Use Storage facade to support faking in tests
            if (Storage::disk('private')->exists($booking->pdf_path)) {
                $fileContent = Storage::disk('private')->get($booking->pdf_path);
                $zip->addFromString("booking-{$booking->id}.pdf", $fileContent);
            }
        }

        $zip->close();

        return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}
