@extends('layouts.admin')

@section('title', 'Modifier une newsletter')
@section('subtitle', $newsletter->title)

@section('content')

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.newsletter.update', $newsletter->id) }}" class="admin-form"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="admin-form-grid">
                <div>
                    <label for="title" class="admin-label">Titre</label>
                    <input type="text" name="title" id="title" class="admin-input"
                        value="{{ old('title', $newsletter->title) }}" required>
                </div>

                <div>
                    <label for="category" class="admin-label">Catégorie</label>
                    <select name="category" id="category" class="admin-select">
                        <option value="cmmc" {{ $newsletter->category === 'cmmc' ? 'selected' : '' }}>CMMC</option>
                        <option value="loi25" {{ $newsletter->category === 'loi25' ? 'selected' : '' }}>Loi 25</option>
                        <option value="iso27001" {{ $newsletter->category === 'iso27001' ? 'selected' : '' }}>ISO 27001</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="content" class="admin-label">Contenu</label>
                <textarea name="content" id="content" rows="12" class="admin-input" required>{{ old('content', $newsletter->content) }}</textarea>
                <p class="admin-help">Reformulez le texte avec vos propres mots avant de publier.</p>
            </div>

            <div>
                <label for="image" class="admin-label">Image (optionnelle)</label>
                <input type="file" name="image" id="image" class="admin-input" accept="image/*">

                @if ($newsletter->image)
                    <img src="{{ asset('storage/' . $newsletter->image) }}" alt=""
                        style="max-width:260px; margin-top:10px;">
                @endif
            </div>

            <div>
                <label for="status" class="admin-label">Statut</label>
                <select name="status" id="status" class="admin-select" required>
                    <option value="draft" {{ $newsletter->status === 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="published" {{ $newsletter->status === 'published' ? 'selected' : '' }}>Publiée</option>
                </select>
            </div>

            @if ($newsletter->source_url)
                <div>
                    <p class="admin-info-label">Source</p>
                    <p class="admin-info-value">
                        <a href="{{ $newsletter->source_url }}" target="_blank">{{ $newsletter->source_url }}</a>
                    </p>
                </div>
            @endif

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-blue">Mettre à jour</button>
                <a href="{{ route('admin.newsletter.index') }}" class="admin-btn admin-btn-gray">Annuler</a>
            </div>
        </form>
    </div>

@endsection
