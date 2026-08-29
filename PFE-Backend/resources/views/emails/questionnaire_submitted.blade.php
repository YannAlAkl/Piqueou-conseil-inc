@extends('emails.layout')

@section('titre', 'Un nouveau questionnaire a été soumis')

@section('contenu')

    <p>Bonjour,</p>

    <p>Un client vient de soumettre un questionnaire.</p>

    <ul>
        <li>Client : {{ $soumission->user->name }}</li>
        <li>Entreprise : {{ $soumission->user->company_name ?? 'Non renseignée' }}</li>
        <li>Questionnaire : {{ $soumission->questionnaire->title }}</li>
        <li>Envoyé le : {{ $soumission->submitted_at ? $soumission->submitted_at->format('d/m/Y H:i') : '-' }}</li>
    </ul>

    <p>
        <a href="{{ route('admin.submission.index', $soumission->id) }}" style="color:#ffffff; background-color:#1d4ed8; padding:10px 18px; text-decoration:none;">
            Voir le dossier
        </a>
    </p>

    <p>L'équipe Piqueou Conseil</p>

@endsection
