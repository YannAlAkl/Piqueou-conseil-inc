@extends('layouts.admin')

@section('title', 'Modifier un client')
@section('subtitle', $client->email)

@section('content')

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.client.update', $client->id) }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="admin-form-grid">
                <div>
                    <label for="first_name" class="admin-label">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="admin-input"
                           value="{{ old('first_name', $client->first_name) }}" required>
                </div>

                <div>
                    <label for="last_name" class="admin-label">Nom</label>
                    <input type="text" name="last_name" id="last_name" class="admin-input"
                           value="{{ old('last_name', $client->last_name) }}" required>
                </div>

                <div>
                    <label for="email" class="admin-label">Email</label>
                    <input type="email" name="email" id="email" class="admin-input"
                           value="{{ old('email', $client->email) }}" required>
                </div>

                <div>
                    <label for="company_name" class="admin-label">Entreprise</label>
                    <input type="text" name="company_name" id="company_name" class="admin-input"
                           value="{{ old('company_name', $client->company_name) }}" required>
                </div>

                <div>
                    <label for="phone" class="admin-label">Téléphone (optionnel)</label>
                    <input type="text" name="phone" id="phone" class="admin-input"
                           value="{{ old('phone', $client->phone) }}">
                </div>

                <div>
                    <label for="account_status" class="admin-label">Statut du compte</label>
                    <select name="account_status" id="account_status" class="admin-select">
                        <option value="active" {{ $client->account_status === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="pending" {{ $client->account_status === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="inactive" {{ $client->account_status === 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="admin-label">Newsletter</label>
                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="wants_newsletter" value="1" class="admin-checkbox"
                           {{ $client->wants_newsletter ? 'checked' : '' }}>
                    Souhaite recevoir la newsletter
                </label>
            </div>
            <div>
            <label for="newsletter_category" class="admin-label">Catégorie de newsletter</label>
            <select name="newsletter_category" id="newsletter_category" class="admin-select">
                <option value="">Aucune</option>
                <option value="cmmc" {{ $client->newsletter_category === 'cmmc' ? 'selected' : '' }}>CMMC</option>
                <option value="loi25" {{ $client->newsletter_category === 'loi25' ? 'selected' : '' }}>Loi 25 (Québec)</option>
                <option value="iso27001" {{ $client->newsletter_category === 'iso27001' ? 'selected' : '' }}>ISO 27001</option>
            </select>
        </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-blue">Mettre à jour</button>
                <a href="{{ route('admin.client.index') }}" class="admin-btn admin-btn-gray">Annuler</a>
            </div>
        </form>
    </div>

@endsection
