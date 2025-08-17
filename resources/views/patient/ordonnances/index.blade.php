@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4 mt-5">Mes ordonnances</h1>
    @if($prescriptions->isEmpty())
        <div class="alert alert-info">Aucune ordonnance disponible.</div>
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
                @foreach($prescriptions as $prescription)
                <tr>
                    <td>{{ $prescription->titre }}</td>
                    <td>{{ $prescription->medecin ? $prescription->medecin->name : '-' }}</td>
                    <td>{{ $prescription->date_prescription }}</td>
                    <td>
                        @if($prescription->fichier)
                            <a href="{{ route('patient.ordonnances.download', $prescription->id) }}" class="btn btn-sm btn-outline-primary">Télécharger</a>
                        @else
                            <span class="text-muted">Aucun</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('patient.ordonnances.show', $prescription->id) }}" class="btn btn-sm btn-info">Détail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
