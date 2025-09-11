<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationMails extends Mailable
{
    use Queueable, SerializesModels;

    public array $mailData;

    public function __construct(array $mailData)
    {
        $this->mailData = $mailData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailData['subject'] ?? 'Hi there!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notifications',
            with: $this->mailData,
        );
    }


    public function attachments(): array
    {
        return [];
    }
}
