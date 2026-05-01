<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovalNeededMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public User $approver
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Persetujuan Diperlukan: Peminjaman Ruangan #'.$this->booking->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approval-needed',
        );
    }
}
