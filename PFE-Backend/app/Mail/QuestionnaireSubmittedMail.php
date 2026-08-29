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

    public function __construct(UserQuestionnaire $soumission)
    {
        $this->soumission = $soumission;

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
