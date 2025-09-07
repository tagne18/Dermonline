<?php $__env->startSection('title', 'Historique des Consultations'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .card-historique {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .consultation-card {
            border-left: 4px solid #4e73df;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }
        
        .consultation-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .badge-status {
            font-size: 0.8rem;
            padding: 0.35em 0.65em;
        }
        
        .patient-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }
        
        .filters {
            background-color: #f8f9fc;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        
        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .pagination .page-link {
            color: #4e73df;
            border: 1px solid #e3e6f0;
            margin: 0 3px;
            border-radius: 8px !important;
        }
        
        .pagination .page-link:hover {
            background-color: #f8f9fc;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #d1d3e2;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Historique des Consultations</h1>
        <div>
            <a href="<?php echo e(route('medecin.planning.index')); ?>" class="btn btn-primary">
                <i class="fas fa-calendar-alt me-2"></i>Gérer mon planning
            </a>
        </div>
    </div>

    <div class="card card-historique">
        <div class="card-body">
            <div class="filters">
                <form action="<?php echo e(route('medecin.planning.historique')); ?>" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="patient" class="form-label">Patient</label>
                        <input type="text" class="form-control" id="patient" name="patient" 
                               value="<?php echo e(request('patient')); ?>" placeholder="Nom du patient">
                    </div>
                    <div class="col-md-2">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Tous</option>
                            <option value="presentiel" <?php echo e(request('type') == 'presentiel' ? 'selected' : ''); ?>>Présentiel</option>
                            <option value="en_ligne" <?php echo e(request('type') == 'en_ligne' ? 'selected' : ''); ?>>En ligne</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_debut" class="form-label">Date début</label>
                        <input type="date" class="form-control" id="date_debut" name="date_debut" 
                               value="<?php echo e(request('date_debut')); ?>">
                    </div>
                    <div class="col-md-2">
                        <label for="date_fin" class="form-label">Date fin</label>
                        <input type="date" class="form-control" id="date_fin" name="date_fin" 
                               value="<?php echo e(request('date_fin')); ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                        <a href="<?php echo e(route('medecin.planning.historique')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
            
            <?php if($consultations->count() > 0): ?>
                <?php $__currentLoopData = $consultations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consultation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card consultation-card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e($consultation->patient->profile_photo_url ?? asset('images/default-avatar.png')); ?>" 
                                         alt="<?php echo e($consultation->patient->name); ?>" 
                                         class="patient-avatar">
                                    <div>
                                        <h6 class="mb-0"><?php echo e($consultation->patient->name); ?></h6>
                                        <small class="text-muted">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            <?php echo e($consultation->date_consultation->format('d/m/Y H:i')); ?>

                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge <?php echo e($consultation->type_consultation == 'en_ligne' ? 'bg-info' : 'bg-primary'); ?> badge-status">
                                        <i class="fas <?php echo e($consultation->type_consultation == 'en_ligne' ? 'fa-video' : 'fa-building'); ?> me-1"></i>
                                        <?php echo e($consultation->type_consultation == 'en_ligne' ? 'En ligne' : 'Présentiel'); ?>

                                    </span>
                                    <div class="mt-2">
                                        <span class="badge bg-success badge-status">
                                            <i class="fas fa-check-circle me-1"></i>Terminée
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if($consultation->motif || $consultation->diagnostic): ?>
                                <div class="mt-3">
                                    <?php if($consultation->motif): ?>
                                        <p class="mb-1"><strong>Motif :</strong> <?php echo e($consultation->motif); ?></p>
                                    <?php endif; ?>
                                    <?php if($consultation->diagnostic): ?>
                                        <p class="mb-0"><strong>Diagnostic :</strong> <?php echo e($consultation->diagnostic); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="d-flex justify-content-end mt-3">
                                <a href="<?php echo e(route('medecin.consultations.show', $consultation)); ?>" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    <?php echo e($consultations->withQueryString()->links()); ?>

                </div>
                
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h4>Aucune consultation trouvée</h4>
                    <p class="mb-0">Aucune consultation ne correspond à vos critères de recherche.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.medecin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/medecin/planning/historique.blade.php ENDPATH**/ ?>