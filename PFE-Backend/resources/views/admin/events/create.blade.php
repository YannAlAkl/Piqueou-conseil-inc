@extends('layouts.admin')

@section('title', 'Créer un événement')
@section('subtitle', 'Ajout d\'un nouvel événement.')

@section('content')

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.events.store') }}" class="admin-form">
            @csrf

            {{-- Section : informations principales --}}
            <h2 class="admin-card-title" style="margin-bottom: 18px;">Informations principales</h2>

            <div style="margin-bottom: 18px;">
                <label for="title" class="admin-label">Titre <span style="color: #dc2626;">*</span></label>
                <input type="text" name="title" id="title" class="admin-input"
                       value="{{ old('title') }}" required placeholder="Ex : Conférence CMMC 2026">
            </div>

            <div class="admin-form-grid" style="margin-bottom: 18px;">
                <div>
                    <label for="start_date" class="admin-label">Date de début <span style="color: #dc2626;">*</span></label>
                    <input type="date" name="start_date" id="start_date" class="admin-input"
                           value="{{ old('start_date') }}" required>
                </div>

                <div>
                    <label for="end_date" class="admin-label">Date de fin <span style="color: #dc2626;">*</span></label>
                    <input type="date" name="end_date" id="end_date" class="admin-input"
                           value="{{ old('end_date') }}" required>
                </div>

                <div>
                    <label for="schedule" class="admin-label">Horaire</label>
                    <input type="text" name="schedule" id="schedule" class="admin-input"
                           value="{{ old('schedule') }}" placeholder="ex : 9h00 – 17h00">
                </div>

                <div>
                    <label for="location" class="admin-label">Lieu</label>
                    <input type="text" name="location" id="location" class="admin-input"
                           value="{{ old('location') }}" placeholder="ex : Montréal, QC">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label for="description" class="admin-label">Description <span style="color: #dc2626;">*</span></label>
                <textarea name="description" id="description" class="admin-input" rows="6"
                          required placeholder="Décrivez l'événement…">{{ old('description') }}</textarea>
            </div>

            {{-- Section : lien optionnel --}}
            <h2 class="admin-card-title" style="margin-top: 28px; margin-bottom: 14px;">Lien externe (optionnel)</h2>

            <div class="admin-form-grid" style="margin-bottom: 18px;">
                <div>
                    <label for="link_url" class="admin-label">URL</label>
                    <input type="url" name="link_url" id="link_url" class="admin-input"
                           value="{{ old('link_url') }}" placeholder="https://...">
                </div>

                <div>
                    <label for="link_label" class="admin-label">Libellé du bouton</label>
                    <input type="text" name="link_label" id="link_label" class="admin-input"
                           value="{{ old('link_label') }}" placeholder="ex : S'inscrire">
                </div>
            </div>

            {{-- Section : publication --}}
            <div style="padding: 16px 18px; border-radius: 10px; background: rgba(34,197,94,0.07); border: 1px solid rgba(34,197,94,0.2); margin-bottom: 8px;">
                <label class="inline-flex items-center gap-2" style="cursor: pointer; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" name="is_published" id="is_published" value="1"
                           class="admin-checkbox" style="width: 17px; height: 17px;"
                           {{ old('is_published') ? 'checked' : '' }}>
                    <span style="font-size: 0.92rem; font-weight: 600; color: #0f172a;">Publier cet événement</span>
                </label>
                <p style="margin: 6px 0 0 27px; font-size: 0.82rem; color: #64748b;">
                    Si décoché, l'événement sera enregistré en brouillon et non visible publiquement.
                </p>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-blue">
                    <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Créer l'événement
                </button>
                <a href="{{ route('admin.events.index') }}" class="admin-btn admin-btn-gray">Annuler</a>
            </div>

        </form>
    </div>

@endsection
