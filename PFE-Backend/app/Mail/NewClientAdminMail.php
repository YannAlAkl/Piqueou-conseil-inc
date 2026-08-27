<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewClientAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $lienAdmin;

    public function __construct(User $user, string $lienAdmin)
    {
        $this->user = $user;
         $this->lienAdmin = $lienAdmin;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande de compte client',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new_client_admin',
        );
    }
}
