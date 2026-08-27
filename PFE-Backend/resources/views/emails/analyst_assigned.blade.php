@extends('emails.layout')

@section('titre', 'Un nouveau dossier vous a été assigné')

@section('contenu')

    <p>Bonjour {{ $analyste->first_name }},</p>

    <p>L'administrateur vient de vous assigner un dossier à analyser.</p>

    <ul>
        <li>Client : {{ $soumission->user->name }}</li>
        <li>Entreprise : {{ $soumission->user->company_name ?? 'Non renseignée' }}</li>
        <li>Questionnaire : {{ $soumission->questionnaire->title }}</li>
        <li>Envoyé le : {{ $soumission->submitted_at ? $soumission->submitted_at->format('d/m/Y H:i') : '-' }}</li>
    </ul>

    <p>
        <a href="{{ route('analyst.dashboard') }}" style="color:#ffffff; background-color:#1d4ed8; padding:10px 18px; text-decoration:none;">
            Voir le dossier
        </a>
    </p>

    <p>L'équipe Piqueou Conseil</p>

@endsection
