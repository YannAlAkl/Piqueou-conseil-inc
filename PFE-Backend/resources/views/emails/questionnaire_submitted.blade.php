@extends('emails.layout')

@section('titre', $soumission->conclusion ? 'Votre questionnaire a été analysé' : 'Un nouveau questionnaire a été soumis')

@section('contenu')

    <p>Bonjour,</p>

    @if ($soumission->conclusion)
        <p>Le questionnaire « {{ $soumission->questionnaire->title }} » a été analysé et les résultats sont disponibles dans votre compte.</p>
    @else
        <p>Un client a soumis un questionnaire, disponible dans votre compte.</p>
    @endif

    <p>
        <a href="{{ $pourAdmin ? route('admin.submission.show', $soumission->id) : route('client.questionnaire.show', $soumission->questionnaire_id) }}" style="color:#ffffff; background-color:#1d4ed8; padding:10px 18px; text-decoration:none;">
        Voir le questionnaire

        </a>
    </p>

    <p>L'équipe Piqueou Conseil</p>

@endsection
