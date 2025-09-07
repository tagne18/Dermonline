@extends('layouts.medecin')

@section('title', 'Historique des Plannings')

@push('styles')
    <style>
        .card-historique {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }
        
        .card-title {
            margin-bottom: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .card-title i {
            margin-right: 0.75rem;
        }
        
        .planning-card {
            border-left: 4px solid #4e73df;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            background-color: white;
            overflow: hidden;
        }
        
        .planning-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .planning-card .card-body {
            padding: 1.25rem;
        }
        
        .planning-card .card-title {
            color: #2d3748;
            font-size: 1.1rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .planning-date {
            display: flex;
            align-items: center;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        
        .planning-date i {
            margin-right: 0.5rem;
            color: #a0aec0;
        }
        
        .planning-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 0.75rem;
        }
        
        .meta-item {
            display: inline-flex;
            align-items: center;
            background-color: #f8f9fc;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.8rem;
            color: #4a5568;
        }
        
        .meta-item i {
            margin-right: 0.4rem;
            color: #4e73df;
        }
        
        .badge-status {
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-status.planifie {
            background-color: #f6c23e33;
            color: #f6c23e;
        }
        
        .badge-status.confirme {
            background-color: #1cc88a33;
            color: #1cc88a;
        }
        
        .badge-status.annule {
            background-color: #e74a3b33;
            color: #e74a3b;
        }
        
        .badge-status.termine {
            background-color: #6c757d33;
            color: #6c757d;
        }
        
        .filters {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #d1d3e2;
            margin-bottom: 1.5rem;
            display: block;
        }
        
        .empty-state h4 {
            color: #4e73df;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: #6c757d;
            margin-bottom: 1.5rem;
        }
        
        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .pagination .page-link {
            color: #4e73df;
            border: 1px solid #e3e6f0;
            margin: 0 3px;
            border-radius: 8px !important;
            padding: 0.5rem 1rem;
        }
        
        .pagination .page-link:hover {
            background-color: #f8f9fc;
        }
        
        .btn-view {
            background-color: #4e73df;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-view:hover {
            background-color: #3a5ccc;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
        }
        
        .btn-view i {
            margin-right: 0.4rem;
            font-size: 0.7rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #e2e8f0;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: #718096;
            font-size: 0.875rem;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h2 mb-1 text-gray-800">
                <i class="fas fa-history me-2"></i>Historique des Plannings
            </h1>
            <p class="mb-0 text-muted">Consultez l'historique de vos plannings passés et en cours</p>
        </div>
        <div>
            <a href="{{ route('medecin.planning.index') }}" class="btn btn-primary">
                <i class="fas fa-calendar-alt me-2"></i>Voir les plannings actuels
            </a>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(78, 115, 223, 0.1); color: #4e73df;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label">Total des plannings</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(28, 200, 138, 0.1); color: #1cc88a;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-value">{{ $stats['termines'] }}</div>
                <div class="stat-label">Terminés</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value">{{ $stats['en_cours'] }}</div>
                <div class="stat-label">En cours</div>
            </div>
        </div>
        
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon" style="background-color: rgba(231, 74, 59, 0.1); color: #e74a3b;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-value">{{ $stats['annules'] }}</div>
                <div class="stat-label">Annulés</div>
            </div>
        </div>
    </div>

    <div class="card card-historique">
        <div class="card-header">
            <h2 class="h5 mb-0"><i class="fas fa-filter me-2"></i>Filtres avancés</h2>
        </div>
        <div class="card-body p-0">
            <div class="filters">
                <form action="{{ route('medecin.planning.historique') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select" id="statut" name="statut">
                            <option value="">Tous les statuts</option>
                            <option value="planifie" {{ request('statut') == 'planifie' ? 'selected' : '' }}>Planifié</option>
                            <option value="confirme" {{ request('statut') == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                            <option value="annule" {{ request('annule') == 'annule' ? 'selected' : '' }}>Annulé</option>
                            <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="type_consultation" class="form-label">Type de consultation</label>
                        <select class="form-select" id="type_consultation" name="type_consultation">
                            <option value="">Tous les types</option>
                            <option value="presentiel" {{ request('type_consultation') == 'presentiel' ? 'selected' : '' }}>Présentiel</option>
                            <option value="en_ligne" {{ request('type_consultation') == 'en_ligne' ? 'selected' : '' }}>En ligne</option>
                            <option value="hybride" {{ request('type_consultation') == 'hybride' ? 'selected' : '' }}>Hybride</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="date_debut" class="form-label">Période du</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                   value="{{ request('date_debut') }}">
                            <span class="input-group-text">au</span>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" 
                                   value="{{ request('date_fin') }}">
                        </div>
                    </div>
                    
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i>Appliquer les filtres
                        </button>
                        <a href="{{ route('medecin.planning.historique') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt me-1"></i>Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card card-historique">
        <div class="card-header">
            <h2 class="h5 mb-0"><i class="fas fa-list me-2"></i>Liste des plannings</h2>
        </div>
        
        <div class="card-body">
            @if($plannings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Titre</th>
                                <th>Créneau horaire</th>
                                <th>Type</th>
                                <th>Prix</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plannings as $planning)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $planning->date_consultation->isoFormat('DD/MM/YYYY') }}</span>
                                            <small class="text-muted">{{ $planning->date_consultation->isoFormat('dddd', 'fr') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $planning->titre }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-clock text-muted me-2"></i>
                                            <span>{{ $planning->heure_debut }} - {{ $planning->heure_fin }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-{{ $planning->type_consultation == 'en_ligne' ? 'video' : ($planning->type_consultation == 'presentiel' ? 'building' : 'exchange-alt') }} me-1"></i>
                                            {{ $planning->type_consultation_libelle }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ number_format($planning->prix, 0, ',', ' ') }} FCFA</span>
                                    </td>
                                    <td>
                                        <span class="badge-status {{ $planning->statut }}">
                                            <i class="fas {{ $planning->statut == 'planifie' ? 'fa-clock' : ($planning->statut == 'confirme' ? 'fa-check-circle' : ($planning->statut == 'annule' ? 'fa-times-circle' : 'fa-calendar-check')) }} me-1"></i>
                                            {{ ucfirst($planning->statut) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('medecin.planning.show', $planning) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Voir les détails">
                                            <i class="far fa-eye"></i>
                                        </a>
                                        @if($planning->statut == 'planifie')
                                            <a href="{{ route('medecin.planning.edit', $planning) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Modifier">
                                                <i class="far fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $plannings->appends(request()->query())->links() }}
                </div>
                
            @else
                <div class="empty-state">
                    <i class="far fa-calendar-times"></i>
                    <h4>Aucun planning trouvé</h4>
                    <p>Aucun planning ne correspond à vos critères de recherche.</p>
                    <a href="{{ route('medecin.planning.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer un nouveau planning
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Activer les tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Initialiser les dates dans le filtre
        const today = new Date();
        const firstDay = new Date(today.getFullYear(), today.getMonth() - 1, 1);
        const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
        
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');
        
        // Si les champs de date sont vides, définir les valeurs par défaut (mois précédent)
        if (dateDebut && !dateDebut.value) {
            dateDebut.valueAsDate = firstDay;
        }
        
        if (dateFin && !dateFin.value) {
            dateFin.valueAsDate = lastDay;
        }
    });
</script>
@endpush

@endsection
