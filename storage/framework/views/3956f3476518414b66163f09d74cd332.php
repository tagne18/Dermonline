<?php $__env->startSection('title', 'Mes annonces'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-bullhorn me-2"></i>Mes annonces
                        </h5>
                        <a href="<?php echo e(route('medecin.new-annonces.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Nouvelle annonce
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Filtres -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="btn-group w-100" role="group">
                                <a href="<?php echo e(request()->fullUrlWithQuery(['statut' => 'tous'])); ?>" 
                                   class="btn <?php echo e(request('statut') === 'tous' || !request('statut') ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    Toutes
                                </a>
                                <a href="<?php echo e(request()->fullUrlWithQuery(['statut' => 'publie'])); ?>" 
                                   class="btn <?php echo e(request('statut') === 'publie' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    Publiées
                                </a>
                                <a href="<?php echo e(request()->fullUrlWithQuery(['statut' => 'brouillon'])); ?>" 
                                   class="btn <?php echo e(request('statut') === 'brouillon' ? 'btn-primary' : 'btn-outline-primary'); ?>">
                                    Brouillons
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 ms-auto">
                            <form action="<?php echo e(route('medecin.new-annonces.index')); ?>" method="GET">
                                <div class="input-group">
                                    <input type="text" name="recherche" class="form-control" 
                                           placeholder="Rechercher une annonce..." value="<?php echo e(request('recherche')); ?>">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Liste des annonces -->
                    <?php if($annonces->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Titre</th>
                                        <th>Statut</th>
                                        <th>Date de création</th>
                                        <th>Publication</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $annonces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $annonce): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if($annonce->image_path): ?>
                                                        <img src="<?php echo e($annonce->image_url); ?>" alt="Image de l'annonce" 
                                                             class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo e($annonce->titre); ?></h6>
                                                        <small class="text-muted">
                                                            <?php echo e(Str::limit(strip_tags($annonce->contenu), 50)); ?>

                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($annonce->estPubliee()): ?>
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i> Publiée
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-edit me-1"></i> Brouillon
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($annonce->created_at->format('d/m/Y H:i')); ?></td>
                                            <td>
                                                <?php if($annonce->date_publication): ?>
                                                    <?php echo e($annonce->date_publication->format('d/m/Y H:i')); ?>

                                                <?php else: ?>
                                                    <span class="text-muted">Non publiée</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('medecin.new-annonces.edit', $annonce)); ?>" 
                                                       class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" 
                                                       title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($annonce->id); ?>"
                                                            title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>

                                                <!-- Modal de confirmation de suppression -->
                                                <div class="modal fade" id="deleteModal<?php echo e($annonce->id); ?>" tabindex="-1" 
                                                     aria-labelledby="deleteModalLabel<?php echo e($annonce->id); ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel<?php echo e($annonce->id); ?>">
                                                                    Confirmer la suppression
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                                                                        aria-label="Fermer"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Êtes-vous sûr de vouloir supprimer l'annonce "<?php echo e($annonce->titre); ?>" ?
                                                                Cette action est irréversible.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" 
                                                                        data-bs-dismiss="modal">Annuler</button>
                                                                <form action="<?php echo e(route('medecin.new-annonces.destroy', $annonce)); ?>" 
                                                                      method="POST" class="d-inline">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <button type="submit" class="btn btn-danger">
                                                                        <i class="fas fa-trash-alt me-1"></i> Supprimer
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            <?php echo e($annonces->withQueryString()->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-bullhorn fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted">Aucune annonce trouvée</h5>
                            <p class="text-muted">
                                <?php if(request('recherche') || request('statut')): ?>
                                    Aucune annonce ne correspond à vos critères de recherche.
                                <?php else: ?>
                                    Vous n'avez pas encore créé d'annonce.
                                <?php endif; ?>
                            </p>
                            <a href="<?php echo e(route('medecin.new-annonces.create')); ?>" class="btn btn-primary mt-2">
                                <i class="fas fa-plus me-1"></i> Créer une annonce
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Initialisation des tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.medecin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/medecin/new-annonces/index.blade.php ENDPATH**/ ?>