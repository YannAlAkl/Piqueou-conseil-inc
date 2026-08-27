@extends('layouts.analyst')

@section('title', 'Espace analyste')
@section('subtitle', 'Vue générale des dossiers qui vous sont confiés.')

@section('content')

    <div class="analyst-card">
        <h2 class="analyst-card-title">Bonjour {{ Auth::user()->name }}</h2>
        <p class="text-sm text-gray-600">
            Les questionnaires envoyés par les clients et assignés par l'administrateur
            apparaissent dans vos dossiers à analyser.
        </p>
    </div>

    <div class="analyst-stat">
        <p class="analyst-stat-value">{{ $total ?? 0 }}</p>
        <p class="analyst-stat-label">Dossier(s) en attente de votre analyse</p>
    </div>

    <div class="analyst-form-actions">
        <a href="{{ route('analyst.questionnaire.index') }}" class="analyst-btn analyst-btn-blue">
            Voir mes dossiers
        </a>
    </div>

@endsection
