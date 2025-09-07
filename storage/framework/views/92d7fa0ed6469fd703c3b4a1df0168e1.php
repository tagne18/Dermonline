<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Toutes les annonces')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="container py-5">
        <div class="container border-success py-3">
            <h1 class="mb-4">Dernières annonces</h1>
        </div>

        <?php if($annonces->isEmpty()): ?>
            <div class="alert alert-info">Aucune annonce disponible pour le moment.</div>
        <?php else: ?>
            <div class="row">
                <?php $__currentLoopData = $annonces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $annonce): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php if($annonce->image): ?>
                                <img src="<?php echo e(asset('storage/' . $annonce->image)); ?>" class="card-img-top" alt="Image de l'annonce" style="max-height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0"><?php echo e($annonce->titre); ?></h5>
                                    <span class="badge <?php echo e($annonce->type === 'medecin' ? 'bg-primary' : 'bg-success'); ?> text-white">
                                        <?php echo e($annonce->type === 'medecin' ? 'Médecin' : 'Administration'); ?>

                                    </span>
                                </div>
                                
                                <div class="card-text mb-3 flex-grow-1">
                                    <?php echo \Illuminate\Support\Str::limit(strip_tags($annonce->contenu), 150); ?>

                                </div>
                                
                                <a href="#" class="btn btn-outline-primary btn-sm align-self-start" data-bs-toggle="modal" data-bs-target="#annonceModal<?php echo e($loop->index); ?>">
                                    Voir plus
                                </a>
                            </div>
                            
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        <?php echo e($annonce->created_at->format('d/m/Y')); ?>

                                    </small>
                                    <small>
                                        <?php if($annonce->type === 'medecin' && $annonce->user): ?>
                                            <i class="fas fa-user-md me-1"></i>
                                            Dr. <?php echo e($annonce->user->name); ?>

                                        <?php else: ?>
                                            <i class="fas fa-shield-alt me-1"></i>
                                            Administration
                                        <?php endif; ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal pour afficher le contenu complet -->
                    <div class="modal fade" id="annonceModal<?php echo e($loop->index); ?>" tabindex="-1" aria-labelledby="annonceModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="annonceModalLabel"><?php echo e($annonce->titre); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <?php if($annonce->image): ?>
                                        <img src="<?php echo e(asset('storage/' . $annonce->image)); ?>" class="img-fluid rounded mb-3" alt="Image de l'annonce">
                                    <?php endif; ?>
                                    <div class="mb-3">
                                        <?php echo $annonce->contenu; ?>

                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <small class="text-muted me-auto">
                                        Publié le <?php echo e($annonce->created_at->format('d/m/Y à H:i')); ?>

                                        <?php if($annonce->type === 'medecin' && $annonce->user): ?>
                                            par <strong>Dr. <?php echo e($annonce->user->name); ?></strong>
                                        <?php else: ?>
                                            par <strong>l'administration</strong>
                                        <?php endif; ?>
                                    </small>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

<?php $__env->startPush('styles'); ?>
<style>
    .card {
        transition: transform 0.2s ease-in-out;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .card-title {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .modal-content {
        border: none;
        border-radius: 10px;
    }
    
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
    
    .modal-footer {
        border-top: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
</style>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/patient/annonces/index.blade.php ENDPATH**/ ?>