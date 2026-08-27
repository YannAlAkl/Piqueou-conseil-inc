@extends('layouts.admin')

@section('title', 'Créer un analyste')
@section('subtitle', 'Le mot de passe est généré automatiquement et envoyé par email à l\'analyste.')

@section('content')

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.analyst.store') }}" class="admin-form">
            @csrf

            <div class="admin-form-grid">
                <div>
                    <label for="first_name" class="admin-label">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="admin-input" value="{{ old('first_name') }}" required>
                </div>

                <div>
                    <label for="last_name" class="admin-label">Nom</label>
                    <input type="text" name="last_name" id="last_name" class="admin-input" value="{{ old('last_name') }}" required>
                </div>

                <div>
                    <label for="email" class="admin-label">Email</label>
                    <input type="email" name="email" id="email" class="admin-input" value="{{ old('email') }}" required>
                    <p class="admin-help">Les identifiants de connexion seront envoyés à cette adresse.</p>
                </div>

                <div>
                    <label for="phone" class="admin-label">Téléphone (optionnel)</label>
                    <input type="text" name="phone" id="phone" class="admin-input" value="{{ old('phone') }}">
                </div>

                <div>
                    <label for="company_name" class="admin-label">Entreprise (optionnel)</label>
                    <input type="text" name="company_name" id="company_name" class="admin-input" value="{{ old('company_name') }}">
                </div>

                <div>
                    <label for="account_status" class="admin-label">Statut du compte</label>
                    <select name="account_status" id="account_status" class="admin-select">
                        <option value="active" selected>Actif</option>
                        <option value="pending">En attente</option>
                        <option value="inactive">Inactif</option>
                    </select>
                </div>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-blue">Créer l'analyste</button>
                <a href="{{ route('admin.analyst.index') }}" class="admin-btn admin-btn-gray">Annuler</a>
            </div>
        </form>
    </div>

@endsection
