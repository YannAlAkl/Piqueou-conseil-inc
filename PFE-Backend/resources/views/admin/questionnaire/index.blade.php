@extends('layouts.admin')

@section('title', 'Questionnaires en analyse ou terminés')
@section('subtitle', "Consultez les recommandations et conclusions des analystes.")

@section('content')

    <div class="admin-table-box">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Entreprise</th>
                    <th>Questionnaire</th>
                    <th>Statut</th>
                    <th>Détail</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($questionnaires as $questionnaire)
                    <tr>
                        <td>{{ $questionnaire->user->name }}</td>
                        <td>{{ $questionnaire->user->company_name ?? '-' }}</td>
                        <td>{{ $questionnaire->questionnaire->title }}</td>
                        <td>
                            @if ($questionnaire->status === 'under_review')
                                <span class="admin-badge admin-badge-green">En analyse</span>
                            @else
                                <span class="admin-badge admin-badge-green">Terminé</span>
                            @endif
                        </td>
                        <td>
                            @if ($questionnaire->status === 'completed')
                                <a href="{{ route('admin.questionnaire.show', $questionnaire->id) }}" class="admin-btn admin-btn-blue">
                                    Voir les recommandations
                                </a>
                            @else
                                <span class="text-xs text-gray-400">En attente de l'analyste</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="admin-table-empty">Aucun questionnaire en analyse ou terminé pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
