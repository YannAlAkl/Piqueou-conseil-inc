@extends('layouts.admin')

@section('title', 'Détails du questionnaire')
@section('subtitle', $soumission->questionnaire->title)

@section('content')

    <div class="admin-card">
        <h3 class="admin-card-title">Informations générales</h3>

        <div class="admin-form-grid">
            <div>
                <p class="admin-info-label">Client</p>
                <p class="admin-info-value">{{ $soumission->user->name }}</p>
            </div>

            <div>
                <p class="admin-info-label">Entreprise</p>
                <p class="admin-info-value">{{ $soumission->user->company_name ?? '-' }}</p>
            </div>

            <div>
                <p class="admin-info-label">Analyste assigné</p>
                <p class="admin-info-value">{{ $soumission->analyst ? $soumission->analyst->name : '-' }}</p>
            </div>

            <div>
                <p class="admin-info-label">Envoyé le</p>
                <p class="admin-info-value">
                    {{ $soumission->submitted_at ? $soumission->submitted_at->format('d/m/Y H:i') : '-' }}
                </p>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <h3 class="admin-card-title">Questions et réponses</h3>

        @foreach ($soumission->questionnaire->questions as $question)
            @php $reponse = $reponses->get($question->id); @endphp

            <div style="border:1px solid #e2e8f0; border-radius:12px; padding:18px; margin-bottom:16px;">

                <p style="font-weight:600; color:#003a36; margin-bottom:12px;">
                    {{ $loop->iteration }}. {{ $question->question }}
                </p>

                <p class="admin-info-label">Réponse du client</p>
                <div class="contenu-html admin-info-value" style="white-space: pre-line; margin-bottom:12px;">
                    {!!$reponse && $reponse->answer ? $reponse->answer : 'Pas de réponse'!!}
                </div>

                @if ($reponse && $reponse->client_comment)
                    <p class="admin-info-label">Commentaire du client</p>
                    <p class=" admin-info-value" style="white-space: pre-line; margin-bottom:12px;">
                        {{ $reponse->client_comment }}
                    </p>
                @endif

                <div style="background:#f0fdf4; border-left:3px solid #22c55e; border-radius:8px; padding:12px 14px;">
                    <p class="admin-info-label">Recommandation de l'analyste</p>
                    <div class="contenu-html admin-info-value" style="white-space: pre-line;">
                        {!! $reponse && $reponse->analyst_recommendation ? $reponse->analyst_recommendation : 'Aucune recommandation' !!}
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    <div class="admin-card">
        <h3 class="admin-card-title">Conclusion de l'analyste</h3>
        <div class="contenu-html admin-info-value" style="white-space: pre-line;">
            {!! $soumission->conclusion ?? 'Pas encore de conclusion' !!}
        </div>
    </div>

    <div class="admin-form-actions">
        <a href="{{ route('admin.questionnaire.index') }}" class="admin-btn admin-btn-gray">
            Retour à la liste
        </a>
    </div>

@endsection
