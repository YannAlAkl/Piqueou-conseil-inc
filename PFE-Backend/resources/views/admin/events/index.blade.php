@extends('layouts.admin')

@section('title', 'Gestion des événements')
@section('subtitle', 'Liste des événements publiés et brouillons.')

@section('actions')
    <a href="{{ route('admin.events.create') }}" class="admin-btn admin-btn-blue">+ Ajouter un événement</a>
@endsection

@section('content')

    <div class="admin-table-box">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Lieu</th>
                    <th>Statut</th>
                    <th>Créé par</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($evenements as $evenement)
                    <tr>
                        <td>{{ $evenement->title }}</td>
                        <td>{{ $evenement->start_date->format('d/m/Y') }}</td>
                        <td>{{ $evenement->end_date->format('d/m/Y') }}</td>
                        <td>{{ $evenement->location ?? '-' }}</td>
                        <td>
                            @if ($evenement->is_published)
                                <span class="admin-badge admin-badge-green">Publié</span>
                            @else
                                <span class="admin-badge admin-badge-yellow">Brouillon</span>
                            @endif
                        </td>
                        <td>{{ $evenement->creator?->name ?? '-' }}</td>
                        <td>{{ $evenement->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="flex items-center gap-3"
                                style="display: flex; gap: 10px; align-items: center; white-space: nowrap;">

                                <a href="{{ route('admin.events.show', $evenement->id) }}"
                                    class="admin-action admin-action-blue" title="Voir">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.events.edit', $evenement->id) }}"
                                    class="admin-action admin-action-yellow" title="Modifier">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.events.destroy', $evenement->id) }}"
                                    onsubmit="return ouvrirModal(this, 'Supprimer cet événement', 'Cet événement sera définitivement supprimé. Cette action est irréversible.', 'Supprimer définitivement', 'admin-btn-red')"
                                    style="display: inline; margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-action admin-action-red" title="Supprimer"
                                        style="background: none; border: none; cursor: pointer; padding: 0;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="admin-table-empty">Aucun événement trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($evenements->hasPages())
        <div class="admin-pagination">
            @if ($evenements->onFirstPage())
                <span class="admin-btn admin-btn-gray">Précédent</span>
            @else
                <a href="{{ $evenements->previousPageUrl() }}" class="admin-btn admin-btn-gray">Précédent</a>
            @endif

            <span>Page {{ $evenements->currentPage() }} sur {{ $evenements->lastPage() }}</span>

            @if ($evenements->hasMorePages())
                <a href="{{ $evenements->nextPageUrl() }}" class="admin-btn admin-btn-gray">Suivant</a>
            @else
                <span class="admin-btn admin-btn-gray">Suivant</span>
            @endif
        </div>
    @endif

@endsection
