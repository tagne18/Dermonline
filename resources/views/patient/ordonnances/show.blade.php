@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <h2>Détail de l'ordonnance</h2>
    <div class="card mb-3 mt-3">
        <div class="card-body">
            <p><strong>Titre:</strong> {{ $prescription->titre }}</p>
            <p><strong>Médecin :</strong> {{ $prescription->medecin ? $prescription->medecin->name : '-' }}</p>
            <p><strong>Date :</strong> {{ $prescription->date_prescription }}</p>
            <p><strong>Description :</strong><br>{{ $prescription->description ?? '-' }}</p>
            @if($prescription->fichier)
                <a href="{{ route('patient.ordonnances.download', $prescription->id) }}" class="btn btn-primary">Télécharger le fichier</a>
            @endif
            <a href="{{ route('patient.ordonnances') }}" class="btn btn-secondary mt-2">Retour à la liste</a>
        </div>
    </div>
</div>
@endsection
