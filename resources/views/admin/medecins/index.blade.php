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
                                                    <button type="button" class="btn btn-success btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#unblockDoctorModal{{ $medecin->id }}"
                                                            title="Débloquer le médecin">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>

                                                    <!-- Modal de confirmation de déblocage -->
                                                    <div class="modal fade modal-unblock" id="unblockDoctorModal{{ $medecin->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">
                                                                <div class="modal-header bg-success text-white">
                                                                    <h5 class="modal-title fw-bold">
                                                                        <i class="fas fa-unlock-alt me-2"></i>
                                                                        Débloquer l'accès
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                                </div>
                                                                <form action="{{ route('admin.medecins.debloquer', $medecin->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="d-flex align-items-center mb-4">
                                                                            <div class="flex-shrink-0 me-3">
                                                                                <div class="avatar-lg">
                                                                                    <img src="{{ $medecin->profile_photo_url }}" alt="{{ $medecin->name }}" class="img-thumbnail rounded-circle">
                                                                                </div>
                                                                            </div>
                                                                            <div class="flex-grow-1">
                                                                                <h6 class="mb-1">{{ $medecin->name }}</h6>
                                                                                <p class="text-muted mb-0">
                                                                                    <i class="fas fa-envelope me-1"></i> {{ $medecin->email }}
                                                                                </p>
                                                                                <p class="text-muted mb-0">
                                                                                    <i class="fas fa-stethoscope me-1"></i> {{ $medecin->specialite ?? 'Non spécifiée' }}
                                                                                </p>
                                                                                @if($medecin->blocked_at)
                                                                                    <p class="text-danger small mt-2">
                                                                                        <i class="fas fa-calendar-times me-1"></i> Bloqué le {{ \Carbon\Carbon::parse($medecin->blocked_at)->format('d/m/Y à H:i') }}
                                                                                        @if($medecin->block_reason)
                                                                                            <br><i class="fas fa-comment me-1"></i> Raison : {{ $medecin->block_reason }}
                                                                                        @endif
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="alert alert-info">
                                                                            <div class="d-flex align-items-center">
                                                                                <i class="fas fa-info-circle me-2"></i>
                                                                                <div>
                                                                                    <strong>Information :</strong> Le médecin retrouvera immédiatement l'accès à son compte.
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                            <i class="fas fa-times me-1"></i> Annuler
                                                                        </button>
                                                                        <button type="submit" class="btn btn-success">
                                                                            <i class="fas fa-check me-1"></i> Confirmer le déblocage
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <button type="button" class="btn btn-warning btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#blockDoctorModal{{ $medecin->id }}"
                                                            title="Bloquer le médecin">
                                                        <i class="fas fa-lock"></i>
                                                    </button>

                                                    <!-- Modal de confirmation de blocage -->
                                                    <div class="modal fade" id="blockDoctorModal{{ $medecin->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">
                                                                <div class="modal-header bg-warning text-dark">
                                                                    <h5 class="modal-title fw-bold">
                                                                        <i class="fas fa-user-lock me-2"></i>
                                                                        Bloquer l'accès
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                                </div>
                                                                <form action="{{ route('admin.medecins.bloquer', $medecin->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="d-flex align-items-center mb-4">
                                                                            <div class="flex-shrink-0 me-3">
                                                                                <i class="fas fa-user-md text-warning fa-3x"></i>
                                                                            </div>
                                                                            <div>
                                                                                <h5 class="fw-bold mb-1">Bloquer le Dr. {{ $medecin->name }} ?</h5>
                                                                                <p class="text-muted mb-0">
                                                                                    Ce médecin ne pourra plus se connecter à son compte jusqu'à ce que vous le débloquiez.
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="blockReason{{ $medecin->id }}" class="form-label fw-semibold">
                                                                                <i class="fas fa-comment-alt me-2 text-warning"></i>Raison du blocage (optionnel)
                                                                            </label>
                                                                            <textarea class="form-control" 
                                                                                    id="blockReason{{ $medecin->id }}" 
                                                                                    name="raison" 
                                                                                    rows="2" 
                                                                                    placeholder="Précisez la raison du blocage..."></textarea>
                                                                        </div>
                                                                        
                                                                        <div class="alert alert-warning" role="alert">
                                                                            <i class="fas fa-exclamation-circle me-2"></i>
                                                                            <strong>Note :</strong> Vous pourrez débloquer ce médecin à tout moment.
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                                            <i class="fas fa-times me-2"></i>Annuler
                                                                        </button>
                                                                        <button type="submit" class="btn btn-warning text-white">
                                                                            <i class="fas fa-lock me-2"></i>Confirmer le blocage
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </form>
                                                @endif
                                                
                                                <!-- Bouton supprimer -->
                                                <form action="{{ route('admin.medecins.destroy', $medecin->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteDoctorModal{{ $medecin->id }}"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>

                                                    <!-- Modal de confirmation de suppression -->
                                                    <div class="modal fade" id="deleteDoctorModal{{ $medecin->id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title fw-bold">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                                        Confirmer la suppression
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="d-flex align-items-center mb-4">
                                                                        <div class="flex-shrink-0 me-3">
                                                                            <i class="fas fa-user-md text-danger fa-3x"></i>
                                                                        </div>
                                                                        <div>
                                                                            <h5 class="fw-bold mb-1">Supprimer le Dr. {{ $medecin->name }} ?</h5>
                                                                            <p class="text-muted mb-0">
                                                                                Cette action est irréversible. Toutes les données associées à ce médecin seront définitivement supprimées.
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="alert alert-warning" role="alert">
                                                                        <i class="fas fa-exclamation-circle me-2"></i>
                                                                        <strong>Attention :</strong> Cette action ne peut pas être annulée.
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                                        <i class="fas fa-times me-2"></i>Annuler
                                                                    </button>
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="fas fa-trash-alt me-2"></i>Supprimer définitivement
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Modal d'alerte moderne pour chaque médecin -->
                                    <div class="modal fade" id="alerteModal{{ $medecin->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <!-- En-tête avec dégradé de couleur -->
                                                <div class="modal-header bg-gradient-primary text-white rounded-top">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-bell me-2"></i>
                                                        <h5 class="modal-title mb-0 fw-bold">Nouvelle alerte pour {{ $medecin->name }}</h5>
                                                    </div>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                
                                                <form action="{{ route('admin.medecins.alerte', $medecin->id) }}" method="POST" class="needs-validation" novalidate>
                                                    @csrf
                                                    <div class="modal-body p-4">
                                                        <!-- Sélecteur de type d'alerte avec icônes -->
                                                        <div class="mb-4">
                                                            <label for="type{{ $medecin->id }}" class="form-label fw-semibold">
                                                                <i class="fas fa-tag me-2 text-primary"></i>Type d'alerte
                                                            </label>
                                                            <select class="form-select form-select-lg" id="type{{ $medecin->id }}" name="type" required>
                                                                <option value="info"><i class="fas fa-info-circle text-primary me-2"></i> Information</option>
                                                                <option value="warning"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Avertissement</option>
                                                                <option value="danger"><i class="fas fa-exclamation-circle text-danger me-2"></i> Urgent</option>
                                                            </select>
                                                            <div class="form-text">Sélectionnez le niveau de priorité</div>
                                                        </div>
                                                        
                                                        <!-- Zone de message avec compteur de caractères -->
                                                        <div class="mb-3">
                                                            <label for="message{{ $medecin->id }}" class="form-label fw-semibold">
                                                                <i class="fas fa-comment-alt me-2 text-primary"></i>Message
                                                            </label>
                                                            <div class="input-group">
                                                                <textarea class="form-control form-control-lg" 
                                                                          id="message{{ $medecin->id }}" 
                                                                          name="message" 
                                                                          rows="4" 
                                                                          maxlength="500"
                                                                          placeholder="Décrivez la raison de cette alerte..." 
                                                                          required
                                                                          style="resize: none;"></textarea>
                                                            </div>
                                                            <div class="d-flex justify-content-between mt-1">
                                                                <small class="text-muted">Maximum 500 caractères</small>
                                                                <small class="text-muted"><span id="charCount{{ $medecin->id }}">0</span>/500</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Pied de page avec boutons d'action -->
                                                    <div class="modal-footer bg-light rounded-bottom p-3">
                                                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i>Annuler
                                                        </button>
                                                        <button type="submit" class="btn btn-warning px-4 fw-bold text-white">
                                                            <i class="fas fa-paper-plane me-2"></i>Envoyer l'alerte
                                                        </button>
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
/* Styles pour les avatars */
.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
}

