@extends('layouts.admin')

@section('title', 'Questionnaires envoyés')
@section('subtitle', 'Assignez un analyste à chaque dossier envoyé par un client.')

@section('content')

    <div class="admin-table-box">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Entreprise</th>
                    <th>Questionnaire</th>
                    <th>Envoyé le</th>
                    <th>Statut</th>
                    <th>Analyste</th>
                    <th>Assigner</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($soumissions as $soumission)
                    <tr>
                        <td>{{ $soumission->user->name }}</td>
                        <td>{{ $soumission->user->company_name ?? '-' }}</td>
                        <td>{{ $soumission->questionnaire->title }}</td>
                        <td>{{ $soumission->submitted_at ? $soumission->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            @if ($soumission->status === 'submitted')
                                <span class="admin-badge admin-badge-yellow">À assigner</span>
                            @elseif ($soumission->status === 'under_review')
                                <span class="admin-badge admin-badge-green">En analyse</span>
                            @else
                                <span class="admin-badge admin-badge-green">Terminé</span>
                            @endif
                        </td>
                        <td>{{ $soumission->analyst ? $soumission->analyst->name : '-' }}</td>
                        <td>
                            @if ($soumission->status === 'completed')
                                <span class="text-xs text-gray-400">Dossier clos</span>
                            @else
                                <form method="POST" action="{{ route('admin.submission.assign', $soumission->id) }}">
                                    @csrf
                                    <div class="flex items-center gap-2">
                                        <select name="analyst_id" class="admin-select" required>
                                            <option value="">Choisir un analyste</option>
                                            @foreach ($analystes as $analyste)
                                                <option value="{{ $analyste->id }}"
                                                    {{ $soumission->analyst_id === $analyste->id ? 'selected' : '' }}>
                                                    {{ $analyste->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="admin-btn admin-btn-blue">Assigner</button>
                                    </div>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="admin-table-empty">Aucun questionnaire envoyé pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($soumissions->hasPages())
        <div class="admin-pagination">
            @if ($soumissions->onFirstPage())
                <span class="admin-btn admin-btn-gray">Précédent</span>
            @else
                <a href="{{ $soumissions->previousPageUrl() }}" class="admin-btn admin-btn-gray">Précédent</a>
            @endif

            <span>Page {{ $soumissions->currentPage() }} sur {{ $soumissions->lastPage() }}</span>

            @if ($soumissions->hasMorePages())
                <a href="{{ $soumissions->nextPageUrl() }}" class="admin-btn admin-btn-gray">Suivant</a>
            @else
                <span class="admin-btn admin-btn-gray">Suivant</span>
            @endif
        </div>
    @endif

@endsection
