<?php $__env->startSection('title', 'Détails du Patient'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-white text-3xl font-bold">👤 Détails du Patient</h1>
                <a href="<?php echo e(route('admin.users.patients')); ?>" class="btn btn-secondary">
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
                                <p class="form-control-plaintext"><?php echo e($patient->name); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext"><?php echo e($patient->email); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Téléphone</label>
                                <p class="form-control-plaintext"><?php echo e($patient->phone ?? 'Non renseigné'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Genre</label>
                                <p class="form-control-plaintext"><?php echo e($patient->gender ?? 'Non renseigné'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Date de naissance</label>
                                <p class="form-control-plaintext">
                                    <?php if($patient->birth_date): ?>
                                        <?php echo e(\Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y')); ?>

                                        (<?php echo e(\Carbon\Carbon::parse($patient->birth_date)->age); ?> ans)
                                    <?php else: ?>
                                        Non renseigné
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Profession</label>
                                <p class="form-control-plaintext"><?php echo e($patient->profession ?? 'Non renseigné'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Ville</label>
                                <p class="form-control-plaintext"><?php echo e($patient->city ?? 'Non renseigné'); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Statut du compte</label>
                                <p class="form-control-plaintext">
                                    <?php if($patient->is_blocked): ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-ban"></i> Bloqué
                                            <?php if($patient->blocked_at): ?>
                                                depuis <?php echo e(\Carbon\Carbon::parse($patient->blocked_at)->format('d/m/Y')); ?>

                                            <?php endif; ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Actif
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Inscrit le</label>
                                <p class="form-control-plaintext">
                                    <?php echo e($patient->created_at->format('d/m/Y à H:i')); ?>

                                    <br><small class="text-muted"><?php echo e($patient->created_at->diffForHumans()); ?></small>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Dernière connexion</label>
                                <p class="form-control-plaintext">
                                    <?php if($patient->last_login_at): ?>
                                        <?php echo e(\Carbon\Carbon::parse($patient->last_login_at)->format('d/m/Y à H:i')); ?>

                                        <br><small class="text-muted"><?php echo e(\Carbon\Carbon::parse($patient->last_login_at)->diffForHumans()); ?></small>
                                    <?php else: ?>
                                        Jamais connecté
                                    <?php endif; ?>
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
                    <img src="<?php echo e($patient->profile_photo_url); ?>" 
                         alt="Photo de <?php echo e($patient->name); ?>"
                         class="img-fluid rounded-circle mb-3" 
                         style="width: 200px; height: 200px; object-fit: cover;">
                    
                    <div class="mt-3">
                        <?php if(!$patient->is_blocked): ?>
                            <form action="<?php echo e(route('admin.utilisateurs.bloquer', $patient->id)); ?>" 
                                  method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <!-- Bouton pour déclencher le modal de blocage -->
                                <button type="button" class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#blockPatientModal">
                                    <i class="fas fa-ban"></i> Bloquer
                                </button>
                                
                                <!-- Modal de confirmation de blocage -->
                                <div class="modal fade" id="blockPatientModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header bg-gradient-warning text-dark">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-white d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                                        <i class="fas fa-user-lock text-warning"></i>
                                                    </div>
                                                    <h5 class="modal-title fw-bold mb-0">
                                                        Bloquer l'accès du patient
                                                    </h5>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                            </div>
                                            <form action="<?php echo e(route('admin.utilisateurs.bloquer', $patient->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-body">
                                                    <div class="d-flex align-items-start mb-4 p-3 bg-light rounded">
                                                        <div class="flex-shrink-0 me-3">
                                                            <img src="<?php echo e($patient->profile_photo_url); ?>" 
                                                                 alt="<?php echo e($patient->name); ?>" 
                                                                 class="img-thumbnail rounded-circle"
                                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 fw-bold text-dark"><?php echo e($patient->name); ?></h6>
                                                            <div class="d-flex align-items-center text-muted mb-1">
                                                                <i class="fas fa-envelope me-2"></i>
                                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?php echo e($patient->email); ?>">
                                                                    <?php echo e($patient->email); ?>

                                                                </span>
                                                            </div>
                                                            <div class="d-flex align-items-center text-muted">
                                                                <i class="fas fa-calendar-alt me-2"></i>
                                                                <span>Inscrit le <?php echo e($patient->created_at->format('d/m/Y')); ?></span>
                                                            </div>
                                                            <div class="mt-2">
                                                                <span class="badge bg-soft-primary text-primary">
                                                                    <i class="fas fa-user me-1"></i> Patient
                                                                </span>
                                                                <?php if($patient->last_login_at): ?>
                                                                    <span class="badge bg-soft-success text-success ms-1">
                                                                        <i class="fas fa-circle-check me-1"></i> Actif
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                        <label for="blockReason" class="form-label fw-bold d-flex align-items-center">
                                                            <i class="fas fa-comment-dots me-2 text-warning"></i> 
                                                            <span>Raison du blocage <span class="text-muted">(optionnel)</span></span>
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light">
                                                                <i class="fas fa-info-circle text-muted"></i>
                                                            </span>
                                                            <textarea class="form-control" 
                                                                      id="blockReason" 
                                                                      name="block_reason"
                                                                      rows="3" 
                                                                      placeholder="Veuillez indiquer la raison du blocage..."
                                                                      style="resize: none;"></textarea>
                                                        </div>
                                                        <div class="form-text text-muted mt-1">
                                                            <i class="fas fa-info-circle me-1"></i> 
                                                            Cette raison sera enregistrée dans l'historique et visible par les administrateurs.
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="alert alert-warning border-0 bg-soft-warning">
                                                        <div class="d-flex">
                                                            <div class="flex-shrink-0 me-3">
                                                                <i class="fas fa-exclamation-triangle text-warning fs-4 mt-1"></i>
                                                            </div>
                                                            <div>
                                                                <h5 class="alert-heading d-flex align-items-center">
                                                                    <span class="me-2">Action irréversible</span>
                                                                    <span class="badge bg-warning text-dark">Important</span>
                                                                </h5>
                                                                <p class="mb-0">
                                                                    En confirmant, le patient ne pourra plus accéder à son compte jusqu'à ce qu'un administrateur le débloque. 
                                                                    Toutes les sessions actives seront immédiatement fermées.
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- État de chargement -->
                                                    <div id="blockLoading" class="text-center d-none">
                                                        <div class="spinner-border text-warning mb-3" role="status">
                                                            <span class="visually-hidden">Chargement...</span>
                                                        </div>
                                                        <p class="text-muted">Traitement en cours, veuillez patienter...</p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light rounded-bottom">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                                                        <i class="fas fa-times me-2"></i> Annuler
                                                    </button>
                                                    <button type="submit" class="btn btn-warning rounded-pill px-4" id="confirmBlockBtn">
                                                        <i class="fas fa-ban me-2"></i> Confirmer le blocage
                                                    </button>
                                                </div>
                                                
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const form = document.querySelector('#blockPatientForm');
                                                        const loadingIndicator = document.querySelector('#blockLoading');
                                                        const submitButton = document.querySelector('#confirmBlockBtn');
                                                        
                                                        if (form) {
                                                            form.addEventListener('submit', function(e) {
                                                                // Afficher l'indicateur de chargement
                                                                loadingIndicator.classList.remove('d-none');
                                                                // Désactiver le bouton de soumission
                                                                submitButton.disabled = true;
                                                                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement...';
                                                                
                                                                // Le formulaire sera soumis normalement après ce délai
                                                                // pour permettre l'animation de chargement de s'afficher
                                                                setTimeout(() => {
                                                                    form.submit();
                                                                }, 500);
                                                            });
                                                        }
                                                    });
                                                </script>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php else: ?>
                            <!-- Bouton pour déclencher le modal de déblocage -->
                            <button type="button" class="btn btn-success btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#unblockPatientModal"
                                    style="min-width: 100px;">
                                <i class="fas fa-unlock me-1"></i> Débloquer
                            </button>
                            
                            <!-- Modal de confirmation de déblocage -->
                            <div class="modal fade" id="unblockPatientModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg">
                                        <div class="modal-header bg-gradient-success text-white">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-white d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                                    <i class="fas fa-unlock text-success"></i>
                                                </div>
                                                <h5 class="modal-title fw-bold mb-0">
                                                    Débloquer l'accès du patient
                                                </h5>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                        </div>
                                        <form id="unblockPatientForm" action="<?php echo e(route('admin.utilisateurs.debloquer', $patient->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal-body">
                                                <div class="d-flex align-items-start mb-4 p-3 bg-light rounded">
                                                    <div class="flex-shrink-0 me-3">
                                                        <img src="<?php echo e($patient->profile_photo_url); ?>" 
                                                             alt="<?php echo e($patient->name); ?>" 
                                                             class="img-thumbnail rounded-circle"
                                                             style="width: 80px; height: 80px; object-fit: cover;">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold text-dark"><?php echo e($patient->name); ?></h6>
                                                        <div class="d-flex align-items-center text-muted mb-1">
                                                            <i class="fas fa-envelope me-2"></i>
                                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="<?php echo e($patient->email); ?>">
                                                                <?php echo e($patient->email); ?>

                                                            </span>
                                                        </div>
                                                        <div class="d-flex align-items-center text-muted">
                                                            <i class="fas fa-calendar-times me-2"></i>
                                                            <span>Bloqué depuis <?php echo e($patient->blocked_at ? \Carbon\Carbon::parse($patient->blocked_at)->format('d/m/Y') : 'Date inconnue'); ?></span>
                                                        </div>
                                                        <?php if($patient->block_reason): ?>
                                                        <div class="mt-2">
                                                            <span class="badge bg-soft-warning text-warning">
                                                                <i class="fas fa-comment-alt me-1"></i> 
                                                                Raison: <?php echo e(Str::limit($patient->block_reason, 30)); ?>

                                                            </span>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="alert alert-success border-0 bg-soft-success">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <i class="fas fa-info-circle text-success fs-4 mt-1"></i>
                                                        </div>
                                                        <div>
                                                            <h5 class="alert-heading d-flex align-items-center">
                                                                <span class="me-2">Confirmation de déblocage</span>
                                                                <span class="badge bg-success">Sécurisé</span>
                                                            </h5>
                                                            <p class="mb-0">
                                                                Le patient pourra immédiatement se reconnecter à son compte. 
                                                                Une notification lui sera envoyée pour l'informer de cette modification.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- État de chargement -->
                                                <div id="unblockLoading" class="text-center d-none">
                                                    <div class="spinner-border text-success mb-3" role="status">
                                                        <span class="visually-hidden">Chargement...</span>
                                                    </div>
                                                    <p class="text-muted">Déblocage en cours, veuillez patienter...</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light rounded-bottom">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-2"></i> Annuler
                                                </button>
                                                <button type="submit" class="btn btn-success rounded-pill px-4" id="confirmUnblockBtn">
                                                    <i class="fas fa-unlock me-2"></i> Confirmer le déblocage
                                                </button>
                                            </div>
                                            
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    const form = document.querySelector('#unblockPatientForm');
                                                    const loadingIndicator = document.querySelector('#unblockLoading');
                                                    const submitButton = document.querySelector('#confirmUnblockBtn');
                                                    
                                                    if (form) {
                                                        form.addEventListener('submit', function(e) {
                                                            // Afficher l'indicateur de chargement
                                                            loadingIndicator.classList.remove('d-none');
                                                            // Désactiver le bouton de soumission
                                                            submitButton.disabled = true;
                                                            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Traitement...';
                                                            
                                                            // Le formulaire sera soumis normalement après ce délai
                                                            // pour permettre l'animation de chargement de s'afficher
                                                            setTimeout(() => {
                                                                form.submit();
                                                            }, 500);
                                                        });
                                                    }
                                                });
                                            </script>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Bouton pour déclencher le modal de suppression -->
                        <button type="button" class="btn btn-danger btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deletePatientModal">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                        
                        <!-- Modal de confirmation de suppression -->
                        <div class="modal fade" id="deletePatientModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title fw-bold">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Confirmer la suppression
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <form action="<?php echo e(route('admin.users.patients.destroy', $patient->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <div class="modal-body">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-lg">
                                                        <img src="<?php echo e($patient->profile_photo_url); ?>" alt="<?php echo e($patient->name); ?>" class="img-thumbnail rounded-circle">
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1"><?php echo e($patient->name); ?></h6>
                                                    <p class="text-muted mb-0">
                                                        <i class="fas fa-envelope me-1"></i> <?php echo e($patient->email); ?>

                                                    </p>
                                                    <p class="text-muted mb-0">
                                                        <i class="fas fa-calendar-alt me-1"></i> Inscrit le <?php echo e($patient->created_at->format('d/m/Y')); ?>

                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="alert alert-danger">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-exclamation-circle me-2"></i>
                                                    <div>
                                                        <strong>Attention :</strong> Cette action est irréversible. Toutes les données liées à ce patient seront définitivement supprimées, y compris :
                                                        <ul class="mb-0 mt-2">
                                                            <li>Ses informations personnelles</li>
                                                            <li>Son historique de rendez-vous</li>
                                                            <li>Ses messages et conversations</li>
                                                            <li>Ses documents médicaux</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                                                <label class="form-check-label" for="confirmDelete">
                                                    Je confirme vouloir supprimer définitivement ce patient
                                                </label>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i> Annuler
                                            </button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-trash me-1"></i> Confirmer la suppression
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
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
                    <?php if($patient->abonnement): ?>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Type d'abonnement</label>
                                <p class="form-control-plaintext"><?php echo e($patient->abonnement->type); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Statut</label>
                                <p class="form-control-plaintext">
                                    <?php if($patient->abonnement->statut === 'actif'): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactif</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Date de début</label>
                                <p class="form-control-plaintext">
                                    <?php echo e($patient->abonnement->date_debut ? \Carbon\Carbon::parse($patient->abonnement->date_debut)->format('d/m/Y') : 'Non définie'); ?>

                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Date de fin</label>
                                <p class="form-control-plaintext">
                                    <?php if($patient->abonnement->date_fin): ?>
                                        <?php echo e(\Carbon\Carbon::parse($patient->abonnement->date_fin)->format('d/m/Y')); ?>

                                        <?php if(\Carbon\Carbon::parse($patient->abonnement->date_fin)->isPast()): ?>
                                            <br><small class="text-danger">Expiré</small>
                                        <?php else: ?>
                                            <br><small class="text-success">Valide</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        Non définie
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucun abonnement trouvé pour ce patient.</p>
                    <?php endif; ?>
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
                    <?php if($patient->appointments->isNotEmpty()): ?>
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
                                    <?php $__currentLoopData = $patient->appointments->sortByDesc('date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(\Carbon\Carbon::parse($appointment->date)->format('d/m/Y')); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($appointment->date)->format('H:i')); ?></td>
                                            <td>
                                                <?php switch($appointment->statut):
                                                    case ('en_attente'): ?>
                                                        <span class="badge bg-warning">En attente</span>
                                                        <?php break; ?>
                                                    <?php case ('confirme'): ?>
                                                        <span class="badge bg-info">Confirmé</span>
                                                        <?php break; ?>
                                                    <?php case ('termine'): ?>
                                                        <span class="badge bg-success">Terminé</span>
                                                        <?php break; ?>
                                                    <?php case ('annule'): ?>
                                                        <span class="badge bg-danger">Annulé</span>
                                                        <?php break; ?>
                                                    <?php default: ?>
                                                        <span class="badge bg-secondary"><?php echo e($appointment->statut); ?></span>
                                                <?php endswitch; ?>
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucun rendez-vous trouvé pour ce patient.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/admin/users/patient-detail.blade.php ENDPATH**/ ?>