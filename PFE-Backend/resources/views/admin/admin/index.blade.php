@extends('layouts.admin')

@section('title', 'Gestion des administrateurs')
@section('subtitle', 'Liste des comptes administrateurs du portail.')

@section('actions')
    <a href="{{ route('admin.admin.create') }}" class="admin-btn admin-btn-blue">+ Ajouter un administrateur</a>
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
                @forelse ($admins as $admin)
                    <tr>
                        <td>{{ $admin->name }} @if ($admin->id === Auth::id())<span class="admin-badge admin-badge-blue">vous</span>@endif</td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            @if ($admin->account_status === 'active')
                                <span class="admin-legend-item" title="Actif"><span class="pastille-verte"></span></span>
                            @elseif ($admin->account_status === 'pending')
                                <span class="admin-legend-item" title="En attente"><span class="pastille-jaune"></span></span>
                            @else
                                <span class="admin-legend-item" title="Inactif"><span class="pastille-rouge"></span></span>
                            @endif
                        </td>
                        <td>
                            @if ($admin->email_verified_at)
                                Oui
                                <span class="block text-xs text-gray-400">{{ $admin->email_verified_at->format('d/m/Y') }}</span>
                            @else
                                Non
                            @endif
                        </td>
                        <td>{{ $admin->phone ?? '-' }}</td>
                        <td>{{ $admin->activated_at ? $admin->activated_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="flex items-center gap-3" style="display: flex; gap: 10px; align-items: center; white-space: nowrap;">

                                <a href="{{ route('admin.admin.show', $admin->id) }}" class="admin-action admin-action-blue" title="Voir">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.admin.edit', $admin->id) }}" class="admin-action admin-action-yellow" title="Modifier">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                @if ($admin->account_status !== 'active')
                                    <form method="POST" action="{{ route('admin.admin.verify', $admin->id) }}"
                                          onsubmit="return ouvrirModal(this, 'Activer ce compte', 'L\'administrateur pourra se connecter et un email de vérification lui sera envoyé.', 'Activer le compte', 'admin-btn-green')"
                                          style="display: inline; margin: 0;">
                                        @csrf
                                        <button type="submit" class="admin-action admin-action-green" title="Activer" style="background: none; border: none; cursor: pointer; padding: 0;">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                    </form>
                                @endif

                                @if ($admin->id !== Auth::id())
                                    <form method="POST" action="{{ route('admin.admin.destroy', $admin->id) }}"
                                          onsubmit="return ouvrirModal(this, 'Supprimer cet administrateur', 'Le compte sera définitivement supprimé. Cette action est irréversible.', 'Supprimer définitivement', 'admin-btn-red')"
                                          style="display: inline; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-action admin-action-red" title="Supprimer" style="background: none; border: none; cursor: pointer; padding: 0;">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="admin-action" title="Vous ne pouvez pas supprimer votre propre compte" style="opacity: 0.4; cursor: not-allowed;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </span>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="admin-table-empty">Aucun administrateur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($admins->hasPages())
        <div class="admin-pagination">
            @if ($admins->onFirstPage())
                <span class="admin-btn admin-btn-gray">Précédent</span>
            @else
                <a href="{{ $admins->previousPageUrl() }}" class="admin-btn admin-btn-gray">Précédent</a>
            @endif

            <span>Page {{ $admins->currentPage() }} sur {{ $admins->lastPage() }}</span>

            @if ($admins->hasMorePages())
                <a href="{{ $admins->nextPageUrl() }}" class="admin-btn admin-btn-gray">Suivant</a>
            @else
                <span class="admin-btn admin-btn-gray">Suivant</span>
            @endif
        </div>
    @endif

@endsection
