@extends('layouts.admin')

@section('title', 'Détails de l\'événement')
@section('subtitle', $evenement->title)

@section('content')

    <div class="admin-card">
        <div class="admin-form-grid">

            <div>
                <h2 class="admin-card-title">Informations générales</h2>
                <div class="admin-info-list">
                    <div>
                        <p class="admin-info-label">Titre</p>
                        <p class="admin-info-value">{{ $evenement->title }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Lieu</p>
                        <p class="admin-info-value">{{ $evenement->location ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Date de début</p>
                        <p class="admin-info-value">{{ $evenement->start_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Date de fin</p>
                        <p class="admin-info-value">{{ $evenement->end_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Horaire</p>
                        <p class="admin-info-value">{{ $evenement->schedule ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="admin-card-title">Statut et métadonnées</h2>
                <div class="admin-info-list">
                    <div>
                        <p class="admin-info-label">Statut</p>
                        <p class="admin-info-value">
                            @if ($evenement->is_published)
                                <span class="admin-badge admin-badge-green">Publié</span>
                            @else
                                <span class="admin-badge admin-badge-yellow">Brouillon</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="admin-info-label">Créé par</p>
                        <p class="admin-info-value">{{ $evenement->creator?->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Créé le</p>
                        <p class="admin-info-value">{{ $evenement->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if ($evenement->link_url)
                        <div>
                            <p class="admin-info-label">Lien</p>
                            <p class="admin-info-value">
                                <a href="{{ $evenement->link_url }}" target="_blank" class="text-blue-600 underline">
                                    {{ $evenement->link_label ?? $evenement->link_url }}
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div class="mt-6">
            <h2 class="admin-card-title">Description</h2>
            <div class="admin-info-value" style="white-space: pre-wrap;">{{ $evenement->description }}</div>
        </div>

        <div class="admin-form-actions mt-6">
            <a href="{{ route('admin.events.edit', $evenement->id) }}" class="admin-btn admin-btn-yellow">Modifier</a>
            <a href="{{ route('admin.events.index') }}" class="admin-btn admin-btn-gray">Retour à la liste</a>
        </div>
    </div>

@endsection
