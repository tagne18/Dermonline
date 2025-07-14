@extends('layouts.admin')

@section('title', 'Détails du Patient')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-white text-3xl font-bold">👤 Détails du Patient</h1>
                <a href="{{ route('admin.users.patients') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nom complet</label>
                                <p class="form-control-plaintext">{{ $patient->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext">{{ $patient->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Téléphone</label>
                                <p class="form-control-plaintext">{{ $patient->phone ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Genre</label>
                                <p class="form-control-plaintext">{{ $patient->gender ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de naissance</label>
                                <p class="form-control-plaintext">
                                    @if($patient->birth_date)
                                        {{ \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') }}
                                        ({{ \Carbon\Carbon::parse($patient->birth_date)->age }} ans)
                                    @else
                                        Non renseigné
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Profession</label>
                                <p class="form-control-plaintext">{{ $patient->profession ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ville</label>
                                <p class="form-control-plaintext">{{ $patient->city ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Statut du compte</label>
                                <p class="form-control-plaintext">
                                    @if($patient->is_blocked)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-ban"></i> Bloqué
                                            @if($patient->blocked_at)
                                                depuis {{ \Carbon\Carbon::parse($patient->blocked_at)->format('d/m/Y') }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Actif
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Inscrit le</label>
                                <p class="form-control-plaintext">
                                    {{ $patient->created_at->format('d/m/Y à H:i') }}
                                    <br><small class="text-muted">{{ $patient->created_at->diffForHumans() }}</small>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dernière connexion</label>
                                <p class="form-control-plaintext">
                                    @if($patient->last_login_at)
                                        {{ \Carbon\Carbon::parse($patient->last_login_at)->format('d/m/Y à H:i') }}
                                        <br><small class="text-muted">{{ \Carbon\Carbon::parse($patient->last_login_at)->diffForHumans() }}</small>
                                    @else
                                        Jamais connecté
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Photo de profil -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-camera"></i> Photo de Profil</h5>
                </div>
                <div class="card-body text-center">
                    <img src="{{ $patient->profile_photo_url }}" 
                         alt="Photo de {{ $patient->name }}"
                         class="img-fluid rounded-circle mb-3" 
                         style="width: 200px; height: 200px; object-fit: cover;">
                    
                    <div class="mt-3">
                        @if(!$patient->is_blocked)
                            <form action="{{ route('admin.utilisateurs.bloquer', $patient->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir bloquer ce patient ?')">
                                    <i class="fas fa-ban"></i> Bloquer
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.utilisateurs.debloquer', $patient->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-unlock"></i> Débloquer
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.users.patients.destroy', $patient->id) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ? Cette action est irréversible.')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Abonnement -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-credit-card"></i> Informations d'Abonnement</h5>
                </div>
                <div class="card-body">
                    @if($patient->abonnement)
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Type d'abonnement</label>
                                <p class="form-control-plaintext">{{ $patient->abonnement->type }}</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Statut</label>
                                <p class="form-control-plaintext">
                                    @if($patient->abonnement->statut === 'actif')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Date de début</label>
                                <p class="form-control-plaintext">
                                    {{ $patient->abonnement->date_debut ? \Carbon\Carbon::parse($patient->abonnement->date_debut)->format('d/m/Y') : 'Non définie' }}
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Date de fin</label>
                                <p class="form-control-plaintext">
                                    @if($patient->abonnement->date_fin)
                                        {{ \Carbon\Carbon::parse($patient->abonnement->date_fin)->format('d/m/Y') }}
                                        @if(\Carbon\Carbon::parse($patient->abonnement->date_fin)->isPast())
                                            <br><small class="text-danger">Expiré</small>
                                        @else
                                            <br><small class="text-success">Valide</small>
                                        @endif
                                    @else
                                        Non définie
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Aucun abonnement trouvé pour ce patient.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rendez-vous -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar"></i> Historique des Rendez-vous</h5>
                </div>
                <div class="card-body">
                    @if($patient->appointments->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patient->appointments->sortByDesc('date') as $appointment)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('H:i') }}</td>
                                            <td>
                                                @switch($appointment->statut)
                                                    @case('en_attente')
                                                        <span class="badge bg-warning">En attente</span>
                                                        @break
                                                    @case('confirme')
                                                        <span class="badge bg-info">Confirmé</span>
                                                        @break
                                                    @case('termine')
                                                        <span class="badge bg-success">Terminé</span>
                                                        @break
                                                    @case('annule')
                                                        <span class="badge bg-danger">Annulé</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $appointment->statut }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucun rendez-vous trouvé pour ce patient.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 