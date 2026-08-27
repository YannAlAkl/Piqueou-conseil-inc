<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserQuestionnaire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnalystAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $soumission;
    public $analyste;

    public function __construct(UserQuestionnaire $soumission, User $analyste)
    {
        $this->soumission = $soumission;
        $this->analyste = $analyste;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Un nouveau dossier vous a été assigné',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.analyst_assigned',
        );
    }
}
