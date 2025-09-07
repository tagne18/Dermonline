<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête avec statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-white mb-0">Gestion des Médecins</h1>
                <div class="btn-group">
                    <a href="<?php echo e(route('admin.medecins.statistiques')); ?>" class="btn btn-info">
                        📊 Statistiques
                    </a>
                    <a href="<?php echo e(route('admin.medecins.export')); ?>" class="btn btn-success">
                        📥 Exporter CSV
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Cartes de statistiques -->
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Médecins</h5>
                    <h2><?php echo e($stats['total']); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Actifs</h5>
                    <h2><?php echo e($stats['actifs']); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Bloqués</h5>
                    <h2><?php echo e($stats['bloques']); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Nouveaux (30j)</h5>
                    <h2><?php echo e($stats['nouveaux']); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche et filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.medecins.index')); ?>" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?php echo e(request('search')); ?>" 
                                   placeholder="Nom, email, spécialité, ville...">
                        </div>
                        <div class="col-md-2">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select" id="statut" name="statut">
                                <option value="">Tous</option>
                                <option value="actif" <?php echo e(request('statut') === 'actif' ? 'selected' : ''); ?>>Actifs</option>
                                <option value="bloque" <?php echo e(request('statut') === 'bloque' ? 'selected' : ''); ?>>Bloqués</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="specialite" class="form-label">Spécialité</label>
                            <select class="form-select" id="specialite" name="specialite">
                                <option value="">Toutes</option>
                                <?php $__currentLoopData = $specialites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($spec); ?>" <?php echo e(request('specialite') === $spec ? 'selected' : ''); ?>>
                                        <?php echo e($spec); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sort" class="form-label">Tri</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="created_at" <?php echo e(request('sort') === 'created_at' ? 'selected' : ''); ?>>Date d'inscription</option>
                                <option value="name" <?php echo e(request('sort') === 'name' ? 'selected' : ''); ?>>Nom</option>
                                <option value="specialite" <?php echo e(request('sort') === 'specialite' ? 'selected' : ''); ?>>Spécialité</option>
                                <option value="ville" <?php echo e(request('sort') === 'ville' ? 'selected' : ''); ?>>Ville</option>
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
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                            <a href="<?php echo e(route('admin.medecins.index')); ?>" class="btn btn-secondary">Réinitialiser</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages de succès -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tableau des médecins -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Liste des Médecins (<?php echo e($medecins->total()); ?> résultats)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Médecin</th>
                                    <th>Contact</th>
                                    <th>Spécialité & Localisation</th>
                                    <th>Statistiques</th>
                                    <th>Statut</th>
                                    <th>Date d'inscription</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $medecins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medecin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <?php if($medecin->profile_photo_path): ?>
                                                        <img src="<?php echo e(Storage::url($medecin->profile_photo_path)); ?>" 
                                                             class="rounded-circle" width="40" height="40" alt="Photo">
                                                    <?php else: ?>
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                             style="width: 40px; height: 40px;">
                                                            <span class="text-white"><?php echo e(substr($medecin->name, 0, 1)); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <strong><?php echo e($medecin->name); ?></strong>
                                                    <br>
                                                    <small class="text-muted">ID: <?php echo e($medecin->id); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>Email:</strong> <?php echo e($medecin->email); ?><br>
                                                <strong>Téléphone:</strong> <?php echo e($medecin->phone ?? 'Non renseigné'); ?><br>
                                                <strong>Langue:</strong> <?php echo e($medecin->langue === 'fr' ? 'Français' : 'Anglais'); ?>

                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>Spécialité:</strong> <?php echo e($medecin->specialite ?? 'Non renseignée'); ?><br>
                                                <strong>Ville:</strong> <?php echo e($medecin->ville ?? 'Non renseignée'); ?><br>
                                                <strong>Lieu de travail:</strong> <?php echo e($medecin->lieu_travail ?? 'Non renseigné'); ?>

                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="badge bg-primary"><?php echo e($medecin->abonnes_count); ?> abonnés</span><br>
                                                <span class="badge bg-info"><?php echo e($medecin->consultations_as_medecin_count); ?> consultations</span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($medecin->is_blocked): ?>
                                                <span class="badge bg-danger">Bloqué</span>
                                                <?php if($medecin->blocked_at): ?>
                                                    <br><small class="text-muted">Depuis: <?php echo e(optional($medecin->blocked_at)->format('d/m/Y')); ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e(optional($medecin->created_at)->format('d/m/Y H:i')); ?>

                                            <br>
                                            <small class="text-muted"><?php echo e(optional($medecin->created_at)->diffForHumans()); ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Bouton voir détails -->
                                                <a href="<?php echo e(route('admin.medecins.show', $medecin->id)); ?>" 
                                                   class="btn btn-primary btn-sm" title="Voir détails">
                                                    👁️
                                                </a>
                                                
                                                <!-- Bouton d'alerte -->
                                                <button type="button" class="btn btn-warning btn-sm" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#alerteModal<?php echo e($medecin->id); ?>"
                                                        title="Envoyer une alerte">
                                                    ⚠️
                                                </button>
                                                
                                                <!-- Bouton bloquer/débloquer -->
                                                <?php if($medecin->is_blocked): ?>
                                                    <button type="button" class="btn btn-success btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#unblockDoctorModal<?php echo e($medecin->id); ?>"
                                                            title="Débloquer le médecin">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>

                                                    <!-- Modal de confirmation de déblocage -->
                                                    <div class="modal fade modal-unblock" id="unblockDoctorModal<?php echo e($medecin->id); ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">
                                                                <div class="modal-header bg-success text-white">
                                                                    <h5 class="modal-title fw-bold">
                                                                        <i class="fas fa-unlock-alt me-2"></i>
                                                                        Débloquer l'accès
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                                </div>
                                                                <form action="<?php echo e(route('admin.medecins.debloquer', $medecin->id)); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <div class="modal-body">
                                                                        <div class="d-flex align-items-center mb-4">
                                                                            <div class="flex-shrink-0 me-3">
                                                                                <div class="avatar-lg">
                                                                                    <img src="<?php echo e($medecin->profile_photo_url); ?>" alt="<?php echo e($medecin->name); ?>" class="img-thumbnail rounded-circle">
                                                                                </div>
                                                                            </div>
                                                                            <div class="flex-grow-1">
                                                                                <h6 class="mb-1"><?php echo e($medecin->name); ?></h6>
                                                                                <p class="text-muted mb-0">
                                                                                    <i class="fas fa-envelope me-1"></i> <?php echo e($medecin->email); ?>

                                                                                </p>
                                                                                <p class="text-muted mb-0">
                                                                                    <i class="fas fa-stethoscope me-1"></i> <?php echo e($medecin->specialite ?? 'Non spécifiée'); ?>

                                                                                </p>
                                                                                <?php if($medecin->blocked_at): ?>
                                                                                    <p class="text-danger small mt-2">
                                                                                        <i class="fas fa-calendar-times me-1"></i> Bloqué le <?php echo e(\Carbon\Carbon::parse($medecin->blocked_at)->format('d/m/Y à H:i')); ?>

                                                                                        <?php if($medecin->block_reason): ?>
                                                                                            <br><i class="fas fa-comment me-1"></i> Raison : <?php echo e($medecin->block_reason); ?>

                                                                                        <?php endif; ?>
                                                                                    </p>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="alert alert-info">
                                                                            <div class="d-flex align-items-center">
                                                                                <i class="fas fa-info-circle me-2"></i>
                                                                                <div>
                                                                                    <strong>Information :</strong> Le médecin retrouvera immédiatement l'accès à son compte.
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                            <i class="fas fa-times me-1"></i> Annuler
                                                                        </button>
                                                                        <button type="submit" class="btn btn-success">
                                                                            <i class="fas fa-check me-1"></i> Confirmer le déblocage
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-warning btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#blockDoctorModal<?php echo e($medecin->id); ?>"
                                                            title="Bloquer le médecin">
                                                        <i class="fas fa-lock"></i>
                                                    </button>

                                                    <!-- Modal de confirmation de blocage -->
                                                    <div class="modal fade" id="blockDoctorModal<?php echo e($medecin->id); ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">
                                                                <div class="modal-header bg-warning text-dark">
                                                                    <h5 class="modal-title fw-bold">
                                                                        <i class="fas fa-user-lock me-2"></i>
                                                                        Bloquer l'accès
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                                </div>
                                                                <form action="<?php echo e(route('admin.medecins.bloquer', $medecin->id)); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <div class="modal-body">
                                                                        <div class="d-flex align-items-center mb-4">
                                                                            <div class="flex-shrink-0 me-3">
                                                                                <i class="fas fa-user-md text-warning fa-3x"></i>
                                                                            </div>
                                                                            <div>
                                                                                <h5 class="fw-bold mb-1">Bloquer le Dr. <?php echo e($medecin->name); ?> ?</h5>
                                                                                <p class="text-muted mb-0">
                                                                                    Ce médecin ne pourra plus se connecter à son compte jusqu'à ce que vous le débloquiez.
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="mb-3">
                                                                            <label for="blockReason<?php echo e($medecin->id); ?>" class="form-label fw-semibold">
                                                                                <i class="fas fa-comment-alt me-2 text-warning"></i>Raison du blocage (optionnel)
                                                                            </label>
                                                                            <textarea class="form-control" 
                                                                                    id="blockReason<?php echo e($medecin->id); ?>" 
                                                                                    name="raison" 
                                                                                    rows="2" 
                                                                                    placeholder="Précisez la raison du blocage..."></textarea>
                                                                        </div>
                                                                        
                                                                        <div class="alert alert-warning" role="alert">
                                                                            <i class="fas fa-exclamation-circle me-2"></i>
                                                                            <strong>Note :</strong> Vous pourrez débloquer ce médecin à tout moment.
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                                            <i class="fas fa-times me-2"></i>Annuler
                                                                        </button>
                                                                        <button type="submit" class="btn btn-warning text-white">
                                                                            <i class="fas fa-lock me-2"></i>Confirmer le blocage
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </form>
                                                <?php endif; ?>
                                                
                                                <!-- Bouton supprimer -->
                                                <form action="<?php echo e(route('admin.medecins.destroy', $medecin->id)); ?>" 
                                                      method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#deleteDoctorModal<?php echo e($medecin->id); ?>"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>

                                                    <!-- Modal de confirmation de suppression -->
                                                    <div class="modal fade" id="deleteDoctorModal<?php echo e($medecin->id); ?>" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title fw-bold">
                                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                                        Confirmer la suppression
                                                                    </h5>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="d-flex align-items-center mb-4">
                                                                        <div class="flex-shrink-0 me-3">
                                                                            <i class="fas fa-user-md text-danger fa-3x"></i>
                                                                        </div>
                                                                        <div>
                                                                            <h5 class="fw-bold mb-1">Supprimer le Dr. <?php echo e($medecin->name); ?> ?</h5>
                                                                            <p class="text-muted mb-0">
                                                                                Cette action est irréversible. Toutes les données associées à ce médecin seront définitivement supprimées.
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="alert alert-warning" role="alert">
                                                                        <i class="fas fa-exclamation-circle me-2"></i>
                                                                        <strong>Attention :</strong> Cette action ne peut pas être annulée.
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                                        <i class="fas fa-times me-2"></i>Annuler
                                                                    </button>
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="fas fa-trash-alt me-2"></i>Supprimer définitivement
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <!-- Modal d'alerte moderne pour chaque médecin -->
                                    <div class="modal fade" id="alerteModal<?php echo e($medecin->id); ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <!-- En-tête avec dégradé de couleur -->
                                                <div class="modal-header bg-gradient-primary text-white rounded-top">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-bell me-2"></i>
                                                        <h5 class="modal-title mb-0 fw-bold">Nouvelle alerte pour <?php echo e($medecin->name); ?></h5>
                                                    </div>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                
                                                <form action="<?php echo e(route('admin.medecins.alerte', $medecin->id)); ?>" method="POST" class="needs-validation" novalidate>
                                                    <?php echo csrf_field(); ?>
                                                    <div class="modal-body p-4">
                                                        <!-- Sélecteur de type d'alerte avec icônes -->
                                                        <div class="mb-4">
                                                            <label for="type<?php echo e($medecin->id); ?>" class="form-label fw-semibold">
                                                                <i class="fas fa-tag me-2 text-primary"></i>Type d'alerte
                                                            </label>
                                                            <select class="form-select form-select-lg" id="type<?php echo e($medecin->id); ?>" name="type" required>
                                                                <option value="info"><i class="fas fa-info-circle text-primary me-2"></i> Information</option>
                                                                <option value="warning"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Avertissement</option>
                                                                <option value="danger"><i class="fas fa-exclamation-circle text-danger me-2"></i> Urgent</option>
                                                            </select>
                                                            <div class="form-text">Sélectionnez le niveau de priorité</div>
                                                        </div>
                                                        
                                                        <!-- Zone de message avec compteur de caractères -->
                                                        <div class="mb-3">
                                                            <label for="message<?php echo e($medecin->id); ?>" class="form-label fw-semibold">
                                                                <i class="fas fa-comment-alt me-2 text-primary"></i>Message
                                                            </label>
                                                            <div class="input-group">
                                                                <textarea class="form-control form-control-lg" 
                                                                          id="message<?php echo e($medecin->id); ?>" 
                                                                          name="message" 
                                                                          rows="4" 
                                                                          maxlength="500"
                                                                          placeholder="Décrivez la raison de cette alerte..." 
                                                                          required
                                                                          style="resize: none;"></textarea>
                                                            </div>
                                                            <div class="d-flex justify-content-between mt-1">
                                                                <small class="text-muted">Maximum 500 caractères</small>
                                                                <small class="text-muted"><span id="charCount<?php echo e($medecin->id); ?>">0</span>/500</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Pied de page avec boutons d'action -->
                                                    <div class="modal-footer bg-light rounded-bottom p-3">
                                                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i>Annuler
                                                        </button>
                                                        <button type="submit" class="btn btn-warning px-4 fw-bold text-white">
                                                            <i class="fas fa-paper-plane me-2"></i>Envoyer l'alerte
                                                        </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-user-md fa-3x mb-3"></i>
                                                <p>Aucun médecin trouvé avec les critères de recherche actuels.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if($medecins->hasPages()): ?>
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo e($medecins->appends(request()->query())->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour les avatars */
.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
}

