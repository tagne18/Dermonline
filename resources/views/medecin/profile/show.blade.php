@extends('layouts.medecin')

@section('title', 'Profil du Médecin')

@section('content')
<div class="container py-5">
    <!-- Header avec gradient -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient-primary text-white rounded-4 p-4 shadow-lg">
                <div class="d-flex align-items-center">
                    <div class="me-4">
                        <i class="fas fa-user-md fa-3x opacity-75"></i>
                    </div>
                    <div>
                        <h1 class="mb-1 fw-bold">Profil Médecin</h1>
                        <p class="mb-0 opacity-90">Gérez vos informations professionnelles</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte principale du profil -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Header de la carte avec fond coloré -->
                <div class="card-header bg-light border-0 p-0">
                    <div class="bg-gradient-primary" style="height: 120px; position: relative;">
                        <div class="position-absolute" style="bottom: -60px; left: 50%; transform: translateX(-50%);">
                            <div class="position-relative">
                                <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default.jpeg') }}" 
                                     alt="Photo de profil" 
                                     class="rounded-circle border border-5 border-white shadow-lg"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                                <div class="position-absolute bottom-0 end-0">
                                    <span class="badge bg-success rounded-pill px-3 py-2 shadow-sm">
                                        <i class="fas fa-check-circle me-1"></i>Vérifié
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Corps de la carte -->
                <div class="card-body pt-5 pb-4">
                    <!-- Nom et spécialité centrés -->
                    <div class="text-center mb-4 mt-3">
                        <h2 class="fw-bold text-primary mb-2">{{ Auth::user()->name }}</h2>
                        <div class="mb-3">
                            <span class="badge bg-primary-subtle text-primary px-4 py-2 rounded-pill fs-6">
                                <i class="fas fa-stethoscope me-2"></i>{{ Auth::user()->specialite }}
                            </span>
                        </div>
                    </div>

                    <!-- Informations organisées en cartes -->
                    <div class="row g-4">
                        <!-- Informations personnelles -->
                        <div class="col-lg-6">
                            <div class="card border-0 bg-light rounded-3 h-100">
                                <div class="card-body p-4">
                                    <h5 class="text-primary mb-3 fw-bold">
                                        <i class="fas fa-user me-2"></i>Informations Personnelles
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-calendar-alt text-muted me-3 fa-fw"></i>
                                                <div>
                                                    <small class="text-muted d-block">Âge</small>
                                                    <span class="fw-medium">{{ Auth::user()->birth_date ? \Carbon\Carbon::parse(Auth::user()->birth_date)->age . ' ans' : 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-venus-mars text-muted me-3 fa-fw"></i>
                                                <div>
                                                    <small class="text-muted d-block">Sexe</small>
                                                    <span class="fw-medium">{{ Auth::user()->gender ? ucfirst(Auth::user()->gender) : 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-envelope text-muted me-3 fa-fw"></i>
                                                <div>
                                                    <small class="text-muted d-block">Email</small>
                                                    <span class="fw-medium">{{ Auth::user()->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-phone text-muted me-3 fa-fw"></i>
                                                <div>
                                                    <small class="text-muted d-block">Téléphone</small>
                                                    <span class="fw-medium">{{ Auth::user()->phone ?: 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations professionnelles -->
                        <div class="col-lg-6">
                            <div class="card border-0 bg-light rounded-3 h-100">
                                <div class="card-body p-4">
                                    <h5 class="text-success mb-3 fw-bold">
                                        <i class="fas fa-hospital me-2"></i>Informations Professionnelles
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-map-marker-alt text-muted me-3 fa-fw"></i>
                                                <div>
                                                    <small class="text-muted d-block">Ville</small>
                                                    <span class="fw-medium">{{ Auth::user()->ville ?: 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-language text-muted me-3 fa-fw"></i>
                                                <div>
                                                    <small class="text-muted d-block">Langue</small>
                                                    <span class="fw-medium">{{ Auth::user()->langue ?: 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-building text-muted me-3 fa-fw"></i>
                                                <div>
                                                    <small class="text-muted d-block">Lieu de travail</small>
                                                    <span class="fw-medium">{{ Auth::user()->lieu_travail ?: 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-clock text-muted me-3 fa-fw"></i>
                                                <div>
                                                    <small class="text-muted d-block">Expérience</small>
                                                    <span class="fw-medium">{{ Auth::user()->experience_professionnelle ?: 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de certification -->
                        <div class="col-lg-6">
                            <div class="card border-0 bg-warning-subtle rounded-3 h-100">
                                <div class="card-body p-4">
                                    <h5 class="text-warning-emphasis mb-3 fw-bold">
                                        <i class="fas fa-certificate me-2"></i>Certifications
                                    </h5>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-id-card text-muted me-3 fa-fw"></i>
                                                <div>
                                                    <small class="text-muted d-block">Numéro de licence</small>
                                                    <span class="fw-medium font-monospace">{{ Auth::user()->numero_licence ?: 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-fingerprint text-muted me-3 fa-fw"></i>
                                                <div>
                                                    <small class="text-muted d-block">Matricule professionnel</small>
                                                    <span class="fw-medium font-monospace">{{ Auth::user()->matricule_professionnel ?: 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Domaine d'expertise -->
                        <div class="col-lg-6">
                            <div class="card border-0 bg-info-subtle rounded-3 h-100">
                                <div class="card-body p-4">
                                    <h5 class="text-info-emphasis mb-3 fw-bold">
                                        <i class="fas fa-brain me-2"></i>Expertise
                                    </h5>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-star text-muted me-3 fa-fw"></i>
                                        <div>
                                            <small class="text-muted d-block">Domaine d'expertise</small>
                                            <span class="fw-medium">{{ Auth::user()->domaine_expertise ?: 'Non renseigné' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- À propos -->
                        @if(Auth::user()->a_propos)
                        <div class="col-12">
                            <div class="card border-0 bg-secondary-subtle rounded-3">
                                <div class="card-body p-4">
                                    <h5 class="text-secondary-emphasis mb-3 fw-bold">
                                        <i class="fas fa-quote-left me-2"></i>À propos
                                    </h5>
                                    <p class="mb-0 text-muted lh-lg">{{ Auth::user()->a_propos }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Bouton d'action -->
                    <div class="text-center mt-5">
                        <a href="{{ route('medecin.profile.edit') }}" 
                           class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-sm">
                            <i class="fas fa-edit me-2"></i>Modifier mon profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.bg-light {
    background-color: #f8f9fa !important;
}

.bg-primary-subtle {
    background-color: rgba(13, 110, 253, 0.1) !important;
}

.bg-warning-subtle {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.bg-secondary-subtle {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.rounded-4 {
    border-radius: 1rem !important;
}

.rounded-pill {
    border-radius: 50rem !important;
}

.fa-fw {
    width: 1.25em;
    text-align: center;
}

.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

.btn-lg {
    font-size: 1.1rem;
    font-weight: 600;
}

.lh-lg {
    line-height: 1.8 !important;
}
</style>
@endsection