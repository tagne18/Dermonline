@extends('layouts.medecin')

@section('title', 'Mes annonces')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-bullhorn me-2"></i>Mes annonces
                        </h5>
                        <a href="{{ route('medecin.new-annonces.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Nouvelle annonce
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filtres -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ request()->fullUrlWithQuery(['statut' => 'tous']) }}" 
                                   class="btn {{ request('statut') === 'tous' || !request('statut') ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Toutes
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['statut' => 'publie']) }}" 
                                   class="btn {{ request('statut') === 'publie' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Publiées
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['statut' => 'brouillon']) }}" 
                                   class="btn {{ request('statut') === 'brouillon' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    Brouillons
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 ms-auto">
                            <form action="{{ route('medecin.new-annonces.index') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="recherche" class="form-control" 
                                           placeholder="Rechercher une annonce..." value="{{ request('recherche') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Liste des annonces -->
                    @if($annonces->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Titre</th>
                                        <th>Statut</th>
                                        <th>Date de création</th>
                                        <th>Publication</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($annonces as $annonce)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($annonce->image_path)
                                                        <img src="{{ $annonce->image_url }}" alt="Image de l'annonce" 
                                                             class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $annonce->titre }}</h6>
                                                        <small class="text-muted">
                                                            {{ Str::limit(strip_tags($annonce->contenu), 50) }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($annonce->estPubliee())
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i> Publiée
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-edit me-1"></i> Brouillon
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $annonce->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($annonce->date_publication)
                                                    {{ $annonce->date_publication->format('d/m/Y H:i') }}
                                                @else
                                                    <span class="text-muted">Non publiée</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('medecin.new-annonces.edit', $annonce) }}" 
                                                       class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $annonce->id }}"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>

                                                <!-- Modal de confirmation de suppression -->
                                                <div class="modal fade" id="deleteModal{{ $annonce->id }}" tabindex="-1" 
                                                     aria-labelledby="deleteModalLabel{{ $annonce->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel{{ $annonce->id }}">
                                                                    Confirmer la suppression
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                                                        aria-label="Fermer"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer l'annonce "{{ $annonce->titre }}" ?
                                                                Cette action est irréversible.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" 
                                                                        data-bs-dismiss="modal">Annuler</button>
                                                                <form action="{{ route('medecin.new-annonces.destroy', $annonce) }}" 
                                                                      method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="fas fa-trash-alt me-1"></i> Supprimer
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $annonces->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-bullhorn fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Aucune annonce trouvée</h5>
                            <p class="text-muted">
                                @if(request('recherche') || request('statut'))
                                    Aucune annonce ne correspond à vos critères de recherche.
                                @else
                                    Vous n'avez pas encore créé d'annonce.
                                @endif
                            </p>
                            <a href="{{ route('medecin.new-annonces.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i> Créer une annonce
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialisation des tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
