<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Liste des patients</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Patients ayant pris rendez-vous</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Dernier rendez-vous</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($patient->name); ?></td>
                                <td><?php echo e($patient->email); ?></td>
                                <td><?php echo e($patient->phone ?? 'Non renseigné'); ?></td>
                                <td>
                                    <?php if($patient->last_appointment): ?>
                                        <?php echo e($patient->last_appointment->date->format('d/m/Y H:i')); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge <?php echo e($patient->is_subscribed ? 'badge-success' : 'badge-secondary'); ?>">
                                        <?php echo e($patient->is_subscribed ? 'Abonné' : 'Non abonné'); ?>

                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('medecin.messages.create', ['user' => $patient->id])); ?>" 
                                       class="btn btn-sm btn-primary" 
                                       title="Envoyer un message">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <?php if(!$patient->is_subscribed): ?>
                                        <button class="btn btn-sm btn-success" 
                                                data-toggle="modal" 
                                                data-target="#subscribeModal<?php echo e($patient->id); ?>"
                                                title="Proposer un abonnement">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Modal pour proposer un abonnement -->
                            <div class="modal fade" id="subscribeModal<?php echo e($patient->id); ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="<?php echo e(route('medecin.abonnements.propose', $patient)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal-header">
                                                <h5 class="modal-title">Proposer un abonnement</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="type">Type d'abonnement :</label>
                                                    <select class="form-control" id="type" name="type" required>
                                                        <option value="mensuel">Mensuel - 10 000 FCFA</option>
                                                        <option value="trimestriel">Trimestriel - 25 000 FCFA</option>
                                                        <option value="annuel">Annuel - 80 000 FCFA</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="message">Message (optionnel) :</label>
                                                    <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Envoyer la proposition</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucun patient trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .table th, .table td {
        vertical-align: middle;
    }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.medecin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/medecin/abonnements/index.blade.php ENDPATH**/ ?>