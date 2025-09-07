<?php $__env->startSection('title', 'Mes Plannings'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .card-planning {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .card-planning:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .planning-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
        }
        
        .planning-body {
            padding: 1.5rem;
        }
        
        .planning-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .planning-date {
            display: flex;
            align-items: center;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        
        .planning-date i {
            margin-right: 0.5rem;
        }
        
        .planning-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            background-color: #f8f9fc;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
        }
        
        .meta-item i {
            margin-right: 0.5rem;
            color: #4e73df;
        }
        
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-status.planifie {
            background-color: #f6c23e33;
            color: #f6c23e;
        }
        
        .badge-status.confirme {
            background-color: #1cc88a33;
            color: #1cc88a;
        }
        
        .badge-status.annule {
            background-color: #e74a3b33;
            color: #e74a3b;
        }
        
        .badge-status.termine {
            background-color: #6c757d33;
            color: #6c757d;
        }
        
        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-action i {
            font-size: 0.875rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background-color: #f8f9fc;
            border-radius: 12px;
            margin: 2rem 0;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #d1d3e2;
            margin-bottom: 1.5rem;
        }
        
        .empty-state h4 {
            color: #4e73df;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: #6c757d;
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
            padding: 0.5rem 1rem;
        }
        
        .pagination .page-link:hover {
            background-color: #f8f9fc;
        }
        
        .btn-add-planning {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-add-planning:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
            color: white;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête avec actions rapides -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h2 mb-1 text-gray-800">
                <i class="fas fa-calendar-alt me-2"></i>Mes Plannings
            </h1>
            <p class="mb-0 text-muted">Gérez vos créneaux de consultation et suivez vos rendez-vous</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('medecin.planning.historique')); ?>" class="btn btn-outline-primary">
                <i class="fas fa-history me-2"></i>Historique
            </a>
            <a href="<?php echo e(route('medecin.planning.create')); ?>" class="btn-add-planning">
                <i class="fas fa-plus"></i> Nouveau Planning
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-body py-3">
            <form action="<?php echo e(route('medecin.planning.index')); ?>" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select name="statut" id="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="planifie" <?php echo e(request('statut') == 'planifie' ? 'selected' : ''); ?>>Planifié</option>
                        <option value="confirme" <?php echo e(request('statut') == 'confirme' ? 'selected' : ''); ?>>Confirmé</option>
                        <option value="annule" <?php echo e(request('statut') == 'annule' ? 'selected' : ''); ?>>Annulé</option>
                        <option value="termine" <?php echo e(request('statut') == 'termine' ? 'selected' : ''); ?>>Terminé</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="type_consultation" class="form-label">Type de consultation</label>
                    <select name="type_consultation" id="type_consultation" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="presentiel" <?php echo e(request('type_consultation') == 'presentiel' ? 'selected' : ''); ?>>Présentiel</option>
                        <option value="en_ligne" <?php echo e(request('type_consultation') == 'en_ligne' ? 'selected' : ''); ?>>En ligne</option>
                        <option value="hybride" <?php echo e(request('type_consultation') == 'hybride' ? 'selected' : ''); ?>>Hybride</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_debut" class="form-label">À partir du</label>
                    <input type="date" class="form-control" id="date_debut" name="date_debut" value="<?php echo e(request('date_debut')); ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter me-1"></i>Filtrer
                    </button>
                    <a href="<?php echo e(route('medecin.planning.index')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des plannings -->
    <?php if($plannings->count() > 0): ?>
        <div class="row">
            <?php $__currentLoopData = $plannings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $planning): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-planning h-100">
                        <div class="planning-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white"><?php echo e($planning->titre); ?></h5>
                                <span class="badge-status <?php echo e($planning->statut); ?>">
                                    <?php echo e(ucfirst($planning->statut)); ?>

                                </span>
                            </div>
                        </div>
                        <div class="planning-body">
                            <div class="planning-date">
                                <i class="far fa-calendar-alt"></i>
                                <span><?php echo e($planning->date_consultation->format('d/m/Y')); ?></span>
                            </div>
                            
                            <div class="planning-meta">
                                <div class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <span><?php echo e($planning->heure_debut); ?> - <?php echo e($planning->heure_fin); ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-stopwatch"></i>
                                    <span><?php echo e($planning->duree_formattee); ?></span>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-<?php echo e($planning->type_consultation == 'en_ligne' ? 'video' : ($planning->type_consultation == 'presentiel' ? 'building' : 'exchange-alt')); ?> me-2"></i>
                                <span class="text-muted"><?php echo e($planning->type_consultation_libelle); ?></span>
                            </div>
                            
                            <?php if($planning->description): ?>
                                <div class="mb-3">
                                    <p class="mb-1 text-muted small">Description :</p>
                                    <p class="mb-0"><?php echo e(Str::limit($planning->description, 100)); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0"><?php echo e(number_format($planning->prix, 0, ',', ' ')); ?> FCFA</span>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo e(route('medecin.planning.show', $planning)); ?>" class="btn-action btn-outline-primary">
                                        <i class="far fa-eye"></i>
                                    </a>
                                    <?php if($planning->statut == 'planifie'): ?>
                                        <a href="<?php echo e(route('medecin.planning.edit', $planning)); ?>" class="btn-action btn-outline-primary">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('medecin.planning.destroy', $planning)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce planning ?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn-action btn-outline-danger">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            <?php echo e($plannings->appends(request()->query())->links()); ?>

        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="empty-state">
                    <i class="far fa-calendar-plus"></i>
                    <h4>Aucun planning trouvé</h4>
                    <p>Commencez par créer un nouveau planning de consultation.</p>
                    <a href="<?php echo e(route('medecin.planning.create')); ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer un planning
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
    <script>
        // Initialiser le sélecteur de date
        document.addEventListener('DOMContentLoaded', function() {
            // Activer les tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialiser le sélecteur de date
            flatpickr("#date_consultation", {
                locale: "fr",
                minDate: "today",
                dateFormat: "Y-m-d",
                disable: [
                    function(date) {
                        // Désactiver les dimanches
                        return (date.getDay() === 0);
                    }
                ]
            });
            
            // Calculer automatiquement la durée de la consultation
            const heureDebut = document.getElementById('heure_debut');
            const heureFin = document.getElementById('heure_fin');
            const dureeConsultation = document.getElementById('duree_consultation');
            
            function calculerDuree() {
                if (heureDebut.value && heureFin.value) {
                    const debut = new Date('2000-01-01 ' + heureDebut.value);
                    const fin = new Date('2000-01-01 ' + heureFin.value);
                    
                    // Vérifier si l'heure de fin est le lendemain
                    if (fin < debut) {
                        fin.setDate(fin.getDate() + 1);
                    }
                    
                    const diffMinutes = Math.round((fin - debut) / (1000 * 60));
                    
                    if (diffMinutes > 0) {
                        dureeConsultation.value = diffMinutes;
                    }
                }
            }
            
            heureDebut.addEventListener('change', calculerDuree);
            heureFin.addEventListener('change', calculerDuree);
            
            // Mettre à jour le libellé du type de consultation
            const typeConsultation = document.getElementById('type_consultation');
            const iconeTypeConsultation = document.getElementById('icone_type_consultation');
            
            if (typeConsultation && iconeTypeConsultation) {
                typeConsultation.addEventListener('change', function() {
                    switch(this.value) {
                        case 'en_ligne':
                            iconeTypeConsultation.className = 'fas fa-video';
                            break;
                        case 'presentiel':
                            iconeTypeConsultation.className = 'fas fa-building';
                            break;
                        case 'hybride':
                            iconeTypeConsultation.className = 'fas fa-exchange-alt';
                            break;
                    }
                });
            }
        });
        
        // Confirmation avant suppression
        function confirmerSuppression(event) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce planning ? Cette action est irréversible.')) {
                event.preventDefault();
            }
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.medecin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/medecin/planning/index.blade.php ENDPATH**/ ?>