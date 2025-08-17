@extends('layouts.medecin')

@section('title', 'Mon Profil')

@section('content')
<div class="container py-5">
    <!-- Header avec gradient -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient-primary text-white rounded-4 p-4 shadow-lg">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="me-4">
                            <i class="fas fa-user-edit fa-3x opacity-75"></i>
                        </div>
                        <div>
                            <h1 class="mb-1 fw-bold">Modifier mon Profil</h1>
                            <p class="mb-0 opacity-90">Mettez à jour vos informations professionnelles</p>
                        </div>
                    </div>
                    <a href="{{ route('medecin.profile.show') }}" class="btn btn-light btn-sm rounded-pill px-3">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages d'alerte -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulaire principal -->
    <form method="POST" action="{{ route('medecin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Section Photo de profil -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="text-primary mb-4 fw-bold">
                            <i class="fas fa-camera me-2"></i>Photo de Profil
                        </h5>
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="position-relative">
                                    <img src="{{ Auth::user()->profile_photo_url ?? asset('images/default.jpeg') }}" 
                                         alt="Photo de profil" 
                                         class="rounded-circle border border-3 border-primary shadow-sm"
                                         style="width: 120px; height: 120px; object-fit: cover;"
                                         id="profilePreview">
                                    <div class="position-absolute bottom-0 end-0">
                                        <span class="badge bg-primary rounded-circle p-2">
                                            <i class="fas fa-camera fa-sm"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <label for="profile_photo" class="form-label fw-medium">Changer la photo</label>
                                <input type="file" class="form-control rounded-3" id="profile_photo" name="profile_photo" accept="image/*">
                                <small class="text-muted">Formats acceptés: JPG, PNG, GIF (max 2MB)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Informations personnelles -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="text-primary mb-4 fw-bold">
                            <i class="fas fa-user me-2"></i>Informations Personnelles
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-id-badge text-muted me-2"></i>Nom
                                </label>
                                <input type="text" class="form-control rounded-3 bg-light" value="{{ Auth::user()->name }}" disabled>
                                <small class="text-muted">Ce champ ne peut pas être modifié</small>
                            </div>
                            <div class="col-md-4">
                                <label for="gender" class="form-label fw-medium">
                                    <i class="fas fa-venus-mars text-muted me-2"></i>Sexe
                                </label>
                                <select class="form-select rounded-3" id="gender" name="gender" required>
                                    <option value="" disabled {{ old('gender', Auth::user()->gender) == '' ? 'selected' : '' }}>Sélectionner</option>
                                    <option value="homme" {{ old('gender', Auth::user()->gender) == 'homme' ? 'selected' : '' }}>Homme</option>
                                    <option value="femme" {{ old('gender', Auth::user()->gender) == 'femme' ? 'selected' : '' }}>Femme</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="birth_date" class="form-label fw-medium">
                                    <i class="fas fa-calendar-alt text-muted me-2"></i>Date de naissance
                                </label>
                                <input type="date" class="form-control rounded-3" id="birth_date" name="birth_date" 
                                       value="{{ old('birth_date', Auth::user()->birth_date ? \Carbon\Carbon::parse(Auth::user()->birth_date)->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Contact -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="text-success mb-4 fw-bold">
                            <i class="fas fa-address-book me-2"></i>Informations de Contact
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-medium">
                                    <i class="fas fa-envelope text-muted me-2"></i>Email
                                </label>
                                <input type="email" class="form-control rounded-3" id="email" name="email" 
                                       value="{{ old('email', Auth::user()->email) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-medium">
                                    <i class="fas fa-phone text-muted me-2"></i>Téléphone
                                </label>
                                <input type="text" class="form-control rounded-3" id="phone" name="phone" 
                                       value="{{ old('phone', Auth::user()->phone) }}" placeholder="+237 6XX XXX XXX">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Informations professionnelles -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="text-info mb-4 fw-bold">
                            <i class="fas fa-hospital me-2"></i>Informations Professionnelles
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="specialite" class="form-label fw-medium">
                                    <i class="fas fa-stethoscope text-muted me-2"></i>Spécialité
                                </label>
                                <input type="text" class="form-control rounded-3" id="specialite" name="specialite" 
                                       value="{{ old('specialite', Auth::user()->specialite) }}" placeholder="Ex: Cardiologie">
                            </div>
                            <div class="col-md-6">
                                <label for="ville" class="form-label fw-medium">
                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>Ville
                                </label>
                                <input type="text" class="form-control rounded-3" id="ville" name="ville" 
                                       value="{{ old('ville', Auth::user()->ville) }}" placeholder="Ex: Yaoundé">
                            </div>
                            <div class="col-md-6">
                                <label for="langue" class="form-label fw-medium">
                                    <i class="fas fa-language text-muted me-2"></i>Langue
                                </label>
                                <input type="text" class="form-control rounded-3" id="langue" name="langue" 
                                       value="{{ old('langue', Auth::user()->langue) }}" placeholder="Ex: Français, Anglais">
                            </div>
                            <div class="col-md-6">
                                <label for="lieu_travail" class="form-label fw-medium">
                                    <i class="fas fa-building text-muted me-2"></i>Lieu de travail
                                </label>
                                <input type="text" class="form-control rounded-3" id="lieu_travail" name="lieu_travail" 
                                       value="{{ old('lieu_travail', Auth::user()->lieu_travail) }}" placeholder="Ex: Hôpital Central">
                            </div>
                            <div class="col-md-6">
                                <label for="experience_professionnelle" class="form-label fw-medium">
                                    <i class="fas fa-clock text-muted me-2"></i>Expérience professionnelle
                                </label>
                                <input type="text" class="form-control rounded-3" id="experience_professionnelle" name="experience_professionnelle" 
                                       value="{{ old('experience_professionnelle', Auth::user()->experience_professionnelle) }}" placeholder="Ex: 5 ans">
                            </div>
                            <div class="col-md-6">
                                <label for="domaine_expertise" class="form-label fw-medium">
                                    <i class="fas fa-star text-muted me-2"></i>Domaine d'expertise
                                </label>
                                <input type="text" class="form-control rounded-3" id="domaine_expertise" name="domaine_expertise" 
                                       value="{{ old('domaine_expertise', Auth::user()->domaine_expertise) }}" placeholder="Ex: Chirurgie cardiaque">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Certifications -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="text-warning mb-4 fw-bold">
                            <i class="fas fa-certificate me-2"></i>Certifications
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="numero_licence" class="form-label fw-medium">
                                    <i class="fas fa-id-card text-muted me-2"></i>Numéro de licence
                                </label>
                                <input type="text" class="form-control rounded-3 font-monospace" id="numero_licence" name="numero_licence" 
                                       value="{{ old('numero_licence', Auth::user()->numero_licence) }}" placeholder="LIC-XXXX-XXXX">
                            </div>
                            <div class="col-md-6">
                                <label for="matricule_professionnel" class="form-label fw-medium">
                                    <i class="fas fa-fingerprint text-muted me-2"></i>Matricule professionnel
                                </label>
                                <input type="text" class="form-control rounded-3 font-monospace" id="matricule_professionnel" name="matricule_professionnel" 
                                       value="{{ old('matricule_professionnel', Auth::user()->matricule_professionnel) }}" placeholder="MAT-XXXX-XXXX">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section À propos -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="text-secondary mb-4 fw-bold">
                            <i class="fas fa-quote-left me-2"></i>À propos de moi
                        </h5>
                        <div class="mb-3">
                            <label for="a_propos" class="form-label fw-medium">Description personnelle</label>
                            <textarea class="form-control rounded-3" id="a_propos" name="a_propos" rows="4" 
                                      placeholder="Décrivez brièvement votre parcours, vos valeurs et votre approche médicale...">{{ old('a_propos', Auth::user()->a_propos) }}</textarea>
                            <small class="text-muted">Cette description apparaîtra sur votre profil public</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Changement de mot de passe -->
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 border-danger-subtle">
                    <div class="card-body p-4">
                        <h5 class="text-danger mb-4 fw-bold">
                            <i class="fas fa-lock me-2"></i>Changer le mot de passe
                        </h5>
                        <div class="alert alert-info border-0 rounded-3 mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Information :</strong> Laissez ces champs vides si vous ne souhaitez pas changer votre mot de passe.
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="old_password" class="form-label fw-medium">
                                    <i class="fas fa-key text-muted me-2"></i>Ancien mot de passe
                                </label>
                                <input type="password" class="form-control rounded-3" id="old_password" name="old_password" 
                                       placeholder="Saisir l'ancien mot de passe">
                            </div>
                            <div class="col-md-4">
                                <label for="password" class="form-label fw-medium">
                                    <i class="fas fa-lock text-muted me-2"></i>Nouveau mot de passe
                                </label>
                                <input type="password" class="form-control rounded-3" id="password" name="password" 
                                       placeholder="Minimum 8 caractères">
                            </div>
                            <div class="col-md-4">
                                <label for="password_confirmation" class="form-label fw-medium">
                                    <i class="fas fa-check-double text-muted me-2"></i>Confirmer le mot de passe
                                </label>
                                <input type="password" class="form-control rounded-3" id="password_confirmation" name="password_confirmation" 
                                       placeholder="Répéter le nouveau mot de passe">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="col-12">
                <div class="card border-0 bg-light rounded-4">
                    <div class="card-body p-4 text-center">
                        <div class="d-flex gap-3 justify-content-center flex-wrap">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow-sm">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                            <a href="{{ route('medecin.profile.show') }}" class="btn btn-outline-secondary btn-lg px-5 py-3 rounded-pill">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                        <small class="text-muted d-block mt-3">
                            <i class="fas fa-shield-alt me-1"></i>
                            Vos informations sont sécurisées et confidentielles
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Prévisualisation de l'image
document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

// Animation au focus des inputs
document.querySelectorAll('.form-control, .form-select').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'translateY(-2px)';
        this.parentElement.style.transition = 'transform 0.2s ease';
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'translateY(0)';
    });
});
</script>

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

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.rounded-4 {
    border-radius: 1rem !important;
}

.rounded-pill {
    border-radius: 50rem !important;
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.font-monospace {
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace !important;
}

.btn-lg {
    font-size: 1.1rem;
    font-weight: 600;
}

.alert {
    border-left: 4px solid;
}

.alert-success {
    border-left-color: #198754;
}

.alert-danger {
    border-left-color: #dc3545;
}

.alert-info {
    border-left-color: #0dcaf0;
}

.border-danger-subtle {
    border-color: rgba(220, 53, 69, 0.2) !important;
}

.text-primary { color: #667eea !important; }
.text-success { color: #10b981 !important; }
.text-info { color: #06b6d4 !important; }
.text-warning { color: #f59e0b !important; }
.text-danger { color: #ef4444 !important; }
.text-secondary { color: #6b7280 !important; }
</style>
@endsection