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

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $otp)
    {
        $this->name = $name;
        $this->otp  = $otp;
        $this->subject = 'Kode Verifikasi Pendaftaran';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Pendaftaran',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Halo " . htmlspecialchars($this->name) . ",</p><p>Kode verifikasi pendaftaran akun Anda adalah: <strong>{$this->otp}</strong></p><p>Kode ini berlaku selama 10 menit. Jangan berikan kode ini kepada siapa pun.</p><p>Jika Anda tidak merasa melakukan pendaftaran ini, abaikan email ini.</p>",
        );
    }
}
