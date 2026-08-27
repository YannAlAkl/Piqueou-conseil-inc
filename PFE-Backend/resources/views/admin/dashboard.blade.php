@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('subtitle', 'Vue générale des comptes du portail.')

@section('content')

    <div class="admin-stats">
        <div class="admin-stat">
            <p class="admin-stat-label">Clients</p>
            <p class="admin-stat-value">{{ $stats['clients'] }}</p>
        </div>
        <div class="admin-stat">
            <p class="admin-stat-label">Analystes</p>
            <p class="admin-stat-value">{{ $stats['analysts'] }}</p>
        </div>
        <div class="admin-stat">
            <p class="admin-stat-label">En attente</p>
            <p class="admin-stat-value">{{ $stats['pending'] }}</p>
        </div>
        <div class="admin-stat">
            <p class="admin-stat-label">Actifs</p>
            <p class="admin-stat-value">{{ $stats['active'] }}</p>
        </div>
        <div class="admin-stat">
            <p class="admin-stat-label">Inactifs</p>
            <p class="admin-stat-value">{{ $stats['inactive'] }}</p>
        </div>
    </div>

    <div class="admin-card">
        <h2 class="admin-card-title">Gestion des comptes</h2>

        <div class="admin-form-grid">
            <a href="{{ route('admin.analyst.index') }}" class="block rounded border border-gray-200 p-4 hover:border-blue-500">
                <p class="admin-stat-label">Analystes</p>
                <p class="mt-1 text-base font-semibold text-gray-900">Voir la liste des analystes</p>
            </a>
            <a href="{{ route('admin.client.index') }}" class="block rounded border border-gray-200 p-4 hover:border-blue-500">
                <p class="admin-stat-label">Clients</p>
                <p class="mt-1 text-base font-semibold text-gray-900">Voir la liste des clients</p>
            </a>
        </div>
    </div>

@endsection
