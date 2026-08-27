@extends('emails.layout')

@section('titre', 'Votre inscription a bien été enregistrée')

@section('contenu')

    <p>Bonjour {{ $user->first_name }},</p>

    <p>
        Nous avons bien reçu votre demande d'inscription avec l'adresse
        <strong>{{ $user->email }}</strong>.
    </p>

    <p>
        Votre compte est en attente de validation. Un administrateur va l'activer très
        prochainement et vous recevrez un email dès qu'il sera actif.
    </p>

    <p>Merci de votre confiance.</p>

    <p>L'équipe Piqueou Conseil</p>

@endsection
