<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public object $booking)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->booking->status === 'confirmed'
            ? 'Your booking request is confirmed'
            : 'An update to your booking request';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.booking-status-updated');
    }
}