.avatar-lg {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #fff;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.avatar-sm img, .avatar-lg img {
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

/* Styles pour le modal moderne */
.modal.fade .modal-dialog {
    transform: translateY(-50px);
    transition: transform 0.3s ease-out, opacity 0.2s ease-out;
    opacity: 0;
}

.modal.show .modal-dialog {
    transform: translateY(0);
    opacity: 1;
}

/* Style personnalisé pour les options du select */
select option {
    padding: 8px 12px;
}

/* Animation du bouton d'envoi */
.btn-send-alert {
    position: relative;
    overflow: hidden;
    transition: all 0.3s;
}

.btn-send-alert:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.btn-send-alert:active {
    transform: translateY(0);
}

/* Style pour le compteur de caractères */
.char-counter {
    transition: color 0.3s;
}

.char-counter.warning {
    color: #ffc107;
    font-weight: bold;
}

.char-counter.danger {
    color: #dc3545;
    font-weight: bold;
}
</style>

@push('scripts')
<script>
    // Initialisation des tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Gestion du compteur de caractères pour chaque modal
    document.addEventListener('DOMContentLoaded', function() {
        // Pour chaque modal d'alerte
        document.querySelectorAll('[id^="alerteModal"]').forEach(function(modal) {
            const modalId = modal.id;
            const medecinId = modalId.replace('alerteModal', '');
            const textarea = document.getElementById('message' + medecinId);
            const charCount = document.getElementById('charCount' + medecinId);
            
            if (textarea && charCount) {
                // Mise à jour initiale
                updateCharCount(textarea, charCount);
                
                // Écouter les changements
                textarea.addEventListener('input', function() {
                    updateCharCount(this, charCount);
                });
            }
            
            // Gestion de la soumission du formulaire
            const form = modal.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            }
        });
        
        // Fonction pour mettre à jour le compteur de caractères
        function updateCharCount(textarea, counterElement) {
            const currentLength = textarea.value.length;
            const maxLength = textarea.getAttribute('maxlength');
            
            counterElement.textContent = currentLength;
            counterElement.className = 'char-counter';
            
            // Changer la couleur en fonction du nombre de caractères restants
            const remaining = maxLength - currentLength;
            if (remaining < 50) {
                counterElement.classList.add(remaining < 20 ? 'danger' : 'warning');
            }
        }
        
        // Animation personnalisée pour l'ouverture du modal
        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.addEventListener('show.bs.modal', function () {
                // Réinitialiser le formulaire à l'ouverture
                const form = this.querySelector('form');
                if (form) {
                    form.classList.remove('was-validated');
                    form.reset();
                }
            });
        });
    });
</script>
@endpush

@endsection
