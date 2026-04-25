<?php

namespace App\Http\Controllers;

use App\Models\BookingAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingAttachmentController extends Controller
{
    public function show(int $id, int $attachmentId): mixed
{
    $attachment = BookingAttachment::where('id', $attachmentId)
        ->whereHas('booking', function ($q) use ($id) {
            $q->where('id', $id)
              ->where('user_id', Auth::id());
        })
        ->firstOrFail();

    $fullPath = Storage::disk('private')->path($attachment->file_path);

    if (!Storage::disk('private')->exists($attachment->file_path)) {
        abort(404, 'File tidak ditemukan.');
    }

    return response()->file($fullPath);
}
}