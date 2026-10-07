@extends('layouts.admin')

@section('title', 'Gestion des événements')
@section('subtitle', 'Liste des événements publiés et brouillons.')

@section('actions')
    <a href="{{ route('admin.events.create') }}" class="admin-btn admin-btn-blue">+ Ajouter un événement</a>
@endsection

@section('content')

    @if ($evenements->isEmpty())
        <div class="admin-card" style="text-align: center; padding: 48px 24px; color: #94a3b8;">
            <i class="fa-regular fa-calendar-xmark" style="font-size: 2.5rem; margin-bottom: 16px; display: block;"></i>
            <p style="font-size: 1rem; font-weight: 500;">Aucun événement pour le moment.</p>
            <a href="{{ route('admin.events.create') }}" class="admin-btn admin-btn-blue" style="margin-top: 20px;">
                + Créer le premier événement
            </a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">

            @foreach ($evenements as $evenement)
                <div class="admin-card" style="margin-bottom: 0; display: flex; flex-direction: column; justify-content: space-between;">

                    {{-- En-tête : badge + titre --}}
                    <div>
                        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 14px;">
                            <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #0f172a; line-height: 1.35; flex: 1;">
                                {{ $evenement->title }}
                            </h3>
                            @if ($evenement->is_published)
                                <span class="admin-badge admin-badge-green" style="white-space: nowrap; flex-shrink: 0;">
                                    <span class="admin-badge-dot"></span> Publié
                                </span>
                            @else
                                <span class="admin-badge admin-badge-yellow" style="white-space: nowrap; flex-shrink: 0;">
                                    <span class="admin-badge-dot"></span> Brouillon
                                </span>
                            @endif
                        </div>

                        {{-- Infos : dates, horaire, lieu --}}
                        <div style="display: grid; gap: 8px; margin-bottom: 14px;">

                            <div style="display: flex; align-items: center; gap: 9px; font-size: 0.87rem; color: #475569;">
                                <i class="fa-regular fa-calendar" style="color: #018880; width: 14px; text-align: center;"></i>
                                <span>
                                    {{ $evenement->start_date->format('d/m/Y') }}
                                    @if (!$evenement->start_date->eq($evenement->end_date))
                                        &rarr; {{ $evenement->end_date->format('d/m/Y') }}
                                    @endif
                                </span>
                            </div>

                            @if ($evenement->schedule)
                                <div style="display: flex; align-items: center; gap: 9px; font-size: 0.87rem; color: #475569;">
                                    <i class="fa-regular fa-clock" style="color: #018880; width: 14px; text-align: center;"></i>
                                    <span>{{ $evenement->schedule }}</span>
                                </div>
                            @endif

                            @if ($evenement->location)
                                <div style="display: flex; align-items: center; gap: 9px; font-size: 0.87rem; color: #475569;">
                                    <i class="fa-solid fa-location-dot" style="color: #018880; width: 14px; text-align: center;"></i>
                                    <span>{{ $evenement->location }}</span>
                                </div>
                            @endif

                            @if ($evenement->link_url)
                                <div style="display: flex; align-items: center; gap: 9px; font-size: 0.87rem; color: #475569;">
                                    <i class="fa-solid fa-link" style="color: #018880; width: 14px; text-align: center;"></i>
                                    <a href="{{ $evenement->link_url }}" target="_blank"
                                       style="color: #018880; text-decoration: underline; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 240px; display: inline-block; vertical-align: middle;">
                                        {{ $evenement->link_label ?? $evenement->link_url }}
                                    </a>
                                </div>
                            @endif

                        </div>

                        {{-- Description (tronquée) --}}
                        @if ($evenement->description)
                            <p style="font-size: 0.85rem; color: #64748b; margin: 0 0 16px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.55;">
                                {{ $evenement->description }}
                            </p>
                        @endif
                    </div>

                    {{-- Pied de carte : créateur + actions --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px; padding-top: 14px; border-top: 1px solid #e2e8f0;">

                        <span style="font-size: 0.78rem; color: #94a3b8;">
                            Par {{ $evenement->creator?->name ?? '—' }}
                        </span>

                        <div style="display: flex; gap: 8px; align-items: center;">

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
                                        style="background: none; border: 2px solid #dc2626; cursor: pointer; padding: 6px 10px; border-radius: 999px;">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            @endforeach

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

    @endif

@endsection
