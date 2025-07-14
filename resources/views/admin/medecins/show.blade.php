@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-white mb-2">Détails du Médecin</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.medecins.index') }}" class="text-white">Médecins</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">{{ $medecin->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.medecins.index') }}" class="btn btn-secondary">← Retour</a>
                    @if($medecin->is_blocked)
                        <form action="{{ route('admin.medecins.debloquer', $medecin->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">🔓 Débloquer</button>
                        </form>
                    @else
                        <form action="{{ route('admin.medecins.bloquer', $medecin->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">🔒 Bloquer</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations du Médecin</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Nom complet:</strong> {{ $medecin->name }}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong> {{ $medecin->email }}
                            </div>
                            <div class="mb-3">
                                <strong>Téléphone:</strong> {{ $medecin->phone ?? 'Non renseigné' }}
                            </div>
                            <div class="mb-3">
                                <strong>Langue:</strong> {{ $medecin->langue === 'fr' ? 'Français' : 'Anglais' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Spécialité:</strong> {{ $medecin->specialite ?? 'Non renseignée' }}
                            </div>
                            <div class="mb-3">
                                <strong>Ville:</strong> {{ $medecin->ville ?? 'Non renseignée' }}
                            </div>
                            <div class="mb-3">
                                <strong>Lieu de travail:</strong> {{ $medecin->lieu_travail ?? 'Non renseigné' }}
                            </div>
                            <div class="mb-3">
                                <strong>Date d'inscription:</strong> {{ $medecin->created_at ? \Carbon\Carbon::parse($medecin->created_at)->format('d/m/Y H:i') : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="bg-primary text-white rounded p-3">
                                <h3>{{ $stats['total_abonnes'] }}</h3>
                                <small>Abonnés</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-success text-white rounded p-3">
                                <h3>{{ $stats['total_consultations'] }}</h3>
                                <small>Consultations</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-info text-white rounded p-3">
                                <h3>{{ $stats['abonnes_ce_mois'] }}</h3>
                                <small>Nouveaux (ce mois)</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-warning text-white rounded p-3">
                                <h3>{{ $stats['consultations_ce_mois'] }}</h3>
                                <small>Consultations (ce mois)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($medecin->abonnes->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Patients Abonnés ({{ $medecin->abonnes->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Email</th>
                                    <th>Date d'abonnement</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($medecin->abonnes as $abonnement)
                                    <tr>
                                        <td>{{ $abonnement->patient->name ?? 'Patient supprimé' }}</td>
                                        <td>{{ $abonnement->patient->email ?? 'N/A' }}</td>
                                        <td>{{ $abonnement->created_at ? \Carbon\Carbon::parse($abonnement->created_at)->format('d/m/Y') : '' }}</td>
                                        <td>
                                            @if($abonnement->patient && $abonnement->patient->is_blocked)
                                                <span class="badge bg-danger">Bloqué</span>
                                            @else
                                                <span class="badge bg-success">Actif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.breadcrumb-item a {
    text-decoration: none;
}
</style>
@endsection
