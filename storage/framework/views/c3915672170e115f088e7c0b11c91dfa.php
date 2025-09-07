<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="text-white mb-2">Détails du Médecin</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>" class="text-white">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.medecins.index')); ?>" class="text-white">Médecins</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page"><?php echo e($medecin->name); ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-group">
                    <a href="<?php echo e(route('admin.medecins.index')); ?>" class="btn btn-secondary">← Retour</a>
                    <?php if($medecin->is_blocked): ?>
                        <form action="<?php echo e(route('admin.medecins.debloquer', $medecin->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success">🔓 Débloquer</button>
                        </form>
                    <?php else: ?>
                        <form action="<?php echo e(route('admin.medecins.bloquer', $medecin->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger">🔒 Bloquer</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations du Médecin</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Nom complet:</strong> <?php echo e($medecin->name); ?>

                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong> <?php echo e($medecin->email); ?>

                            </div>
                            <div class="mb-3">
                                <strong>Téléphone:</strong> <?php echo e($medecin->phone ?? 'Non renseigné'); ?>

                            </div>
                            <div class="mb-3">
                                <strong>Langue:</strong> <?php echo e($medecin->langue === 'fr' ? 'Français' : 'Anglais'); ?>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Spécialité:</strong> <?php echo e($medecin->specialite ?? 'Non renseignée'); ?>

                            </div>
                            <div class="mb-3">
                                <strong>Ville:</strong> <?php echo e($medecin->ville ?? 'Non renseignée'); ?>

                            </div>
                            <div class="mb-3">
                                <strong>Lieu de travail:</strong> <?php echo e($medecin->lieu_travail ?? 'Non renseigné'); ?>

                            </div>
                            <div class="mb-3">
                                <strong>Date d'inscription:</strong> <?php echo e($medecin->created_at ? \Carbon\Carbon::parse($medecin->created_at)->format('d/m/Y H:i') : ''); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="bg-primary text-white rounded p-3">
                                <h3><?php echo e($stats['total_abonnes']); ?></h3>
                                <small>Abonnés</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-success text-white rounded p-3">
                                <h3><?php echo e($stats['total_consultations']); ?></h3>
                                <small>Consultations</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-info text-white rounded p-3">
                                <h3><?php echo e($stats['abonnes_ce_mois']); ?></h3>
                                <small>Nouveaux (ce mois)</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="bg-warning text-white rounded p-3">
                                <h3><?php echo e($stats['consultations_ce_mois']); ?></h3>
                                <small>Consultations (ce mois)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if($medecin->abonnes->count() > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Patients Abonnés (<?php echo e($medecin->abonnes->count()); ?>)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Email</th>
                                    <th>Date d'abonnement</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $medecin->abonnes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $abonnement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($abonnement->patient->name ?? 'Patient supprimé'); ?></td>
                                        <td><?php echo e($abonnement->patient->email ?? 'N/A'); ?></td>
                                        <td><?php echo e($abonnement->created_at ? \Carbon\Carbon::parse($abonnement->created_at)->format('d/m/Y') : ''); ?></td>
                                        <td>
                                            <?php if($abonnement->patient && $abonnement->patient->is_blocked): ?>
                                                <span class="badge bg-danger">Bloqué</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Actif</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
.breadcrumb-item a {
    text-decoration: none;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/admin/medecins/show.blade.php ENDPATH**/ ?>