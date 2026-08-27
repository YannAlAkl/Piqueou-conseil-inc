@extends('layouts.admin')

@section('title', 'Modifier un analyste')
@section('subtitle', $analyst->email)

@section('content')

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.analyst.update', $analyst->id) }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="admin-form-grid">
                <div>
                    <label for="first_name" class="admin-label">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="admin-input"
                           value="{{ old('first_name', $analyst->first_name) }}" required>
                </div>

                <div>
                    <label for="last_name" class="admin-label">Nom</label>
                    <input type="text" name="last_name" id="last_name" class="admin-input"
                           value="{{ old('last_name', $analyst->last_name) }}" required>
                </div>

                <div>
                    <label for="email" class="admin-label">Email</label>
                    <input type="email" name="email" id="email" class="admin-input"
                           value="{{ old('email', $analyst->email) }}" required>
                </div>

                <div>
                    <label for="phone" class="admin-label">Téléphone (optionnel)</label>
                    <input type="text" name="phone" id="phone" class="admin-input"
                           value="{{ old('phone', $analyst->phone) }}">
                </div>

                <div>
                    <label for="company_name" class="admin-label">Entreprise (optionnel)</label>
                    <input type="text" name="company_name" id="company_name" class="admin-input"
                           value="{{ old('company_name', $analyst->company_name) }}">
                </div>

                <div>
                    <label for="account_status" class="admin-label">Statut du compte</label>
                    <select name="account_status" id="account_status" class="admin-select">
                        <option value="active" {{ $analyst->account_status === 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="pending" {{ $analyst->account_status === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="inactive" {{ $analyst->account_status === 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-blue">Mettre à jour</button>
                <a href="{{ route('admin.analyst.index') }}" class="admin-btn admin-btn-gray">Annuler</a>
            </div>
        </form>
    </div>

@endsection
