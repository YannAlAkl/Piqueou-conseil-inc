@extends('layouts.admin')

@section('title', 'Détails du client')
@section('subtitle', $client->name)

@section('content')

    <div class="admin-card">
        <div class="admin-form-grid">

            <div>
                <h2 class="admin-card-title">Informations personnelles</h2>
                <div class="admin-info-list">
                    <div>
                        <p class="admin-info-label">Prénom</p>
                        <p class="admin-info-value">{{ $client->first_name }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Nom</p>
                        <p class="admin-info-value">{{ $client->last_name }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Email</p>
                        <p class="admin-info-value">{{ $client->email }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Téléphone</p>
                        <p class="admin-info-value">{{ $client->phone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Entreprise</p>
                        <p class="admin-info-value">{{ $client->company_name ?? 'Non renseignée' }}</p>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="admin-card-title">Statut du compte</h2>
                <div class="admin-info-list">
                    <div>
                        <p class="admin-info-label">Statut</p>
                        <p class="admin-info-value">
                            @if ($client->account_status === 'active')
                                <span class="admin-badge admin-badge-green">Actif</span>
                            @elseif ($client->account_status === 'pending')
                                <span class="admin-badge admin-badge-yellow">En attente</span>
                            @else
                                <span class="admin-badge admin-badge-red">Inactif</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="admin-info-label">Email vérifié</p>
                        <p class="admin-info-value">
                            {{ $client->email_verified_at ? 'Oui (' . $client->email_verified_at->format('d/m/Y H:i') . ')' : 'Non' }}
                        </p>
                    </div>
                    <div>
                        <p class="admin-info-label">Newsletter</p>
                        <p class="admin-info-value">
                            {{ $client->wants_newsletter ? 'Oui' : 'Non' }}
                            @if ($client->wants_newsletter && $client->newsletter_category)
                                ({{ $client->newsletter_category }})
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="admin-info-label">Inscrit le</p>
                        <p class="admin-info-value">{{ $client->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="admin-info-label">Activé le</p>
                        <p class="admin-info-value">{{ $client->activated_at ? $client->activated_at->format('d/m/Y H:i') : '-' }}</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="admin-form-actions mt-6">
            @if ($client->account_status !== 'active')
                <form method="POST" action="{{ route('admin.client.activate', $client->id) }}"
                      onsubmit="return ouvrirModal(this, 'Activer ce compte', 'Le client pourra se connecter et un email de vérification lui sera envoyé.', 'Activer le compte', 'admin-btn-green')">
                    @csrf
                    <button type="submit" class="admin-btn admin-btn-green">Activer</button>
                </form>
            @endif
            <a href="{{ route('admin.client.edit', $client->id) }}" class="admin-btn admin-btn-yellow">Modifier</a>
            <a href="{{ route('admin.client.index') }}" class="admin-btn admin-btn-gray">Retour à la liste</a>
        </div>
    </div>

@endsection
