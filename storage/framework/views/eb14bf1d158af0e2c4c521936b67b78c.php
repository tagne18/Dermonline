<?php $__env->startSection('title', 'Gestion des Patients'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-white text-3xl font-bold mb-6">👥 Gestion des Patients</h1>
        </div>
        
        <!-- Cartes de statistiques -->
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Patients</h5>
                            <h2 class="mb-0"><?php echo e($stats['total']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Abonnés Actifs</h5>
                            <h2 class="mb-0"><?php echo e($stats['abonnes']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Comptes Bloqués</h5>
                            <h2 class="mb-0"><?php echo e($stats['bloques']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-ban fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Nouveaux (30j)</h5>
                            <h2 class="mb-0"><?php echo e($stats['nouveaux']); ?></h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.users.patients')); ?>" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">🔍 Rechercher</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo e(request('search')); ?>" 
                           placeholder="Nom, email, ID, téléphone, ville...">
                </div>
                
                <div class="col-md-2">
                    <label for="abonnement" class="form-label">📦 Abonnement</label>
                    <select class="form-select" id="abonnement" name="abonnement">
                        <option value="">Tous</option>
                        <option value="actif" <?php echo e(request('abonnement') === 'actif' ? 'selected' : ''); ?>>Actif</option>
                        <option value="inactif" <?php echo e(request('abonnement') === 'inactif' ? 'selected' : ''); ?>>Inactif</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="statut" class="form-label">🔒 Statut</label>
                    <select class="form-select" id="statut" name="statut">
                        <option value="">Tous</option>
                        <option value="actif" <?php echo e(request('statut') === 'actif' ? 'selected' : ''); ?>>Actif</option>
                        <option value="bloque" <?php echo e(request('statut') === 'bloque' ? 'selected' : ''); ?>>Bloqué</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="sort" class="form-label">📊 Trier par</label>
                    <select class="form-select" id="sort" name="sort">
                        <option value="created_at" <?php echo e(request('sort') === 'created_at' ? 'selected' : ''); ?>>Date d'inscription</option>
                        <option value="name" <?php echo e(request('sort') === 'name' ? 'selected' : ''); ?>>Nom</option>
                        <option value="email" <?php echo e(request('sort') === 'email' ? 'selected' : ''); ?>>Email</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="order" class="form-label">Ordre</label>
                    <select class="form-select" id="order" name="order">
                        <option value="desc" <?php echo e(request('order') === 'desc' ? 'selected' : ''); ?>>Décroissant</option>
                        <option value="asc" <?php echo e(request('order') === 'asc' ? 'selected' : ''); ?>>Croissant</option>
                    </select>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                    <a href="<?php echo e(route('admin.users.patients')); ?>" class="btn btn-secondary">
                        <i class="fas fa-refresh"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages de succès/erreur -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tableau des patients -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Liste des Patients 
                <span class="badge bg-primary"><?php echo e($patients->total()); ?> résultat(s)</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Photo</th>
                            <th>Informations</th>
                            <th>Contact</th>
                            <th>Localisation</th>
                            <th>Abonnement</th>
                            <th>Dernier RDV</th>
                            <th>Statut</th>
                            <th>Inscrit le</th>
                            <th>Actions</th>
            </tr>
        </thead>
        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="<?php echo e($patient->is_blocked ? 'table-danger' : ''); ?>">
                                <td class="align-middle">
                                    <span class="badge bg-secondary">#<?php echo e($patient->id); ?></span>
                                </td>
                                
                                <td class="align-middle">
                                    <img src="<?php echo e($patient->profile_photo_url); ?>" 
                                         alt="Photo de <?php echo e($patient->name); ?>"
                                         class="rounded-circle" 
                                         width="40" height="40"
                                         style="object-fit: cover;">
                                </td>
                                
                                <td class="align-middle">
                                    <div>
                                        <strong><?php echo e($patient->name); ?></strong>
                                        <?php if($patient->gender): ?>
                                            <span class="badge bg-info ms-1"><?php echo e($patient->gender); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($patient->birth_date): ?>
                                        <small class="text-muted">
                                            <i class="fas fa-birthday-cake"></i> 
                                            <?php echo e(\Carbon\Carbon::parse($patient->birth_date)->age); ?> ans
                                        </small>
                                    <?php endif; ?>
                                    <?php if($patient->profession): ?>
                                        <div><small class="text-muted"><?php echo e($patient->profession); ?></small></div>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="align-middle">
                                    <div><i class="fas fa-envelope"></i> <?php echo e($patient->email); ?></div>
                                    <?php if($patient->phone): ?>
                                        <div><i class="fas fa-phone"></i> <?php echo e($patient->phone); ?></div>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="align-middle">
                                    <?php if($patient->city): ?>
                                        <i class="fas fa-map-marker-alt"></i> <?php echo e($patient->city); ?>

                                    <?php else: ?>
                                        <span class="text-muted">Non renseigné</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="align-middle">
                                    <?php if($patient->abonnement && $patient->abonnement->statut === 'actif'): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Actif
                                        </span>
                                        <?php if($patient->abonnement->date_fin): ?>
                                            <br><small class="text-muted">
                                                Expire: <?php echo e($patient->abonnement->date_fin ? \Carbon\Carbon::parse($patient->abonnement->date_fin)->format('d/m/Y') : ''); ?>

                                            </small>
                                        <?php endif; ?>
                        <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times"></i> Inactif
                                        </span>
                        <?php endif; ?>
                    </td>
                                
                                <td class="align-middle">
                                    <?php if($patient->appointments->isNotEmpty()): ?>
                                        <div class="text-success">
                                            <i class="fas fa-calendar-check"></i>
                            <?php echo e($patient->appointments->first()->date ? \Carbon\Carbon::parse($patient->appointments->first()->date)->format('d/m/Y') : ''); ?>

                                        </div>
                                        <small class="text-muted">
                                            <?php echo e($patient->appointments->count()); ?> RDV total
                                        </small>
                                    <?php else: ?>
                                        <span class="text-muted">Aucun RDV</span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="align-middle">
                                    <?php if($patient->is_blocked): ?>
                                        <span class="badge bg-danger">
                                            <i class="fas fa-ban"></i> Bloqué
                                        </span>
                                        <?php if($patient->blocked_at): ?>
                                            <br><small class="text-muted">
                                                <?php echo e($patient->blocked_at ? \Carbon\Carbon::parse($patient->blocked_at)->format('d/m/Y') : ''); ?>

                                            </small>
                                        <?php endif; ?>
                        <?php else: ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Actif
                                        </span>
                        <?php endif; ?>
                    </td>
                                
                                <td class="align-middle">
                                    <div><?php echo e($patient->created_at ? \Carbon\Carbon::parse($patient->created_at)->format('d/m/Y') : ''); ?></div>
                                    <small class="text-muted"><?php echo e($patient->created_at ? $patient->created_at->diffForHumans() : ''); ?></small>
                                </td>
                                
                                <td class="align-middle">
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('admin.users.patients.show', $patient->id)); ?>" 
                                           class="btn btn-sm btn-info" 
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if(!$patient->is_blocked): ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-warning btn-block-patient" 
                                                    data-id="<?php echo e($patient->id); ?>"
                                                    data-name="<?php echo e($patient->name); ?>"
                                                    title="Bloquer le compte">
                                                <i class="fas fa-ban"></i>
                                            </button>
                    <?php else: ?>
                                            <form action="<?php echo e(route('admin.utilisateurs.debloquer', $patient->id)); ?>" 
                                                  method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        title="Débloquer le compte">
                                                    <i class="fas fa-unlock"></i>
                                                </button>
                        </form>
                    <?php endif; ?>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-danger btn-delete-patient" 
                                                data-id="<?php echo e($patient->id); ?>"
                                                data-name="<?php echo e($patient->name); ?>"
                                                title="Supprimer définitivement">
                                            <i class="fas fa-trash"></i>
                                        </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fas fa-search fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Aucun patient trouvé avec ces critères.</p>
                                </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if($patients->hasPages()): ?>
            <div class="card-footer">
                <?php echo e($patients->appends(request()->query())->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de confirmation pour le blocage d'un patient -->
<div class="modal fade" id="blockPatientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmer le blocage
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="flex-shrink-0 me-3">
                        <i class="fas fa-user-times text-warning fa-3x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" id="patientName"></h5>
                        <p class="text-muted mb-0">
                            Êtes-vous sûr de vouloir bloquer l'accès de ce patient ?
                        </p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="blockReason" class="form-label">Raison du blocage (optionnel) :</label>
                    <textarea class="form-control" id="blockReason" rows="2" placeholder="Motif du blocage..."></textarea>
                    <div class="form-text">Cette information sera enregistrée mais ne sera pas visible par le patient.</div>
                </div>
                
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    Le patient ne pourra plus se connecter à son compte jusqu'à son déblocage.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Annuler
                </button>
                <form id="blockPatientForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="reason" id="blockReasonInput">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-lock me-1"></i> Confirmer le blocage
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Initialisation du modal de blocage
const blockPatientModal = new bootstrap.Modal(document.getElementById('blockPatientModal'));

// Gestion du clic sur le bouton de blocage
document.querySelectorAll('.btn-block-patient').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Récupération des données du patient
        const patientId = this.dataset.id;
        const patientName = this.dataset.name;
        
        // Mise à jour du modal
        document.getElementById('patientName').textContent = patientName;
        document.getElementById('blockPatientForm').action = `/admin/users/patients/${patientId}/block`;
        document.getElementById('blockReason').value = '';
        
        // Affichage du modal
        blockPatientModal.show();
    });
});

// Gestion de la soumission du formulaire de blocage
document.getElementById('blockPatientForm').addEventListener('submit', function(e) {
    // Copie de la raison dans le champ caché
    document.getElementById('blockReasonInput').value = document.getElementById('blockReason').value;
    return true;
});

// Auto-submit du formulaire de recherche lors du changement de filtres
document.querySelectorAll('#abonnement, #statut, #sort, #order').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/admin/users/patients.blade.php ENDPATH**/ ?>