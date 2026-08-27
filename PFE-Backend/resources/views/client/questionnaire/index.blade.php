@extends('layouts.client')

@section('title', 'Mes questionnaires')
@section('subtitle', 'Répondez au questionnaire préliminaire avant l\'analyse de nos experts.')

@section('content')

    @forelse ($questionnaires as $questionnaire)

        @php
            $soumission = $mesSoumissions->get($questionnaire->id);
        @endphp

        <div class="client-card">
            <h2 class="client-card-title">{{ $questionnaire->title }}</h2>
            <p class="text-sm text-gray-600">{{ $questionnaire->description }}</p>

            <p class="mt-4 text-sm">
                Statut :
                @if (! $soumission)
                    <span class="client-badge client-badge-gray">Non commencé</span>
                @elseif (in_array($soumission->status, ['not_started', 'in_progress']))
                    <span class="client-badge client-badge-yellow">En cours</span>
                @elseif ($soumission->status === 'completed')
                    <span class="client-badge client-badge-green">Terminé</span>
                @else
                    <span class="client-badge client-badge-blue">Envoyé</span>
                @endif
            </p>

            <div class="mt-4">
                <a href="{{ route('client.questionnaire.show', $questionnaire->id) }}" class="client-btn client-btn-blue">
                    @if (! $soumission)
                        Commencer
                    @elseif (in_array($soumission->status, ['not_started', 'in_progress']))
                        Continuer
                    @else
                        Voir mes réponses
                    @endif
                </a>
            </div>
        </div>

    @empty
        <div class="client-card">
            <p>Aucun questionnaire disponible pour le moment.</p>
        </div>
    @endforelse

@endsection
