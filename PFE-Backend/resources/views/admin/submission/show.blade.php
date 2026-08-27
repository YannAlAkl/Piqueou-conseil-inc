@extends('layouts.admin')

@section('title', 'Détails du questionnaire')
@section('subtitle', $soumission->questionnaire->title ?? 'Questionnaire')

@section('content')

    <div class="admin-card">
        <div class="admin-form-grid">

            <div>
                <h2 class="admin-card-title">Client</h2>
                <div class="admin-info-list">
                    <div>
                        <p class="admin-info-label">Nom</p>
                        <p class="admin-info-value">{{ $soumission->user->name }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Email</p>
                        <p class="admin-info-value">{{ $soumission->user->email }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Entreprise</p>
                        <p class="admin-info-value">{{ $soumission->user->company_name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="admin-card-title">Dossier</h2>
                <div class="admin-info-list">
                    <div>
                        <p class="admin-info-label">Statut</p>
                        <p class="admin-info-value">
                            @if ($soumission->status === 'submitted')
                                <span class="admin-badge admin-badge-yellow">À assigner</span>
                            @elseif ($soumission->status === 'under_review')
                                <span class="admin-badge admin-badge-green">En analyse</span>
                            @else
                                <span class="admin-badge admin-badge-green">Terminé</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="admin-info-label">Analyste assigné</p>
                        <p class="admin-info-value">{{ $soumission->analyst ? $soumission->analyst->name : 'Non assigné' }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Envoyé le</p>
                        <p class="admin-info-value">{{ $soumission->submitted_at ? $soumission->submitted_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="admin-card mt-6">
        <h2 class="admin-card-title">Réponses du client</h2>

        @if ($soumission->questionnaire && $soumission->questionnaire->questions->isNotEmpty())
            <div class="admin-info-list">
                @foreach ($soumission->questionnaire->questions as $question)
                    @php
                        $reponse = $reponses->get($question->id);
                    @endphp
                    <div>
                        <p class="admin-info-label">{{ $question->position }}. {{ $question->question }}</p>
                        <p class="admin-info-value">{{ $reponse->answer ?? 'Pas de réponse enregistrée' }}</p>
                        @if ($reponse && $reponse->client_comment)
                            <p class="text-xs text-gray-500">Commentaire : {{ $reponse->client_comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">Aucune question associée disponible.</p>
        @endif
    </div>

    @if ($soumission->conclusion)
        <div class="admin-card mt-6">
            <h2 class="admin-card-title">Conclusion de l'analyste</h2>
            <p class="admin-info-value">{{ $soumission->conclusion }}</p>
        </div>
    @endif

    <div class="admin-form-actions mt-6">
        <a href="{{ route('admin.submission.index') }}" class="admin-btn admin-btn-gray">Retour à la liste</a>
    </div>

@endsection
