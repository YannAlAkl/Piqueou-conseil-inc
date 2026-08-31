@extends('layouts.admin')

@section('title', 'Modifier un administrateur')
@section('subtitle', $admin->email)

@section('content')

    @if ($admin->id === Auth::id())
        <div class="admin-alert-error" style="background: rgba(234,179,8,0.12); border-color: rgba(234,179,8,0.35); color: #a16207;">
            Vous modifiez votre propre compte. Pour éviter de vous bloquer l'accès, le statut ne peut pas être désactivé et le compte ne peut pas être supprimé depuis ici.
        </div>
    @endif

    <div class="admin-card">
        <form method="POST" action="{{ route('admin.admin.update', $admin->id) }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="admin-form-grid">
                <div>
                    <label for="first_name" class="admin-label">Prénom</label>
                    <input type="text" name="first_name" id="first_name" class="admin-input"
                           value="{{ old('first_name', $admin->first_name) }}" required>
                </div>

                <div>
                    <label for="last_name" class="admin-label">Nom</label>
                    <input type="text" name="last_name" id="last_name" class="admin-input"
                           value="{{ old('last_name', $admin->last_name) }}" required>
                </div>

                <div>
                    <label for="email" class="admin-label">Email</label>
                    <input type="email" name="email" id="email" class="admin-input"
                           value="{{ old('email', $admin->email) }}" required>
                </div>

                <div>
                    <label for="phone" class="admin-label">Téléphone (optionnel)</label>
                    <input type="text" name="phone" id="phone" class="admin-input"
                           value="{{ old('phone', $admin->phone) }}">
                </div>

                <div>
                    <label for="company_name" class="admin-label">Entreprise (optionnel)</label>
                    <input type="text" name="company_name" id="company_name" class="admin-input"
                           value="{{ old('company_name', $admin->company_name) }}">
                </div>

                <div>
                    <label for="account_status" class="admin-label">Statut du compte</label>
                    @if ($admin->id === Auth::id())
                        <select class="admin-select" disabled>
                            <option selected>Actif</option>
                        </select>
                        <p class="admin-help">Verrouillé : vous ne pouvez pas désactiver votre propre compte.</p>
                    @else
                        <select name="account_status" id="account_status" class="admin-select">
                            <option value="active" {{ $admin->account_status === 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="pending" {{ $admin->account_status === 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="inactive" {{ $admin->account_status === 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    @endif
                </div>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-btn admin-btn-blue">Mettre à jour</button>
                <a href="{{ route('admin.admin.index') }}" class="admin-btn admin-btn-gray">Annuler</a>
            </div>
        </form>
    </div>

    @if ($admin->id !== Auth::id())
        <div class="admin-card" style="border-color: rgba(220,38,38,0.25);">
            <h2 class="admin-card-title" style="color: #b91c1c;">Zone de danger</h2>
            <p class="admin-help" style="margin-bottom: 14px;">La suppression d'un administrateur est définitive et irréversible.</p>
            <form method="POST" action="{{ route('admin.admin.destroy', $admin->id) }}"
                  onsubmit="return ouvrirModal(this, 'Supprimer cet administrateur', 'Le compte sera définitivement supprimé. Cette action est irréversible.', 'Supprimer définitivement', 'admin-btn-red')">
                @csrf
                @method('DELETE')
                <button type="submit" class="admin-btn admin-btn-red">Supprimer ce compte</button>
            </form>
        </div>
    @endif

@endsection
