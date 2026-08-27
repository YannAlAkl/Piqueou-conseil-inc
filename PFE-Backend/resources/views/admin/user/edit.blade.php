@extends('layouts.admin')

@section('title', 'Modifier un utilisateur')
@section('subtitle', $user->email)

@section('content')

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.user.update', $user->id) }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="admin-form-grid">
                <div>
                    <label for="first_name" class="admin-label">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="admin-input"
                           value="{{ old('first_name', $user->first_name) }}" required>
                </div>

                <div>
                    <label for="last_name" class="admin-label">Nom</label>
                    <input type="text" name="last_name" id="last_name" class="admin-input"
                           value="{{ old('last_name', $user->last_name) }}" required>
                </div>

                <div>
                    <label for="email" class="admin-label">Email</label>
                    <input type="email" name="email" id="email" class="admin-input"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <div>
                    <label for="phone" class="admin-label">Téléphone (optionnel)</label>
                    <input type="text" name="phone" id="phone" class="admin-input"
                           value="{{ old('phone', $user->phone) }}">
                </div>

                <div>
                    <label for="company_name" class="admin-label">Entreprise (optionnel)</label>
                    <input type="text" name="company_name" id="company_name" class="admin-input"
                           value="{{ old('company_name', $user->company_name) }}">
                </div>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-blue">Mettre à jour</button>
                <a href="{{ route('admin.dashboard') }}" class="admin-btn admin-btn-gray">Annuler</a>
            </div>
        </form>
    </div>

@endsection
