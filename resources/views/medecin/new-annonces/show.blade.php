@extends('layouts.medecin')

@section('title', $newAnnonce->titre)

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('medecin.new-annonces.index') }}" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            <a href="{{ route('medecin.new-annonces.edit', $newAnnonce) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                        </div>
                        <div>
                            <span class="badge {{ $newAnnonce->estPubliee() ? 'bg-success' : 'bg-secondary' }} me-2">
                                <i class="fas {{ $newAnnonce->estPubliee() ? 'fa-check-circle' : 'fa-edit' }} me-1"></i>
                                {{ $newAnnonce->estPubliee() ? 'Publiée' : 'Brouillon' }}
                            </span>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="far fa-trash-alt me-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Image de couverture -->
                    @if($newAnnonce->image_path)
                        <div class="mb-4 text-center">
                            <img src="{{ $newAnnonce->image_url }}" alt="Image de couverture" class="img-fluid rounded">
                        </div>
                    @endif

                    <!-- Titre et métadonnées -->
                    <div class="mb-4">
                        <h1 class="h3 mb-2">{{ $newAnnonce->titre }}</h1>
                        <div class="d-flex flex-wrap gap-3 text-muted small mb-3">
                            <div>
                                <i class="far fa-user me-1"></i>
                                Par {{ $newAnnonce->user->name }}
                            </div>
                            <div>
                                <i class="far fa-calendar-plus me-1"></i>
                                Créée le {{ $newAnnonce->created_at->format('d/m/Y à H:i') }}
                            </div>
                            @if($newAnnonce->date_publication)
                                <div>
                                    <i class="far fa-calendar-check me-1"></i>
                                    Publiée le {{ $newAnnonce->date_publication->format('d/m/Y à H:i') }}
                                </div>
                            @endif
                            <div>
                                <i class="far fa-edit me-1"></i>
                                Dernière modification : {{ $newAnnonce->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Contenu -->
                    <div class="content">
                        {!! $newAnnonce->contenu !!}
                    </div>
                </div>

                <!-- Pied de page -->
                <div class="card-footer bg-white border-top py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('medecin.new-annonces.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                        <div class="btn-group">
                            <a href="{{ route('medecin.new-annonces.edit', $newAnnonce) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="far fa-trash-alt me-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer définitivement cette annonce ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('medecin.new-annonces.destroy', $newAnnonce) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="far fa-trash-alt me-1"></i> Supprimer définitivement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content {
        line-height: 1.8;
        color: #333;
    }
    
    .content h2, 
    .content h3, 
    .content h4 {
        margin-top: 1.5em;
        margin-bottom: 0.8em;
        color: #2c3e50;
    }
    
    .content p {
        margin-bottom: 1.2em;
    }
    
    .content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
    }
    
    .content ul, 
    .content ol {
        margin-bottom: 1.2em;
        padding-left: 1.5em;
    }
    
    .content a {
        color: #0d6efd;
        text-decoration: none;
    }
    
    .content a:hover {
        text-decoration: underline;
    }
    
    .content blockquote {
        border-left: 4px solid #ddd;
        padding-left: 1em;
        margin: 1.5em 0;
        color: #666;
        font-style: italic;
    }
    
    .content table {
        width: 100%;
        margin-bottom: 1.5em;
        border-collapse: collapse;
    }
    
    .content table th,
    .content table td {
        padding: 0.75em;
        border: 1px solid #dee2e6;
    }
    
    .content table th {
        background-color: #f8f9fa;
        text-align: left;
    }
</style>
@endpush
