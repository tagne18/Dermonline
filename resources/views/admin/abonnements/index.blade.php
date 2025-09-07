
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des abonnements</h1>
        <div>
            <a href="{{ route('admin.abonnements.export', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-export"></i> Exporter
            </a>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total des abonnements</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Abonnements actifs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['actifs'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Expirent bientôt (7j)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['expires'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Expirent aujourd'hui</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['expires_today'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtres de recherche</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.abonnements.index') }}" method="GET" class="form-inline">
                <div class="form-group mb-2 mr-2">
                    <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
                </div>
                <div class="form-group mb-2 mr-2">
                    <select name="statut" class="form-control">
                        <option value="">Tous les statuts</option>
                        @foreach($statuts as $value => $label)
                            <option value="{{ $value }}" {{ request('statut') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-2 mr-2">
                    <select name="type" class="form-control">
                        <option value="">Tous les types</option>
                        @foreach($types as $value => $label)
                            <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-2 mr-2">
                    <input type="date" class="form-control" name="date_debut" value="{{ request('date_debut') }}" placeholder="Date début">
                </div>
                <div class="form-group mb-2 mr-2">
                    <input type="date" class="form-control" name="date_fin" value="{{ request('date_fin') }}" placeholder="Date fin">
                </div>
                <button type="submit" class="btn btn-primary mb-2">
                    <i class="fas fa-search fa-sm"></i> Filtrer
                </button>
                <a href="{{ route('admin.abonnements.index') }}" class="btn btn-secondary mb-2 ml-2">
                    <i class="fas fa-sync-alt fa-sm"></i> Réinitialiser
                </a>
            </form>
        </div>
    </div>

    <!-- Tableau des abonnements -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des abonnements</h6>
            <span class="badge badge-primary">{{ $abonnements->total() }} abonnement(s) trouvé(s)</span>
        </div>
        <div class="card-body">
            @if($abonnements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Médecin</th>
                                <th>Type</th>
                                <th>Période</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($abonnements as $abonnement)
                                @php
                                    $joursRestants = now()->diffInDays(Carbon\Carbon::parse($abonnement->date_fin), false);
                                    $estExpire = $joursRestants < 0;
                                    $vaBientotExpirer = $joursRestants > 0 && $joursRestants <= 7;
                                @endphp
                                <tr class="{{ $estExpire ? 'table-danger' : ($vaBientotExpirer ? 'table-warning' : '') }}">
                                    <td>#{{ $abonnement->id }}</td>
                                    <td>
                                        <div>{{ $abonnement->patient->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $abonnement->patient->email ?? '' }}</small>
                                    </td>
                                    <td>{{ $abonnement->medecin->name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $typeClass = [
                                                'mensuel' => 'info',
                                                'trimestriel' => 'primary',
                                                'annuel' => 'success',
                                                'ponctuel' => 'secondary'
                                            ][$abonnement->type] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $typeClass }}">
                                            {{ ucfirst($abonnement->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>Début: {{ $abonnement->date_debut->format('d/m/Y') }}</div>
                                        <div>Fin: {{ $abonnement->date_fin->format('d/m/Y') }}</div>
                                        @if(!$estExpire)
                                            <small class="text-muted">{{ $joursRestants }} jours restants</small>
                                        @else
                                            <small class="text-danger">Expiré</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'actif' => 'success',
                                                'inactif' => 'secondary',
                                                'en_attente' => 'warning',
                                                'annule' => 'danger',
                                                'suspendu' => 'info'
                                            ][$abonnement->statut] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-{{ $statusClass }}">
                                            {{ $statuts[$abonnement->statut] ?? $abonnement->statut }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.abonnements.show', $abonnement) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Menu déroulant pour les actions rapides -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if($abonnement->statut !== 'actif')
                                                    <a class="dropdown-item" href="#" 
                                                       onclick="event.preventDefault(); document.getElementById('activate-form-{{ $abonnement->id }}').submit();">
                                                        <i class="fas fa-check text-success"></i> Activer
                                                    </a>
                                                    <form id="activate-form-{{ $abonnement->id }}" 
                                                          action="{{ route('admin.abonnements.update', $abonnement) }}" 
                                                          method="POST" style="display: none;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="statut" value="actif">
                                                    </form>
                                                @endif
                                                
                                                @if($abonnement->statut !== 'suspendu')
                                                    <a class="dropdown-item" href="#" 
                                                       data-toggle="modal" 
                                                       data-target="#suspendModal{{ $abonnement->id }}">
                                                        <i class="fas fa-pause text-warning"></i> Suspendre
                                                    </a>
                                                @endif
                                                
                                                @if($abonnement->statut !== 'annule')
                                                    <a class="dropdown-item text-danger" href="#" 
                                                       data-toggle="modal" 
                                                       data-target="#cancelModal{{ $abonnement->id }}">
                                                        <i class="fas fa-times"></i> Annuler
                                                    </a>
                                                @endif
                                                
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#" 
                                                   data-toggle="modal" 
                                                   data-target="#renewModal{{ $abonnement->id }}">
                                                    <i class="fas fa-sync-alt text-primary"></i> Renouveler
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Modal de suspension -->
                                        <div class="modal fade" id="suspendModal{{ $abonnement->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.abonnements.update', $abonnement) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="statut" value="suspendu">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Suspendre l'abonnement</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="raison">Raison de la suspension :</label>
                                                                <textarea class="form-control" id="raison" name="raison" rows="3" required></textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="date_fin">Date de fin de suspension :</label>
                                                                <input type="date" class="form-control" id="date_fin" name="date_fin" 
                                                                       min="{{ date('Y-m-d') }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-warning">Confirmer la suspension</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal d'annulation -->
                                        <div class="modal fade" id="cancelModal{{ $abonnement->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.abonnements.update', $abonnement) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="statut" value="annule">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Annuler l'abonnement</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Êtes-vous sûr de vouloir annuler cet abonnement ? Cette action est irréversible.</p>
                                                            <div class="form-group">
                                                                <label for="raison">Raison de l'annulation :</label>
                                                                <textarea class="form-control" id="raison" name="raison" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal de renouvellement -->
                                        <div class="modal fade" id="renewModal{{ $abonnement->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.abonnements.renew', $abonnement) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Renouveler l'abonnement</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="duree">Durée du renouvellement :</label>
                                                                <select class="form-control" id="duree" name="duree" required>
                                                                    <option value="1">1 mois - 10 000 FCFA</option>
                                                                    <option value="3">3 mois - 25 000 FCFA</option>
                                                                    <option value="6">6 mois - 45 000 FCFA</option>
                                                                    <option value="12">12 mois - 80 000 FCFA</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="date_debut">Date de début :</label>
                                                                <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                                                       value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="montant">Montant (FCFA) :</label>
                                                                <input type="number" class="form-control" id="montant" name="montant" 
                                                                       value="10000" min="0" step="1000" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-primary">Confirmer le renouvellement</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $abonnements->appends(request()->query())->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    Aucun abonnement trouvé avec les critères sélectionnés.
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
    .table-danger {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialisation des tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Mise à jour dynamique du montant en fonction de la durée
        $('select[name="duree"]').on('change', function() {
            const duree = $(this).val();
            let montant = 0;
            
            switch(duree) {
                case '1':
                    montant = 10000;
                    break;
                case '3':
                    montant = 25000;
                    break;
                case '6':
                    montant = 45000;
                    break;
                case '12':
                    montant = 80000;
                    break;
            }
            
            $('input[name="montant"]').val(montant);
        });
    });
</script>
@endpush
