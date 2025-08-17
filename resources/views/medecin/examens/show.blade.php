@extends('layouts.medecin')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- En-tête moderne -->
    <div class="d-flex align-items-center mb-4">
        <div class="bg-light rounded-circle p-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-file-medical text-primary fs-4"></i>
        </div>
        <div>
            <h1 class="mb-1 fw-light text-dark">Détail de l'examen</h1>
            <p class="text-muted mb-0">Consultation des informations détaillées</p>
        </div>
    </div>

    <!-- Carte principale moderne -->
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                <!-- En-tête de la carte -->
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h3 class="mb-1 fw-semibold text-dark">{{ $examen->titre }}</h3>
                            <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $examen->date_examen }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Corps de la carte -->
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Informations patient -->
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-muted fw-normal">Patient</h6>
                                        <p class="mb-0 fw-medium text-dark">{{ $examen->patient->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-4">
                                <h6 class="mb-3 text-muted fw-normal d-flex align-items-center">
                                    <i class="fas fa-file-text me-2"></i>
                                    Description
                                </h6>
                                <div class="text-dark lh-lg" style="white-space: pre-line;">{{ $examen->description }}</div>
                            </div>
                        </div>

                        <!-- Fichier joint -->
                        @if($examen->fichier)
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-4">
                                <h6 class="mb-3 text-muted fw-normal d-flex align-items-center">
                                    <i class="fas fa-paperclip me-2"></i>
                                    Fichier joint
                                </h6>
                                <a href="{{ asset('storage/' . $examen->fichier) }}" target="_blank" 
                                   class="btn btn-outline-primary rounded-pill px-4 py-2 d-inline-flex align-items-center text-decoration-none">
                                    <i class="fas fa-download me-2"></i>
                                    Télécharger / Voir le fichier
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar actions -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-4">
                    <h6 class="mb-3 text-muted fw-normal">Actions disponibles</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('medecin.examens.edit', $examen) }}" 
                           class="btn btn-warning rounded-pill py-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-edit me-2"></i>
                            Modifier l'examen
                        </a>
                        <a href="{{ route('medecin.examens.index') }}" 
                           class="btn btn-outline-secondary rounded-pill py-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles personnalisés pour un look moderne et léger */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
}

.bg-light {
    background-color: #f8f9fa !important;
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

/* Animation douce pour les éléments interactifs */
.badge, .btn, .card {
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
}
</style>
@endsection