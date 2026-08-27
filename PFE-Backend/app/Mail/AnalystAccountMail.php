<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnalystAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $motDePasse;

    public function __construct(User $user, string $motDePasse)
    {
        $this->user = $user;
        $this->motDePasse = $motDePasse;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vos identifiants de connexion - Portail Piqueou',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.analyst_account',
        );
    }
}
