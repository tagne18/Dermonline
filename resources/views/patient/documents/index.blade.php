@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Mes documents médicaux</h1>
        <a href="{{ route('patient.documents.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Ajouter un document
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($documents->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>Titre</th>
                                <th>Date d'ajout</th>
                                <th>Taille</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ getDocumentTypeColor($document->type_document) }}">
                                            {{ ucfirst(str_replace('_', ' ', $document->type_document)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('patient.documents.show', $document) }}" class="text-decoration-none">
                                            {{ $document->titre }}
                                        </a>
                                        @if($document->description)
                                            <p class="small text-muted mb-0">{{ Str::limit($document->description, 50) }}</p>
                                        @endif
                                    </td>
                                    <td>{{ $document->created_at->format('d/m/Y') }}</td>
                                    <td>{{ number_format($document->taille_fichier / 1024, 1) }} Ko</td>
                                    <td>
                                        <span class="badge bg-{{ $document->statut === 'actif' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($document->statut) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('patient.documents.show', $document) }}" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('patient.documents.download', $document) }}" 
                                               class="btn btn-sm btn-outline-success"
                                               title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <form action="{{ route('patient.documents.destroy', $document) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Affichage de {{ $documents->firstItem() }} à {{ $documents->lastItem() }} sur {{ $documents->total() }} documents
                    </div>
                    <nav>
                        {{ $documents->links() }}
                    </nav>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-folder-open fa-4x text-muted"></i>
                    </div>
                    <h4>Aucun document trouvé</h4>
                    <p class="text-muted">Vous n'avez pas encore de documents dans votre espace personnel.</p>
                    <a href="{{ route('patient.documents.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle me-1"></i> Ajouter votre premier document
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@php
function getDocumentTypeColor($type) {
    switch($type) {
        case 'ordonnance':
            return 'info';
        case 'analyse':
            return 'primary';
        case 'compte_rendu':
            return 'warning';
        case 'certificat':
            return 'success';
        default:
            return 'secondary';
    }
}
@endphp

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
    }
    .btn-group .btn {
        border-radius: 0.25rem !important;
        margin-right: 0.25rem;
    }
    .btn-group .btn:last-child {
        margin-right: 0;
    }
</style>
@endsection
