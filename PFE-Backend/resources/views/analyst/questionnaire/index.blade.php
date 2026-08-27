@extends('layouts.analyst')

@section('title', 'Dossiers à analyser')
@section('subtitle', 'Les soumissions clients qui vous sont assignées.')

@section('content')

    @forelse ($questionnaires as $soumission)

        <div class="analyst-card">
            <h2 class="analyst-card-title">
                {{ $soumission->questionnaire->title ?? 'Questionnaire' }}
            </h2>

            <p class="text-sm text-gray-600">
                Client : {{ $soumission->user->name ?? 'Inconnu' }}
                @if ($soumission->user && $soumission->user->company_name)
                    ({{ $soumission->user->company_name }})
                @endif
            </p>

            <p class="mt-4 text-sm">
                Statut :
                @if ($soumission->status === 'submitted')
                    <span class="analyst-badge analyst-badge-yellow">À analyser</span>
                @elseif ($soumission->status === 'under_review')
                    <span class="analyst-badge analyst-badge-blue">En cours d'analyse</span>
                @elseif ($soumission->status === 'completed')
                    <span class="analyst-badge analyst-badge-green">Terminé</span>
                @else
                    <span class="analyst-badge analyst-badge-gray">{{ $soumission->status }}</span>
                @endif
            </p>

            @if ($soumission->submitted_at)
                <p class="analyst-meta">
                    Envoyé le {{ $soumission->submitted_at->format('d/m/Y à H:i') }}
                </p>
            @endif

            <div class="analyst-form-actions">
                <a href="{{ route('analyst.questionnaire.show', $soumission->id) }}"
                   class="analyst-btn analyst-btn-blue">
                    Analyser ce dossier
                </a>
            </div>
        </div>

    @empty
        <div class="analyst-card">
            <p>Aucun dossier à analyser pour le moment.</p>
        </div>
    @endforelse

@endsection
