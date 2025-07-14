@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-white mb-0">Gestion des Médecins</h1>
                <div class="btn-group">
                    <a href="{{ route('admin.medecins.statistiques') }}" class="btn btn-info">
                        📊 Statistiques
                    </a>
                    <a href="{{ route('admin.medecins.export') }}" class="btn btn-success">
                        📥 Exporter CSV
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Cartes de statistiques -->
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Médecins</h5>
                    <h2>{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Actifs</h5>
                    <h2>{{ $stats['actifs'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Bloqués</h5>
                    <h2>{{ $stats['bloques'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Nouveaux (30j)</h5>
                    <h2>{{ $stats['nouveaux'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.medecins.index') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Nom, email, spécialité, ville...">
                        </div>
                        <div class="col-md-2">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select" id="statut" name="statut">
                                <option value="">Tous</option>
                                <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actifs</option>
                                <option value="bloque" {{ request('statut') === 'bloque' ? 'selected' : '' }}>Bloqués</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="specialite" class="form-label">Spécialité</label>
                            <select class="form-select" id="specialite" name="specialite">
                                <option value="">Toutes</option>
                                @foreach($specialites as $spec)
                                    <option value="{{ $spec }}" {{ request('specialite') === $spec ? 'selected' : '' }}>
                                        {{ $spec }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sort" class="form-label">Tri</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                                <option value="specialite" {{ request('sort') === 'specialite' ? 'selected' : '' }}>Spécialité</option>
                                <option value="ville" {{ request('sort') === 'ville' ? 'selected' : '' }}>Ville</option>
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
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            <a href="{{ route('admin.medecins.index') }}" class="btn btn-secondary">Réinitialiser</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages de succès -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Tableau des médecins -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Liste des Médecins ({{ $medecins->total() }} résultats)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Médecin</th>
                                    <th>Contact</th>
                                    <th>Spécialité & Localisation</th>
                                    <th>Statistiques</th>
                                    <th>Statut</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medecins as $medecin)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    @if($medecin->profile_photo_path)
                                                        <img src="{{ Storage::url($medecin->profile_photo_path) }}" 
                                                             class="rounded-circle" width="40" height="40" alt="Photo">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <span class="text-white">{{ substr($medecin->name, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $medecin->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">ID: {{ $medecin->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>Email:</strong> {{ $medecin->email }}<br>
                                                <strong>Téléphone:</strong> {{ $medecin->phone ?? 'Non renseigné' }}<br>
                                                <strong>Langue:</strong> {{ $medecin->langue === 'fr' ? 'Français' : 'Anglais' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>Spécialité:</strong> {{ $medecin->specialite ?? 'Non renseignée' }}<br>
                                                <strong>Ville:</strong> {{ $medecin->ville ?? 'Non renseignée' }}<br>
                                                <strong>Lieu de travail:</strong> {{ $medecin->lieu_travail ?? 'Non renseigné' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="badge bg-primary">{{ $medecin->abonnes_count }} abonnés</span><br>
                                                <span class="badge bg-info">{{ $medecin->consultations_as_medecin_count }} consultations</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($medecin->is_blocked)
                                                <span class="badge bg-danger">Bloqué</span>
                                                @if($medecin->blocked_at)
                                                    <br><small class="text-muted">Depuis: {{ optional($medecin->blocked_at)->format('d/m/Y') }}</small>
                                                @endif
                                            @else
                                                <span class="badge bg-success">Actif</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ optional($medecin->created_at)->format('d/m/Y H:i') }}
                                            <br>
                                            <small class="text-muted">{{ optional($medecin->created_at)->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Bouton voir détails -->
                                                <a href="{{ route('admin.medecins.show', $medecin->id) }}" 
                                                   class="btn btn-primary btn-sm" title="Voir détails">
                                                    👁️
                                                </a>
                                                
                                                <!-- Bouton d'alerte -->
                                                <button type="button" class="btn btn-warning btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#alerteModal{{ $medecin->id }}"
                                                        title="Envoyer une alerte">
                                                    ⚠️
                                                </button>
                                                
                                                <!-- Bouton bloquer/débloquer -->
                                                @if($medecin->is_blocked)
                                                    <form action="{{ route('admin.medecins.debloquer', $medecin->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" 
                                                                onclick="return confirm('Débloquer ce médecin ?')"
                                                                title="Débloquer">
                                                            🔓
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.medecins.bloquer', $medecin->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm" 
                                                                onclick="return confirm('Bloquer ce médecin ? Il ne pourra plus se connecter.')"
                                                                title="Bloquer">
                                                            🔒
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <!-- Bouton supprimer -->
                                                <form action="{{ route('admin.medecins.destroy', $medecin->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Supprimer définitivement ce médecin ?')"
                                                            title="Supprimer">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Modal d'alerte pour chaque médecin -->
                                    <div class="modal fade" id="alerteModal{{ $medecin->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Envoyer une alerte à {{ $medecin->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.medecins.alerte', $medecin->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="type{{ $medecin->id }}" class="form-label">Type d'alerte</label>
                                                            <select class="form-select" id="type{{ $medecin->id }}" name="type" required>
                                                                <option value="info">Information</option>
                                                                <option value="warning">Avertissement</option>
                                                                <option value="danger">Urgent</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="message{{ $medecin->id }}" class="form-label">Message</label>
                                                            <textarea class="form-control" id="message{{ $medecin->id }}" 
                                                                      name="message" rows="4" maxlength="500" 
                                                                      placeholder="Votre message d'alerte..." required></textarea>
                                                            <div class="form-text">Maximum 500 caractères</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-warning">Envoyer l'alerte</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-user-md fa-3x mb-3"></i>
                                                <p>Aucun médecin trouvé avec les critères de recherche actuels.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($medecins->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $medecins->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm img {
    object-fit: cover;
}
.btn-group .btn {
    margin-right: 2px;
}
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.table th {
    border-top: none;
}
</style>
@endsection
