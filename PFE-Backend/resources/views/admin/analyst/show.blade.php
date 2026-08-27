@extends('layouts.admin')

@section('title', 'Détails de l\'analyste')
@section('subtitle', $analyst->name)

@section('content')

    <div class="admin-card">
        <div class="admin-form-grid">

            <div>
                <h2 class="admin-card-title">Informations personnelles</h2>
                <div class="admin-info-list">
                    <div>
                        <p class="admin-info-label">Prénom</p>
                        <p class="admin-info-value">{{ $analyst->first_name }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Nom</p>
                        <p class="admin-info-value">{{ $analyst->last_name }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Email</p>
                        <p class="admin-info-value">{{ $analyst->email }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Téléphone</p>
                        <p class="admin-info-value">{{ $analyst->phone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Entreprise</p>
                        <p class="admin-info-value">{{ $analyst->company_name ?? 'Non renseignée' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="admin-card-title">Statut du compte</h2>
                <div class="admin-info-list">
                    <div>
                        <p class="admin-info-label">Statut</p>
                        <p class="admin-info-value">
                            @if ($analyst->account_status === 'active')
                                <span class="admin-badge admin-badge-green">Actif</span>
                            @elseif ($analyst->account_status === 'pending')
                                <span class="admin-badge admin-badge-yellow">En attente</span>
                            @else
                                <span class="admin-badge admin-badge-red">Inactif</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="admin-info-label">Email vérifié</p>
                        <p class="admin-info-value">
                            {{ $analyst->email_verified_at ? 'Oui (' . $analyst->email_verified_at->format('d/m/Y H:i') . ')' : 'Non' }}
                        </p>
                    </div>
                    <div>
                        <p class="admin-info-label">Inscrit le</p>
                        <p class="admin-info-value">{{ $analyst->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Activé le</p>
                        <p class="admin-info-value">{{ $analyst->activated_at ? $analyst->activated_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="admin-form-actions mt-6">
            @if ($analyst->account_status !== 'active')
                <form method="POST" action="{{ route('admin.analyst.verify', $analyst->id) }}"
                      onsubmit="return ouvrirModal(this, 'Activer ce compte', 'L\'analyste pourra se connecter et un email de vérification lui sera envoyé.', 'Activer le compte', 'admin-btn-green')">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-green">Activer</button>
                </form>
            @endif
            <a href="{{ route('admin.analyst.edit', $analyst->id) }}" class="admin-btn admin-btn-yellow">Modifier</a>
            <a href="{{ route('admin.analyst.index') }}" class="admin-btn admin-btn-gray">Retour à la liste</a>
        </div>
    </div>

@endsection
