@extends('layouts.admin')

@section('title', 'Gestion des clients')
@section('subtitle', 'Liste des comptes clients du portail.')

@section('actions')
    <a href="{{ route('admin.client.create') }}" class="admin-btn admin-btn-blue">+ Ajouter un client</a>
@endsection

@section('content')

<div class="admin-legend">
    <span class="admin-legend-item">
        <span class="pastille-verte"></span>
        Actif
    </span>
    <span class="admin-legend-item">
        <span class="pastille-jaune"></span>
        En attente
    </span>
    <span class="admin-legend-item">
        <span class="pastille-rouge"></span>
        Inactif
    </span>
</div>

    <div class="admin-table-box">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Email vérifié</th>
                    <th>Entreprise</th>
                    <th>Téléphone</th>
                    <th>Newsletter</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($clients as $client)
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>
                            @if ($client->account_status === 'active')
                                <span class="admin-legend-item" title="Actif"><span class="pastille-verte"></span></span>
                            @elseif ($client->account_status === 'pending')
                                <span class="admin-legend-item" title="En attente"><span class="pastille-jaune"></span></span>
                            @else
                                <span class="admin-legend-item" title="Inactif"><span class="pastille-rouge"></span></span>
                            @endif
                        </td>
                        <td>
                            @if ($client->email_verified_at)
                                Oui
                                <span class="block text-xs text-gray-400">{{ $client->email_verified_at->format('d/m/Y') }}</span>
                            @else
                                Non
                            @endif
                        </td>
                        <td>{{ $client->company_name ?? '-' }}</td>
                        <td>{{ $client->phone ?? '-' }}</td>
                        <td>
                            @if ($client->wants_newsletter)
                                Oui
                                @if ($client->newsletter_category)
                                    <span class="block text-xs text-gray-400">{{ $client->newsletter_category }}</span>
                                @endif
                            @else
                                Non
                            @endif
                        </td>
                        <td>{{ $client->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="flex items-center gap-3" style="display: flex; gap: 10px; align-items: center; white-space: nowrap;">

                                <a href="{{ route('admin.client.show', $client->id) }}" class="admin-action admin-action-blue" title="Voir">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.client.edit', $client->id) }}" class="admin-action admin-action-yellow" title="Modifier">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                @if ($client->account_status !== 'active')
                                    <form method="POST" action="{{ route('admin.client.activate', $client->id) }}"
                                          onsubmit="return ouvrirModal(this, 'Activer ce compte', 'Le client pourra se connecter et un email de vérification lui sera envoyé.', 'Activer le compte', 'admin-btn-green')"
                                          style="display: inline; margin: 0;">
                                        @csrf
                                        <button type="submit" class="admin-action admin-action-green" title="Activer" style="background: none; border: none; cursor: pointer; padding: 0;">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.client.destroy', $client->id) }}"
                                      onsubmit="return ouvrirModal(this, 'Supprimer ce client', 'Le compte et toutes ses réponses au questionnaire seront définitivement supprimés. Cette action est irréversible. Vous pouver le desactiver si vous souhaitez en passant vers modifier', 'Supprimer définitivement', 'admin-btn-red')"
                                      style="display: inline; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-action admin-action-red" title="Supprimer" style="background: none; border: none; cursor: pointer; padding: 0;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="admin-table-empty">Aucun client trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($clients->hasPages())
        <div class="admin-pagination">
            @if ($clients->onFirstPage())
                <span class="admin-btn admin-btn-gray">Précédent</span>
            @else
                <a href="{{ $clients->previousPageUrl() }}" class="admin-btn admin-btn-gray">Précédent</a>
            @endif

            <span>Page {{ $clients->currentPage() }} sur {{ $clients->lastPage() }}</span>

            @if ($clients->hasMorePages())
                <a href="{{ $clients->nextPageUrl() }}" class="admin-btn admin-btn-gray">Suivant</a>
            @else
                <span class="admin-btn admin-btn-gray">Suivant</span>
            @endif
        </div>
    @endif

@endsection
