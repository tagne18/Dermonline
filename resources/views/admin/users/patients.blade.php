@extends('layouts.admin')

@section('title', 'Gestion des Patients')

@section('content')
<div class="container-fluid">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-white text-3xl font-bold mb-6">👥 Gestion des Patients</h1>
        </div>
        
        <!-- Cartes de statistiques -->
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Patients</h5>
                            <h2 class="mb-0">{{ $stats['total'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Abonnés Actifs</h5>
                            <h2 class="mb-0">{{ $stats['abonnes'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Comptes Bloqués</h5>
                            <h2 class="mb-0">{{ $stats['bloques'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-ban fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Nouveaux (30j)</h5>
                            <h2 class="mb-0">{{ $stats['nouveaux'] }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.patients') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">🔍 Rechercher</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Nom, email, ID, téléphone, ville...">
                </div>
                
                <div class="col-md-2">
                    <label for="abonnement" class="form-label">📦 Abonnement</label>
                    <select class="form-select" id="abonnement" name="abonnement">
                        <option value="">Tous</option>
                        <option value="actif" {{ request('abonnement') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('abonnement') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="statut" class="form-label">🔒 Statut</label>
                    <select class="form-select" id="statut" name="statut">
                        <option value="">Tous</option>
                        <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="bloque" {{ request('statut') === 'bloque' ? 'selected' : '' }}>Bloqué</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="sort" class="form-label">📊 Trier par</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                        <option value="email" {{ request('sort') === 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="order" class="form-label">Ordre</label>
                    <select class="form-select" id="order" name="order">
                        <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                    <a href="{{ route('admin.users.patients') }}" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tableau des patients -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Liste des Patients 
                <span class="badge bg-primary">{{ $patients->total() }} résultat(s)</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Informations</th>
                            <th>Contact</th>
                            <th>Localisation</th>
                            <th>Abonnement</th>
                            <th>Dernier RDV</th>
                            <th>Statut</th>
                            <th>Inscrit le</th>
                            <th>Actions</th>
            </tr>
        </thead>
        <tbody>
                        @forelse($patients as $patient)
                            <tr class="{{ $patient->is_blocked ? 'table-danger' : '' }}">
                                <td class="align-middle">
                                    <span class="badge bg-secondary">#{{ $patient->id }}</span>
                                </td>
                                
                                <td class="align-middle">
                                    <img src="{{ $patient->profile_photo_url }}" 
                                         alt="Photo de {{ $patient->name }}"
                                         class="rounded-circle" 
                                         width="40" height="40"
                                         style="object-fit: cover;">
                                </td>
                                
                                <td class="align-middle">
                                    <div>
                                        <strong>{{ $patient->name }}</strong>
                                        @if($patient->gender)
                                            <span class="badge bg-info ms-1">{{ $patient->gender }}</span>
                                        @endif
                                    </div>
                                    @if($patient->birth_date)
                                        <small class="text-muted">
                                            <i class="fas fa-birthday-cake"></i> 
                                            {{ \Carbon\Carbon::parse($patient->birth_date)->age }} ans
                                        </small>
                                    @endif
                                    @if($patient->profession)
                                        <div><small class="text-muted">{{ $patient->profession }}</small></div>
                                    @endif
                                </td>
                                
                                <td class="align-middle">
                                    <div><i class="fas fa-envelope"></i> {{ $patient->email }}</div>
                                    @if($patient->phone)
                                        <div><i class="fas fa-phone"></i> {{ $patient->phone }}</div>
                                    @endif
                                </td>
                                
                                <td class="align-middle">
                                    @if($patient->city)
                                        <i class="fas fa-map-marker-alt"></i> {{ $patient->city }}
                                    @else
                                        <span class="text-muted">Non renseigné</span>
                                    @endif
                                </td>
                                
                                <td class="align-middle">
                                    @if($patient->abonnement && $patient->abonnement->statut === 'actif')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Actif
                                        </span>
                                        @if($patient->abonnement->date_fin)
                                            <br><small class="text-muted">
                                                Expire: {{ $patient->abonnement->date_fin ? \Carbon\Carbon::parse($patient->abonnement->date_fin)->format('d/m/Y') : '' }}
                                            </small>
                                        @endif
                        @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times"></i> Inactif
                                        </span>
                        @endif
                    </td>
                                
                                <td class="align-middle">
                                    @if($patient->appointments->isNotEmpty())
                                        <div class="text-success">
                                            <i class="fas fa-calendar-check"></i>
                            {{ $patient->appointments->first()->date ? \Carbon\Carbon::parse($patient->appointments->first()->date)->format('d/m/Y') : '' }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $patient->appointments->count() }} RDV total
                                        </small>
                                    @else
                                        <span class="text-muted">Aucun RDV</span>
                                    @endif
                                </td>
                                
                                <td class="align-middle">
                                    @if($patient->is_blocked)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-ban"></i> Bloqué
                                        </span>
                                        @if($patient->blocked_at)
                                            <br><small class="text-muted">
                                                {{ $patient->blocked_at ? \Carbon\Carbon::parse($patient->blocked_at)->format('d/m/Y') : '' }}
                                            </small>
                                        @endif
                        @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Actif
                                        </span>
                        @endif
                    </td>
                                
                                <td class="align-middle">
                                    <div>{{ $patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->format('d/m/Y') : '' }}</div>
                                    <small class="text-muted">{{ $patient->created_at ? $patient->created_at->diffForHumans() : '' }}</small>
                                </td>
                                
                                <td class="align-middle">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.patients.show', $patient->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if(!$patient->is_blocked)
                                            <form action="{{ route('admin.utilisateurs.bloquer', $patient->id) }}" 
                                                  method="POST" class="d-inline">
                            @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" 
                                                        title="Bloquer le compte"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir bloquer ce patient ?')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                        </form>
                    @else
                                            <form action="{{ route('admin.utilisateurs.debloquer', $patient->id) }}" 
                                                  method="POST" class="d-inline">
                            @csrf
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        title="Débloquer le compte">
                                                    <i class="fas fa-unlock"></i>
                                                </button>
                        </form>
                    @endif
                                        
                                        <form action="{{ route('admin.users.patients.destroy', $patient->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="Supprimer définitivement"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ? Cette action est irréversible.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Aucun patient trouvé avec ces critères.</p>
                                </td>
                </tr>
            @endforelse
        </tbody>
    </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($patients->hasPages())
            <div class="card-footer">
                {{ $patients->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation pour les actions importantes -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmAction">Confirmer</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Fonction pour confirmer les actions importantes
function confirmAction(message, action) {
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmAction').onclick = action;
    new bootstrap.Modal(document.getElementById('confirmModal')).show();
}

// Auto-submit du formulaire de recherche lors du changement de filtres
document.querySelectorAll('#abonnement, #statut, #sort, #order').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush
