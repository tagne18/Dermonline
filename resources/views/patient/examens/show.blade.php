@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h2>Détail du résultat d'examen</h2>
    <div class="card mb-3 mt-3">
        <div class="card-body">
            <p><strong>Titre:</strong> {{ $examResult->titre }}</p>
            <p><strong>Médecin :</strong> {{ $examResult->medecin ? $examResult->medecin->name : '-' }}</p>
            <p><strong>Date :</strong> {{ $examResult->date_examen }}</p>
            <p><strong>Description :</strong><br>{{ $examResult->description ?? '-' }}</p>
            @if($examResult->fichier)
                <a href="{{ route('patient.examens.download', $examResult->id) }}" class="btn btn-primary">Télécharger le fichier</a>
            @endif
            <a href="{{ route('patient.examens') }}" class="btn btn-secondary mt-2">Retour à la liste</a>
        </div>
    </div>
</div>
@endsection
