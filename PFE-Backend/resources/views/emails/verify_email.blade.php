@extends('emails.layout')

@section('titre', 'Vérifiez votre adresse email')

@section('contenu')

    <p>Bonjour {{ $user->first_name }},</p>

    <p>
        Votre compte vient d'être activé par un administrateur. Il ne reste qu'une étape :
        confirmer votre adresse email pour accéder à votre espace.
    </p>

    <p style="margin:24px 0;">
        <a href="{{ $url }}" style="color:#ffffff; background-color:#22c55e; padding:12px 24px; border-radius:30px; text-decoration:none; font-weight:bold; display:inline-block;">
            Vérifier mon adresse email
        </a>
    </p>

    <p style="font-size:13px; color:#64748b;">
        Ce lien est valable 60 minutes. Passé ce délai, connectez-vous au portail et demandez
        un nouvel email depuis l'écran de vérification.
    </p>

    <p>Si vous n'êtes pas à l'origine de cette demande, ignorez simplement ce message.</p>

    <p>L'équipe Piqueou Conseil</p>

@endsection
