@extends('layouts.client')

@section('title', 'Espace client')
@section('subtitle', 'Bienvenue sur votre portail Piqueou Conseil.')

@section('content')

    <div class="client-card">
        <h2 class="client-card-title">Bonjour {{ Auth::user()->name }}</h2>
        <p class="text-sm text-gray-600">
            Votre compte est actif. Vous pouvez répondre au questionnaire préliminaire de
            cybersécurité. Un analyste ajoutera ensuite ses recommandations.
        </p>

        <div class="mt-4">
            <a href="{{ route('client.questionnaire.index') }}" class="client-btn client-btn-blue">
                Accéder à mes questionnaires
            </a>
        </div>
    </div>

@endsection
