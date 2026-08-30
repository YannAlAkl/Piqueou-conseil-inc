<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserQuestionnaire;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuestionnaireSubmittedMail extends Mailable
    {
    use Queueable, SerializesModels;

    public $soumission;
    public $pourAdmin;

    public function __construct(UserQuestionnaire $soumission, bool $pourAdmin = false)
    {
        $this->soumission = $soumission;
        $this->pourAdmin = $pourAdmin;

    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Un nouveau questionnaire a été soumis',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.questionnaire_submitted',
        );
    }
}
