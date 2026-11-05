<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $name;
    public string $otp;
    public string $emailSubject;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $otp, string $subject = 'Kode Verifikasi OTP - One Movies')
    {
        $this->name = $name;
        $this->otp  = $otp;
        $this->emailSubject = $subject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
            with: [
                'name' => $this->name,
                'otp'  => $this->otp,
            ],
        );
    }
}
