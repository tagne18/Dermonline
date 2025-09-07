@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Ajouter un document</h2>
        <a href="{{ route('patient.documents.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('patient.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre du document <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                           id="titre" name="titre" value="{{ old('titre') }}" required>
                    @error('titre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type_document" class="form-label">Type de document <span class="text-danger">*</span></label>
                        <select class="form-select @error('type_document') is-invalid @enderror" 
                                id="type_document" name="type_document" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="ordonnance" {{ old('type_document') == 'ordonnance' ? 'selected' : '' }}>Ordonnance</option>
                            <option value="analyse" {{ old('type_document') == 'analyse' ? 'selected' : '' }}>Analyse médicale</option>
                            <option value="compte_rendu" {{ old('type_document') == 'compte_rendu' ? 'selected' : '' }}>Compte-rendu</option>
                            <option value="certificat" {{ old('type_document') == 'certificat' ? 'selected' : '' }}>Certificat médical</option>
                            <option value="autre" {{ old('type_document') == 'autre' ? 'selected' : '' }}>Autre document</option>
                        </select>
                        @error('type_document')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="consultation_id" class="form-label">Associer à une consultation (optionnel)</label>
                        <select class="form-select @error('consultation_id') is-invalid @enderror" 
                                id="consultation_id" name="consultation_id">
                            <option value="">Non associé à une consultation</option>
                            @foreach($consultations as $id => $date)
                                <option value="{{ $id }}" {{ old('consultation_id') == $id ? 'selected' : '' }}>
                                    Consultation du {{ $date }}
                                </option>
                            @endforeach
                        </select>
                        @error('consultation_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="fichier" class="form-label">Fichier <span class="text-danger">*</span> <small class="text-muted">(max: 10MB)</small></label>
                    <div class="border rounded p-3 @error('fichier') border-danger @enderror">
                        <input class="form-control @error('fichier') is-invalid @enderror" 
                               type="file" id="fichier" name="fichier" required>
                        @error('fichier')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Formats acceptés : PDF, JPG, PNG, DOC, DOCX. Taille maximale : 10MB
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="reset" class="btn btn-outline-secondary me-md-2">
                        <i class="fas fa-undo me-1"></i> Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer le document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    .form-label {
        font-weight: 500;
    }
    .required:after {
        content: " *";
        color: #dc3545;
    }
</style>

@endsection
