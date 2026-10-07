<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Créer un événement</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow p-8">

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.events.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Titre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex : Conférence CMMC 2026">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Date de début <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required
                                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Date de fin <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" required
                                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Horaire</label>
                            <input type="text" name="schedule" value="{{ old('schedule') }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="ex : 9h00 – 17h00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lieu</label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="ex : Montréal, QC">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" rows="6" required
                                  class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Décrivez l'événement…">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">URL externe</label>
                            <input type="url" name="link_url" value="{{ old('link_url') }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="https://...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Libellé du bouton</label>
                            <input type="text" name="link_label" value="{{ old('link_label') }}"
                                   class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="ex : S'inscrire">
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded">
                        <input type="checkbox" name="is_published" id="is_published" value="1"
                               class="w-4 h-4 accent-green-600"
                               {{ old('is_published') ? 'checked' : '' }}>
                        <div>
                            <label for="is_published" class="text-sm font-medium text-gray-800 cursor-pointer">
                                Publier cet événement
                            </label>
                            <p class="text-xs text-gray-500">Si décoché, l'événement sera enregistré en brouillon.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                            Créer l'événement
                        </button>
                        <a href="{{ route('admin.events.index') }}"
                           class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded hover:bg-gray-200">
                            Annuler
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
