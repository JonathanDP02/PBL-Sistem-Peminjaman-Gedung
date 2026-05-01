<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $notes
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Peminjaman Ruangan Ditolak #'.$this->booking->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-rejected',
        );
    }
}
