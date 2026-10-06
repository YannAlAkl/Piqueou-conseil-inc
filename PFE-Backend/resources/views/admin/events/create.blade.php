@extends('layouts.admin')

@section('title', 'Créer un événement')
@section('subtitle', 'Ajout d\'un nouvel événement.')

@section('content')

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.events.store') }}" class="admin-form">
            @csrf

            <div class="admin-form-grid">
                <div>
                    <label for="title" class="admin-label">Titre</label>
                    <input type="text" name="title" id="title" class="admin-input"
                           value="{{ old('title') }}" required>
                </div>

                <div>
                    <label for="location" class="admin-label">Lieu (optionnel)</label>
                    <input type="text" name="location" id="location" class="admin-input"
                           value="{{ old('location') }}">
                </div>

                <div>
                    <label for="start_date" class="admin-label">Date de début</label>
                    <input type="date" name="start_date" id="start_date" class="admin-input"
                           value="{{ old('start_date') }}" required>
                </div>

                <div>
                    <label for="end_date" class="admin-label">Date de fin</label>
                    <input type="date" name="end_date" id="end_date" class="admin-input"
                           value="{{ old('end_date') }}" required>
                </div>

                <div>
                    <label for="schedule" class="admin-label">Horaire (optionnel)</label>
                    <input type="text" name="schedule" id="schedule" class="admin-input"
                           value="{{ old('schedule') }}" placeholder="ex: 9h00 – 17h00">
                </div>

                <div>
                    <label for="link_url" class="admin-label">Lien (optionnel)</label>
                    <input type="url" name="link_url" id="link_url" class="admin-input"
                           value="{{ old('link_url') }}" placeholder="https://...">
                </div>

                <div>
                    <label for="link_label" class="admin-label">Libellé du lien (optionnel)</label>
                    <input type="text" name="link_label" id="link_label" class="admin-input"
                           value="{{ old('link_label') }}" placeholder="ex: S'inscrire">
                </div>
            </div>

            <div>
                <label for="description" class="admin-label">Description</label>
                <textarea name="description" id="description" class="admin-input" rows="6"
                          required>{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="admin-label">Publication</label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="is_published" id="is_published" value="1"
                           class="admin-checkbox" {{ old('is_published') ? 'checked' : '' }}>
                    Publier cet événement
                </label>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-blue">Créer l'événement</button>
                <a href="{{ route('admin.events.index') }}" class="admin-btn admin-btn-gray">Annuler</a>
            </div>
        </form>
    </div>

@endsection
