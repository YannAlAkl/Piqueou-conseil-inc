@extends('emails.layout')

@section('titre', 'Vos identifiants de connexion')

@section('contenu')

    <p>Bonjour {{ $user->first_name }},</p>

    <p>Un compte analyste vient d'être créé pour vous sur le portail Piqueou Conseil.</p>

    <ul>
        <li>Email : {{ $user->email }}</li>
        <li>Mot de passe : {{ $motDePasse }}</li>
    </ul>

    <p>
        <a href="{{ route('login') }}" style="color:#ffffff; background-color:#22c55e; padding:10px 18px; text-decoration:none;">
            Se connecter
        </a>
    </p>

    <p>Pensez à changer ce mot de passe après votre première connexion.</p>

@endsection
