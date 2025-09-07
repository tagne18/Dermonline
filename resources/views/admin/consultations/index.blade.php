@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des consultations</h1>
    </div>

    <!-- Filtres et recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres de recherche</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.consultations.index') }}" method="GET" class="form-inline">
                <div class="form-group mb-2 mr-2">
                    <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="form-group mb-2 mr-2">
                    <select name="status" class="form-control">
                        <option value="all">Tous les statuts</option>
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-2 mr-2">
                    <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                </div>
                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fas fa-search fa-sm"></i> Filtrer
                </button>
                <a href="{{ route('admin.consultations.index') }}" class="btn btn-secondary mb-2 ml-2">
                    <i class="fas fa-sync-alt fa-sm"></i> Réinitialiser
                </a>
            </form>
        </div>
    </div>

    <!-- Tableau des consultations -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des consultations</h6>
            <span class="badge badge-primary">{{ $consultations->total() }} consultation(s)</span>
        </div>
        <div class="card-body">
            @if($consultations->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Médecin</th>
                                <th>Date</th>
                                <th>Heure</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($consultations as $consultation)
                            <tr>
                                <td>#{{ $consultation->id }}</td>
                                <td>{{ $consultation->patient->name ?? 'N/A' }}</td>
                                <td>{{ $consultation->medecin->name ?? 'N/A' }}</td>
                                <td>{{ $consultation->date->format('d/m/Y') }}</td>
                                <td>{{ $consultation->time ?? 'Non spécifiée' }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'scheduled' => 'info',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ][$consultation->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">
                                        {{ $statuses[$consultation->status] ?? $consultation->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.consultations.show', $consultation) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $consultations->appends(request()->query())->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    Aucune consultation trouvée avec les critères sélectionnés.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush