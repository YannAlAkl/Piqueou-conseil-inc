@extends('layouts.analyst')

@section('title', 'Espace analyste')
@section('subtitle', 'Vue générale des dossiers qui vous sont confiés.')

@section('content')

    <div class="analyst-card">
        <h2 class="analyst-card-title">Bonjour {{ Auth::user()->name }}</h2>
        <p class="text-sm text-gray-600">
            Voici l'état de vos dossiers. Les questionnaires envoyés par les clients et
            assignés par l'administrateur apparaissent dans « Dossiers à analyser ».
        </p>
    </div>

    <div class="analyst-kpi-grid">

        <div class="analyst-kpi analyst-kpi-amber">
            <p class="analyst-kpi-label">À analyser</p>
            <p class="analyst-kpi-value">{{ $total }}</p>
            <p class="analyst-kpi-note">Dossiers en attente de votre analyse</p>
        </div>

        <div class="analyst-kpi analyst-kpi-teal">
            <p class="analyst-kpi-label">Terminés</p>
            <p class="analyst-kpi-value">{{ $completed }}</p>
            <p class="analyst-kpi-note">Analyses envoyées au client</p>
        </div>

        <div class="analyst-kpi analyst-kpi-green">
            <p class="analyst-kpi-label">Recommandations</p>
            <p class="analyst-kpi-value">{{ $recommendations }}</p>
            <p class="analyst-kpi-note">Rédigées depuis le début</p>
        </div>

        <div class="analyst-kpi analyst-kpi-slate">
            <p class="analyst-kpi-label">Délai moyen</p>
            <p class="analyst-kpi-value">{{ $averageDelay }} j</p>
            <p class="analyst-kpi-note">Entre l'envoi du client et votre analyse</p>
        </div>

    </div>

    <div class="analyst-card">
        <h2 class="analyst-card-title">Répartition de vos dossiers</h2>

        @if ($totalSubmissions > 0)
            <div class="analyst-split">
                <div class="analyst-split-teal" style="width: {{ $completedPercent }}%;"></div>
                <div class="analyst-split-amber" style="width: {{ $pendingPercent }}%;"></div>
            </div>

            <div class="analyst-legend">
                <span class="analyst-legend-item">
                    <span class="analyst-legend-dot analyst-dot-teal"></span>
                    Terminés — {{ $completed }} ({{ $completedPercent }} %)
                </span>
                <span class="analyst-legend-item">
                    <span class="analyst-legend-dot analyst-dot-amber"></span>
                    À analyser — {{ $total }} ({{ $pendingPercent }} %)
                </span>
            </div>
        @else
            <p class="text-sm text-gray-600">Aucun dossier ne vous est assigné pour le moment.</p>
        @endif
    </div>

    <div class="analyst-card">
        <h2 class="analyst-card-title">Dossiers terminés par mois</h2>
        <p class="analyst-meta">Sur les 6 derniers mois</p>

        <div class="analyst-chart">
            @foreach ($months as $month)
                <div class="analyst-chart-col">
                    <span class="analyst-bar-value">{{ $month['value'] }}</span>
                    <div class="analyst-bar" style="height: {{ $month['height'] }}%;"></div>
                    <span class="analyst-bar-label">{{ $month['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="analyst-card">
        <h2 class="analyst-card-title">Derniers dossiers</h2>

        @forelse ($latest as $submission)
            <div class="analyst-row">

                <div class="analyst-row-main">
                    <p class="analyst-row-name">{{ $submission->user->name ?? 'Client inconnu' }}</p>
                    <p class="analyst-row-meta">
                        {{ $submission->questionnaire->title ?? 'Questionnaire' }}
                        @if ($submission->submitted_at)
                            — envoyé le {{ $submission->submitted_at->format('d/m/Y') }}
                        @endif
                    </p>
                </div>

                <div class="analyst-row-actions">
                    @if ($submission->status === 'completed')
                        <span class="analyst-badge analyst-badge-green">Terminé</span>
                    @else
                        <span class="analyst-badge analyst-badge-yellow">À analyser</span>
                    @endif

                    @if ($submission->status === 'under_review')
                        <a href="{{ route('analyst.questionnaire.show', $submission->id) }}"
                            class="analyst-btn analyst-btn-blue">
                            Analyser
                        </a>
                    @endif
                </div>

            </div>

        @empty
            <p class="text-sm text-gray-600">Aucun dossier pour le moment.</p>
        @endforelse

        <div class="analyst-form-actions">
            <a href="{{ route('analyst.questionnaire.index') }}" class="analyst-btn analyst-btn-gray">
                Voir tous mes dossiers
            </a>
        </div>
    </div>

@endsection
