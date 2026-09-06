@extends('layouts.client')

@section('title', 'Espace client')
@section('subtitle', 'Bienvenue sur votre portail PIQUÉOU Conseil Inc.')

@section('content')

    <div class="client-ring-card">

        <div class="client-ring" style="background: conic-gradient(#018880 {{ $progress }}%, #e2e8f0 0);">
            <div class="client-ring-hole">
                <span class="client-ring-value">{{ $progress }}%</span>
                <span class="client-ring-legend">Complété</span>
            </div>
        </div>

        <div class="client-ring-text">
            <h2 class="client-card-title">Bonjour {{ Auth::user()->name }}</h2>

            <p class="text-sm text-gray-600">
                Vous avez répondu à {{ $answeredQuestions }} question(s) sur {{ $totalQuestions }}.
                Un analyste ajoute ensuite ses recommandations pour chacune de vos réponses.
            </p>

            <div class="client-steps">
                <div class="client-step {{ $stepNumber >= 1 ? 'client-step-done' : '' }}">Non commencé</div>
                <div class="client-step {{ $stepNumber >= 2 ? 'client-step-done' : '' }}">En cours</div>
                <div class="client-step {{ $stepNumber >= 3 ? 'client-step-done' : '' }}">Envoyé</div>
                <div class="client-step {{ $stepNumber >= 4 ? 'client-step-done' : '' }}">En analyse</div>
                <div class="client-step {{ $stepNumber >= 5 ? 'client-step-done' : '' }}">Terminé</div>
            </div>
        </div>

    </div>

    <div class="client-kpi-grid">

        <div class="client-kpi client-kpi-teal">
            <p class="client-kpi-label">Questionnaires disponibles</p>
            <p class="client-kpi-value">{{ count($questionnaires) }}</p>
            <p class="client-kpi-note">Publiés par Piquéou Conseil</p>
        </div>

        <div class="client-kpi client-kpi-amber">
            <p class="client-kpi-label">En cours</p>
            <p class="client-kpi-value">{{ $inProgress }}</p>
            <p class="client-kpi-note">Commencés ou en attente d'analyse</p>
        </div>

        <div class="client-kpi client-kpi-green">
            <p class="client-kpi-label">Dossiers terminés</p>
            <p class="client-kpi-value">{{ $completed }}</p>
            <p class="client-kpi-note">Analyse reçue</p>
        </div>

        <div class="client-kpi client-kpi-slate">
            <p class="client-kpi-label">Recommandations reçues</p>
            <p class="client-kpi-value">{{ $recommendations }}</p>
            <p class="client-kpi-note">Rédigées par votre analyste</p>
        </div>

    </div>

    <div class="client-card">
        <h2 class="client-card-title">Mes questionnaires</h2>

        @forelse ($questionnaires as $questionnaire)
            @php
                $submission = $submissions->get($questionnaire->id);
            @endphp

            <div class="client-row">

                <div class="client-row-main">
                    <p class="client-row-name">{{ $questionnaire->title }}</p>
                    <p class="client-row-meta">{{ count($questionnaire->questions) }} question(s)</p>

                    <div class="client-progress">
                        <div class="client-progress-fill" style="width: {{ $progressList[$questionnaire->id] }}%;"></div>
                    </div>
                </div>

                <div class="client-row-actions">
                    @if (!$submission)
                        <span class="client-badge client-badge-gray">Non commencé</span>
                    @elseif (in_array($submission->status, ['not_started', 'in_progress']))
                        <span class="client-badge client-badge-yellow">En cours</span>
                    @elseif ($submission->status === 'completed')
                        <span class="client-badge client-badge-green">Terminé</span>
                    @else
                        <span class="client-badge client-badge-blue">Envoyé</span>
                    @endif

                    <a href="{{ route('client.questionnaire.show', $questionnaire->id) }}"
                        class="client-btn client-btn-blue">
                        Ouvrir
                    </a>
                </div>

            </div>

        @empty
            <p class="text-sm text-gray-600">Aucun questionnaire disponible pour le moment.</p>
        @endforelse

    </div>

    <div class="client-card">
        <h2 class="client-card-title">Mon infolettre</h2>

        @if (Auth::user()->wants_newsletter)
            <p class="text-sm text-gray-600">
                Vous êtes inscrit à l'infolettre <strong>{{ $newsletterName }}</strong>.
                Vous la recevez chaque semaine par courriel.
            </p>
        @else
            <p class="text-sm text-gray-600">
                Vous n'êtes pas inscrit à l'infolettre. Vous pouvez le faire depuis votre profil.
            </p>
        @endif

        <div class="client-form-actions">
            <a href="{{ route('profile.edit') }}" class="client-btn client-btn-gray">Modifier mon profil</a>
        </div>
    </div>

@endsection
