<?php

// app/Mail/UnlockAccountMail.php
namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UnlockAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Buka Blokir Akun Anda',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.unlock_account',
            with: [
                'url' => route('unlock.account', ['token' => $this->token]),
            ],
        );
    }
}
