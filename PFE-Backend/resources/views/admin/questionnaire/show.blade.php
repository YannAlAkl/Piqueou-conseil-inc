@extends('layouts.admin')

@section('title', 'Détails du questionnaire')

@section('content')

    <h3>Questions et réponses</h3>

    @foreach ($soumission->questionnaire->questions as $question)
        @php $reponse = $reponses->get($question->id); @endphp

        <p><strong>{{ $question->question }}</strong></p>
        @if ($reponse)
            <p>Réponse du client : {{ $reponse->answer ?? 'Pas de réponse' }}</p>
            <p>Commentaire du client : {{ $reponse->client_comment ?? '-' }}</p>
            <p>Recommandation de l'analyste : {{ $reponse->analyst_recommendation ?? '-' }}</p>
        @else
            <p>Pas de réponse</p>
        @endif
        <hr>
    @endforeach

    <h3>Conclusion de l'analyste</h3>
    <p>{{ $soumission->conclusion ?? 'Pas encore de conclusion' }}</p>

    <a href="{{ route('admin.questionnaire.index') }}">Retour à la liste</a>

@endsection
