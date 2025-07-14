@extends('layouts.medecin')

@section('title', 'Rendez-vous reçus')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📅 Rendez-vous reçus</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <!-- Debug info -->
    <div class="alert alert-info">
        <strong>Debug:</strong> {{ $appointments->count() }} rendez-vous trouvés pour le médecin ID: {{ auth()->id() }}
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Date & Heure</th>
                    <th>Motif</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $rdv)
                    <tr>
                        <td>
                            {{ $rdv->patient_name ?? $rdv->user->name ?? 'Patient inconnu' }}<br>
                            <small>{{ $rdv->patient_phone ?? $rdv->user->phone ?? 'Téléphone non renseigné' }}</small>
                        </td>
                        <td>{{ $rdv->date }}</td>
                        <td>{{ $rdv->motif }}</td>
                        <td>{{ $rdv->description }}</td>
                        <td>{{ ucfirst($rdv->type ?? 'Non défini') }}</td>
                        <td>
                            @if($rdv->statut == 'en_attente')
                                <span class="badge bg-warning">En attente</span>
                            @elseif($rdv->statut == 'valide')
                                <span class="badge bg-success">Validé</span>
                            @elseif($rdv->statut == 'refuse')
                                <span class="badge bg-danger">Refusé</span>
                            @endif
                        </td>
                        <td>
                            @if($rdv->statut == 'en_attente')
                                <form method="POST" action="{{ route('medecin.appointments.validate', $rdv->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Valider</button>
                                </form>
                                <form method="POST" action="{{ route('medecin.appointments.refuse', $rdv->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                                </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Aucun rendez-vous reçu.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