.avatar-lg {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #fff;
    box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

.avatar-sm img, .avatar-lg img {
    object-fit: cover;
}
.btn-group .btn {
    margin-right: 2px;
}
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.table th {
    border-top: none;
}

/* Styles pour le modal moderne */
.modal.fade .modal-dialog {
    transform: translateY(-50px);
    transition: transform 0.3s ease-out, opacity 0.2s ease-out;
    opacity: 0;
}

.modal.show .modal-dialog {
    transform: translateY(0);
    opacity: 1;
}

/* Style personnalisé pour les options du select */
select option {
    padding: 8px 12px;
}

/* Animation du bouton d'envoi */
.btn-send-alert {
    position: relative;
    overflow: hidden;
    transition: all 0.3s;
}

.btn-send-alert:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.btn-send-alert:active {
    transform: translateY(0);
}

/* Style pour le compteur de caractères */
.char-counter {
    transition: color 0.3s;
}

.char-counter.warning {
    color: #ffc107;
    font-weight: bold;
}

.char-counter.danger {
    color: #dc3545;
    font-weight: bold;
}
</style>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialisation des tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Gestion du compteur de caractères pour chaque modal
    document.addEventListener('DOMContentLoaded', function() {
        // Pour chaque modal d'alerte
        document.querySelectorAll('[id^="alerteModal"]').forEach(function(modal) {
            const modalId = modal.id;
            const medecinId = modalId.replace('alerteModal', '');
            const textarea = document.getElementById('message' + medecinId);
            const charCount = document.getElementById('charCount' + medecinId);
            
            if (textarea && charCount) {
                // Mise à jour initiale
                updateCharCount(textarea, charCount);
                
                // Écouter les changements
                textarea.addEventListener('input', function() {
                    updateCharCount(this, charCount);
                });
            }
            
            // Gestion de la soumission du formulaire
            const form = modal.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            }
        });
        
        // Fonction pour mettre à jour le compteur de caractères
        function updateCharCount(textarea, counterElement) {
            const currentLength = textarea.value.length;
            const maxLength = textarea.getAttribute('maxlength');
            
            counterElement.textContent = currentLength;
            counterElement.className = 'char-counter';
            
            // Changer la couleur en fonction du nombre de caractères restants
            const remaining = maxLength - currentLength;
            if (remaining < 50) {
                counterElement.classList.add(remaining < 20 ? 'danger' : 'warning');
            }
        }
        
        // Animation personnalisée pour l'ouverture du modal
        document.querySelectorAll('.modal').forEach(function(modal) {
            modal.addEventListener('show.bs.modal', function () {
                // Réinitialiser le formulaire à l'ouverture
                const form = this.querySelector('form');
                if (form) {
                    form.classList.remove('was-validated');
                    form.reset();
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/admin/medecins/index.blade.php ENDPATH**/ ?>