@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Détails du document</h2>
        <div>
            <a href="{{ route('patient.documents.download', $document) }}" class="btn btn-primary me-2">
                <i class="fas fa-download me-1"></i> Télécharger
            </a>
            <a href="{{ route('patient.documents.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <h3 class="card-title">{{ $document->titre }}</h3>
                    <p class="text-muted">
                        <i class="fas fa-file me-2"></i> 
                        {{ ucfirst(str_replace('_', ' ', $document->type_document)) }}
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="badge bg-{{ $document->statut === 'actif' ? 'success' : 'secondary' }}">
                        {{ ucfirst($document->statut) }}
                    </span>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Informations du document</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Date d'ajout :</strong> 
                            {{ $document->created_at->format('d/m/Y à H:i') }}
                        </li>
                        <li class="mb-2">
                            <strong>Taille :</strong> 
                            {{ number_format($document->taille_fichier / 1024, 2) }} Ko
                        </li>
                        <li class="mb-2">
                            <strong>Type de fichier :</strong> 
                            {{ $document->mime_type }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Métadonnées</h5>
                    <ul class="list-unstyled">
                        @if($document->medecin)
                        <li class="mb-2">
                            <strong>Médecin :</strong> 
                            Dr. {{ $document->medecin->first_name ?? '' }} {{ $document->medecin->last_name ?? $document->medecin->name }}
                        </li>
                        @endif
                        @if($document->consultation)
                        <li class="mb-2">
                            <strong>Consultation du :</strong> 
                            {{ $document->consultation->date_consultation->format('d/m/Y') }}
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            @if($document->description)
            <div class="mb-4">
                <h5>Description</h5>
                <div class="p-3 bg-light rounded">
                    {!! nl2br(e($document->description)) !!}
                </div>
            </div>
            @endif

            <div class="mt-4">
                <h5>Aperçu du document</h5>
                <div class="border rounded p-3 text-center">
                    @if(str_starts_with($document->mime_type, 'image/'))
                        <img src="{{ Storage::url($document->fichier) }}" 
                             alt="Aperçu de {{ $document->nom_fichier }}" 
                             class="img-fluid"
                             style="max-height: 500px;">
                    @elseif($document->mime_type === 'application/pdf')
                        <div class="p-4 bg-light">
                            <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                            <p class="mb-0">Fichier PDF - {{ $document->nom_fichier }}</p>
                        </div>
                    @else
                        <div class="p-4 bg-light">
                            <i class="fas fa-file fa-5x text-secondary mb-3"></i>
                            <p class="mb-0">Aperçu non disponible pour ce type de fichier</p>
                            <small class="text-muted">{{ $document->nom_fichier }}</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <form action="{{ route('patient.documents.destroy', $document) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ? Cette action est irréversible.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="fas fa-trash-alt me-1"></i> Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .card-title {
        color: #2c3e50;
        font-weight: 600;
    }
    .badge {
        font-size: 0.85rem;
        padding: 0.4em 0.8em;
    }
</style>

@endsection
