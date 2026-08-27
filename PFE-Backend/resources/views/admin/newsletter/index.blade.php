@extends('layouts.admin')

@section('title', 'Gestion des newsletters')
@section('subtitle', 'Liste des newsletters du portail.')

@section('actions')
    <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('admin.newsletter.retrieve') }}" class="inline-flex items-center m-0">
            @csrf
            <button type="submit" class="admin-btn admin-btn-gray">Récupérer les newsletters</button>
        </form>

        <form method="POST" action="{{ route('admin.newsletter.send') }}" class="inline-flex items-center m-0"
            onsubmit="return ouvrirModal(this, 'Envoyer les newsletters', 'Chaque client abonné va recevoir la newsletter publiée de sa catégorie.', 'Envoyer', 'admin-btn-green')">
            @csrf
            <button type="submit" class="admin-btn admin-btn-green">Envoyer les newsletters</button>
        </form>
    </div>
@endsection

@section('content')

    <div class="admin-legend">
        <span class="admin-legend-item">
            <span class="pastille-verte"></span>
            Publiée
        </span>
        <span class="admin-legend-item">
            <span class="pastille-jaune"></span>
            Brouillon
        </span>
    </div>

    <div class="admin-table-box">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Statut</th>
                    <th>Envoyée le</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($newsletters as $newsletter)
                    <tr>
                        <td>{{ $newsletter->title }}</td>
                        <td>{{ $newsletter->nomCategorie() }}</td>
                        <td>
                            @if ($newsletter->status === 'published')
                                <span class="admin-badge admin-badge-green">Publiée</span>
                            @else
                                <span class="admin-badge admin-badge-yellow">Brouillon</span>
                            @endif
                        </td>
                        <td>{{ $newsletter->sent_at ? $newsletter->sent_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <a href="{{ route('admin.newsletter.edit', $newsletter->id) }}"
                                class="admin-btn admin-btn-blue">Modifier</a>

                            <form method="POST" action="{{ route('admin.newsletter.destroy', $newsletter->id) }}"
                                class="inline-flex items-center m-0"
                                onsubmit="return ouvrirModal(this, 'Supprimer cette newsletter', 'Cette action est définitive.', 'Supprimer', 'admin-btn-red')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn admin-btn-red">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="admin-table-empty">Aucune newsletter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($newsletters->hasPages())
        <div class="admin-pagination">
            @if ($newsletters->onFirstPage())
                <span class="admin-btn admin-btn-gray">Précédent</span>
            @else
                <a href="{{ $newsletters->previousPageUrl() }}" class="admin-btn admin-btn-gray">Précédent</a>
            @endif

            <span>Page {{ $newsletters->currentPage() }} sur {{ $newsletters->lastPage() }}</span>

            @if ($newsletters->hasMorePages())
                <a href="{{ $newsletters->nextPageUrl() }}" class="admin-btn admin-btn-gray">Suivant</a>
            @else
                <span class="admin-btn admin-btn-gray">Suivant</span>
            @endif
        </div>
    @endif

@endsection
