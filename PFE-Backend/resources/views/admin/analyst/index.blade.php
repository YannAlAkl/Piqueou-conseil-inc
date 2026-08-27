@extends('layouts.admin')

@section('title', 'Gestion des analystes')
@section('subtitle', 'Liste des comptes analystes du portail.')

@section('actions')
    <a href="{{ route('admin.analyst.create') }}" class="admin-btn admin-btn-blue">+ Ajouter un analyste</a>
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
                    <th>Téléphone</th>
                    <th>Activé le</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($analystes as $analyst)
                    <tr>
                        <td>{{ $analyst->name }}</td>
                        <td>{{ $analyst->email }}</td>
                        <td>
                            @if ($analyst->account_status === 'active')
                                <span class="admin-legend-item" title="Actif"><span class="pastille-verte"></span></span>
                            @elseif ($analyst->account_status === 'pending')
                                <span class="admin-legend-item" title="En attente"><span class="pastille-jaune"></span></span>
                            @else
                                <span class="admin-legend-item" title="Inactif"><span class="pastille-rouge"></span></span>
                            @endif
                        </td>
                        <td>
                            @if ($analyst->email_verified_at)
                                Oui
                                <span class="block text-xs text-gray-400">{{ $analyst->email_verified_at->format('d/m/Y') }}</span>
                            @else
                                Non
                            @endif
                        </td>
                        <td>{{ $analyst->phone ?? '-' }}</td>
                        <td>{{ $analyst->activated_at ? $analyst->activated_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $analyst->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="flex items-center gap-3" style="display: flex; gap: 10px; align-items: center; white-space: nowrap;">
                                
                                <a href="{{ route('admin.analyst.show', $analyst->id) }}" class="admin-action admin-action-blue" title="Voir">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                
                                <a href="{{ route('admin.analyst.edit', $analyst->id) }}" class="admin-action admin-action-yellow" title="Modifier">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                @if ($analyst->account_status !== 'active')
                                    <form method="POST" action="{{ route('admin.analyst.verify', $analyst->id) }}"
                                          onsubmit="return ouvrirModal(this, 'Activer ce compte', 'L\'analyste pourra se connecter et un email de vérification lui sera envoyé.', 'Activer le compte', 'admin-btn-green')"
                                          style="display: inline; margin: 0;">
                                        @csrf
                                        <button type="submit" class="admin-action admin-action-green" title="Activer" style="background: none; border: none; cursor: pointer; padding: 0;">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.analyst.destroy', $analyst->id) }}"
                                      onsubmit="return ouvrirModal(this, 'Supprimer cet analyste', 'Le compte sera définitivement supprimé et les dossiers qui lui étaient assignés se retrouveront sans analyste. Cette action est irréversible.', 'Supprimer définitivement', 'admin-btn-red')"
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
                        <td colspan="8" class="admin-table-empty">Aucun analyste trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($analystes->hasPages())
        <div class="admin-pagination">
            @if ($analystes->onFirstPage())
                <span class="admin-btn admin-btn-gray">Précédent</span>
            @else
                <a href="{{ $analystes->previousPageUrl() }}" class="admin-btn admin-btn-gray">Précédent</a>
            @endif

            <span>Page {{ $analystes->currentPage() }} sur {{ $analystes->lastPage() }}</span>

            @if ($analystes->hasMorePages())
                <a href="{{ $analystes->nextPageUrl() }}" class="admin-btn admin-btn-gray">Suivant</a>
            @else
                <span class="admin-btn admin-btn-gray">Suivant</span>
            @endif
        </div>
    @endif

@endsection