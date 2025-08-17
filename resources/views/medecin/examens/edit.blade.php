@extends('layouts.medecin')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- En-tête moderne -->
    <div class="d-flex align-items-center mb-4">
        <div class="bg-light rounded-circle p-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-edit text-warning fs-4"></i>
        </div>
        <div>
            <h1 class="mb-1 fw-light text-dark">Modifier l'examen</h1>
            <p class="text-muted mb-0">Modification des informations de l'examen</p>
        </div>
    </div>

    <!-- Messages d'erreur modernes -->
    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 mb-4" style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);">
            <div class="d-flex align-items-start">
                <div class="bg-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-2 text-danger fw-semibold">Erreurs de validation</h6>
                    <ul class="mb-0 list-unstyled">
                        @foreach($errors->all() as $error)
                            <li class="text-danger mb-1">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Formulaire moderne -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-0 fw-semibold text-dark d-flex align-items-center">
                        <i class="fas fa-form me-2 text-muted"></i>
                        Informations de l'examen
                    </h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('medecin.examens.update', $examen) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <!-- Patient -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <select name="patient_id" id="patient_id" class="form-select rounded-3" required style="padding-top: 1.625rem; padding-bottom: 0.625rem;">
                                        <option value="">Sélectionner un patient</option>
                                        @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}" @if($examen->patient_id == $patient->id) selected @endif>{{ $patient->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="patient_id" class="text-muted">
                                        <i class="fas fa-user me-2"></i>Patient
                                    </label>
                                </div>
                            </div>

                            <!-- Titre -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="titre" id="titre" class="form-control rounded-3" 
                                           value="{{ $examen->titre }}" required placeholder="Titre de l'examen">
                                    <label for="titre" class="text-muted">
                                        <i class="fas fa-heading me-2"></i>Titre de l'examen
                                    </label>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea name="description" id="description" class="form-control rounded-3" 
                                              style="height: 120px; resize: vertical;" placeholder="Description détaillée">{{ $examen->description }}</textarea>
                                    <label for="description" class="text-muted">
                                        <i class="fas fa-file-text me-2"></i>Description
                                    </label>
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <input type="date" name="date_examen" id="date_examen" class="form-control rounded-3" 
                                           value="{{ $examen->date_examen }}" required>
                                    <label for="date_examen" class="text-muted">
                                        <i class="fas fa-calendar-alt me-2"></i>Date de l'examen
                                    </label>
                                </div>
                            </div>

                            <!-- Fichier -->
                            <div class="col-12">
                                <div class="p-3 bg-light rounded-4">
                                    <h6 class="mb-3 text-muted fw-normal d-flex align-items-center">
                                        <i class="fas fa-paperclip me-2"></i>
                                        Fichier joint (PDF/image)
                                    </h6>
                                    
                                    @if($examen->fichier)
                                        <div class="mb-3 p-3 bg-white rounded-3 d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-file text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-1 fw-medium">Fichier actuel</p>
                                                <a href="{{ asset('storage/' . $examen->fichier) }}" target="_blank" 
                                                   class="btn btn-sm btn-outline-primary rounded-pill">
                                                    <i class="fas fa-eye me-1"></i>Voir le fichier
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <div class="form-floating">
                                        <input type="file" name="fichier" id="fichier" class="form-control rounded-3" 
                                               accept=".pdf,.jpg,.jpeg,.png,.gif" style="padding-top: 1rem;">
                                        <label for="fichier" class="text-muted">
                                            <i class="fas fa-upload me-2"></i>Nouveau fichier (optionnel)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="mt-4 d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-success rounded-pill px-4 py-2 d-flex align-items-center">
                                <i class="fas fa-save me-2"></i>
                                Enregistrer les modifications
                            </button>
                            <a href="{{ route('medecin.examens.index') }}" 
                               class="btn btn-outline-secondary rounded-pill px-4 py-2 d-flex align-items-center">
                                <i class="fas fa-times me-2"></i>
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar d'aide -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="mb-3 text-muted fw-normal d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        Conseils de modification
                    </h6>
                    <div class="text-sm text-muted">
                        <div class="mb-3 p-2 bg-light rounded-3">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            <strong>Titre :</strong> Soyez descriptif et précis
                        </div>
                        <div class="mb-3 p-2 bg-light rounded-3">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            <strong>Description :</strong> Ajoutez tous les détails importants
                        </div>
                        <div class="mb-3 p-2 bg-light rounded-3">
                            <i class="fas fa-lightbulb text-warning me-2"></i>
                            <strong>Fichier :</strong> Formats acceptés: PDF, JPG, PNG, GIF
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles personnalisés pour un look moderne */
.form-floating > .form-control,
.form-floating > .form-select {
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
}

.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    transform: translateY(-1px);
}

.form-floating > label {
    font-size: 0.875rem;
    font-weight: 500;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.3s ease;
    font-weight: 500;
    border-width: 1.5px;
}

.btn:hover {
    transform: translateY(-1px);
}

.alert {
    border: none !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.rounded-3 {
    border-radius: 8px !important;
}

.rounded-4 {
    border-radius: 12px !important;
}

.rounded-pill {
    border-radius: 50px !important;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.text-sm {
    font-size: 0.875rem;
}

/* Animation pour les champs de formulaire */
.form-floating > .form-control,
.form-floating > .form-select,
.btn {
    transition: all 0.2s ease-in-out;
}

/* Style pour les icônes */
.fas {
    font-size: 14px;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .card-body, .card-header {
        padding: 1.5rem !important;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection