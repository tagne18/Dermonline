@extends('layouts.app')
@section('content')
<div class="container my-5">
    <h2 class="mb-4">Mes résultats d'examens</h2>
    @if($examResults->isEmpty())
        <div class="alert alert-info">Aucun résultat d'examen disponible.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Médecin</th>
                    <th>Date</th>
                    <th>Fichier</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($examResults as $exam)
                <tr>
                    <td>{{ $exam->titre }}</td>
                    <td>{{ $exam->medecin ? $exam->medecin->name : '-' }}</td>
                    <td>{{ $exam->date_examen }}</td>
                    <td>
                        @if($exam->fichier)
                            <a href="{{ route('patient.examens.download', $exam->id) }}" class="btn btn-sm btn-outline-primary">Télécharger</a>
                        @else
                            <span class="text-muted">Aucun</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('patient.examens.show', $exam->id) }}" class="btn btn-sm btn-info">Détail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
