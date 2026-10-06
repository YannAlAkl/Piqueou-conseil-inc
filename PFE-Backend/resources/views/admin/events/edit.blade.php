@extends('layouts.admin')

@section('title', 'Modifier un événement')
@section('subtitle', $evenement->title)

@section('content')

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.events.update', $evenement->id) }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="admin-form-grid">
                <div>
                    <label for="title" class="admin-label">Titre</label>
                    <input type="text" name="title" id="title" class="admin-input"
                           value="{{ old('title', $evenement->title) }}" required>
                </div>

                <div>
                    <label for="location" class="admin-label">Lieu (optionnel)</label>
                    <input type="text" name="location" id="location" class="admin-input"
                           value="{{ old('location', $evenement->location) }}">
                </div>

                <div>
                    <label for="start_date" class="admin-label">Date de début</label>
                    <input type="date" name="start_date" id="start_date" class="admin-input"
                           value="{{ old('start_date', $evenement->start_date->format('Y-m-d')) }}" required>
                </div>

                <div>
                    <label for="end_date" class="admin-label">Date de fin</label>
                    <input type="date" name="end_date" id="end_date" class="admin-input"
                           value="{{ old('end_date', $evenement->end_date->format('Y-m-d')) }}" required>
                </div>

                <div>
                    <label for="schedule" class="admin-label">Horaire (optionnel)</label>
                    <input type="text" name="schedule" id="schedule" class="admin-input"
                           value="{{ old('schedule', $evenement->schedule) }}" placeholder="ex: 9h00 – 17h00">
                </div>

                <div>
                    <label for="link_url" class="admin-label">Lien (optionnel)</label>
                    <input type="url" name="link_url" id="link_url" class="admin-input"
                           value="{{ old('link_url', $evenement->link_url) }}" placeholder="https://...">
                </div>

                <div>
                    <label for="link_label" class="admin-label">Libellé du lien (optionnel)</label>
                    <input type="text" name="link_label" id="link_label" class="admin-input"
                           value="{{ old('link_label', $evenement->link_label) }}" placeholder="ex: S'inscrire">
                </div>
            </div>

            <div>
                <label for="description" class="admin-label">Description</label>
                <textarea name="description" id="description" class="admin-input" rows="6"
                          required>{{ old('description', $evenement->description) }}</textarea>
            </div>

            <div>
                <label class="admin-label">Publication</label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="is_published" id="is_published" value="1"
                           class="admin-checkbox" {{ old('is_published', $evenement->is_published) ? 'checked' : '' }}>
                    Publier cet événement
                </label>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-blue">Mettre à jour</button>
                <a href="{{ route('admin.events.index') }}" class="admin-btn admin-btn-gray">Annuler</a>
            </div>
        </form>
    </div>

@endsection
