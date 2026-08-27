@extends('emails.layout')

@section('titre', 'Nouvelle demande de compte client')

@section('contenu')

    <p>Bonjour,</p>

    <p>Un nouveau client vient de s'inscrire et attend l'activation de son compte.</p>

    <ul>
        <li>Nom : {{ $user->first_name }} {{ $user->last_name }}</li>
        <li>Email : {{ $user->email }}</li>
        <li>Entreprise : {{ $user->company_name ?? 'Non renseignée' }}</li>
        <li>Téléphone : {{ $user->phone ?? 'Non renseigné' }}</li>
        <li>Inscrit le : {{ $user->created_at->format('d/m/Y H:i') }}</li>
    </ul>

    <p>
        <a href="{{ $lienAdmin }}" style="color:#ffffff; background-color:#22c55e; padding:10px 18px; text-decoration:none;">
            Activer ce compte
        </a>
    </p>

@endsection
