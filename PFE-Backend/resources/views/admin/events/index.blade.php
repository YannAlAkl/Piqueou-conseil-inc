<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Événements</h2>
            <a href="{{ route('admin.events.create') }}"
               class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                + Ajouter un événement
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($evenements->isEmpty())
                <div class="bg-white rounded-lg shadow p-12 text-center text-gray-400">
                    <p class="text-lg font-medium">Aucun événement pour le moment.</p>
                    <a href="{{ route('admin.events.create') }}"
                       class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        + Créer le premier événement
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($evenements as $evenement)
                        <div class="bg-white rounded-lg shadow p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <h3 class="font-bold text-gray-900 text-base leading-snug flex-1">
                                        {{ $evenement->title }}
                                    </h3>
                                    @if ($evenement->is_published)
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700 whitespace-nowrap">
                                            Publié
                                        </span>
                                    @else
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 whitespace-nowrap">
                                            Brouillon
                                        </span>
                                    @endif
                                </div>

                                <div class="space-y-2 mb-4 text-sm text-gray-500">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>
                                            {{ $evenement->start_date->format('d/m/Y') }}
                                            @if (!$evenement->start_date->eq($evenement->end_date))
                                                &rarr; {{ $evenement->end_date->format('d/m/Y') }}
                                            @endif
                                        </span>
                                    </div>
                                    @if ($evenement->schedule)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ $evenement->schedule }}</span>
                                        </div>
                                    @endif
                                    @if ($evenement->location)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span>{{ $evenement->location }}</span>
                                        </div>
                                    @endif
                                    @if ($evenement->link_url)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                            <a href="{{ $evenement->link_url }}" target="_blank"
                                               class="text-blue-600 underline truncate max-w-xs">
                                                {{ $evenement->link_label ?? $evenement->link_url }}
                                            </a>
                                        </div>
                                    @endif
                                </div>

                                @if ($evenement->description)
                                    <p class="text-sm text-gray-500 line-clamp-3 mb-4">
                                        {{ $evenement->description }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <span class="text-xs text-gray-400">Par {{ $evenement->creator?->name ?? '—' }}</span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.events.show', $evenement->id) }}"
                                       class="text-xs px-3 py-1 rounded border border-blue-400 text-blue-600 hover:bg-blue-50">
                                        Voir
                                    </a>
                                    <a href="{{ route('admin.events.edit', $evenement->id) }}"
                                       class="text-xs px-3 py-1 rounded border border-yellow-400 text-yellow-600 hover:bg-yellow-50">
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.events.destroy', $evenement->id) }}"
                                          onsubmit="return confirm('Supprimer cet événement ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-xs px-3 py-1 rounded border border-red-400 text-red-600 hover:bg-red-50">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($evenements->hasPages())
                    <div class="mt-6">{{ $evenements->links() }}</div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
