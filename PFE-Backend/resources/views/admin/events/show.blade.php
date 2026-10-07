<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $evenement->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-8 space-y-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Titre</p>
                            <p class="text-sm font-medium text-gray-800">{{ $evenement->title }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Date de début</p>
                            <p class="text-sm text-gray-800">{{ $evenement->start_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Date de fin</p>
                            <p class="text-sm text-gray-800">{{ $evenement->end_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Horaire</p>
                            <p class="text-sm text-gray-800">{{ $evenement->schedule ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Lieu</p>
                            <p class="text-sm text-gray-800">{{ $evenement->location ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Statut</p>
                            @if ($evenement->is_published)
                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">Publié</span>
                            @else
                                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-yellow-100 text-yellow-700">Brouillon</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Créé par</p>
                            <p class="text-sm text-gray-800">{{ $evenement->creator?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">Créé le</p>
                            <p class="text-sm text-gray-800">{{ $evenement->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if ($evenement->link_url)
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Lien</p>
                                <a href="{{ $evenement->link_url }}" target="_blank"
                                   class="text-sm text-blue-600 underline">
                                    {{ $evenement->link_label ?? $evenement->link_url }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Description</p>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $evenement->description }}</p>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.events.edit', $evenement->id) }}"
                       class="px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded hover:bg-yellow-600">
                        Modifier
                    </a>
                    <a href="{{ route('admin.events.index') }}"
                       class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded hover:bg-gray-200">
                        Retour à la liste
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
