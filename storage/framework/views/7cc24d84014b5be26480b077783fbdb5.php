 <!-- ou layouts.app selon ton template -->

<?php $__env->startSection('content'); ?><?php if(session('success')): ?>
<div class="alert alert-success">
    <?php echo e(session('success')); ?>

</div>
<?php elseif(session('info')): ?>
<div class="alert alert-info">
    <?php echo e(session('info')); ?>

</div>

<?php if(session('approved_password')): ?>
<!-- Modal Mot de passe par défaut -->
<div class="modal fade" id="approvedModal" tabindex="-1" aria-labelledby="approvedModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-success text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="approvedModalLabel">Compte Médecin Approuvé ✅</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <p><strong>Email :</strong> <?php echo e(session('approved_email')); ?></p>
        <p><strong>Mot de passe par défaut :</strong> <code><?php echo e(session('approved_password')); ?></code></p>
        <p>⚠️ Demandez au médecin de le modifier à sa première connexion.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Script pour ouvrir le modal automatiquement -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var approvedModal = new bootstrap.Modal(document.getElementById('approvedModal'));
        approvedModal.show();
    });
</script>
<?php endif; ?>

<?php endif; ?>


<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <span class="text-white mt-5 fz-5">Demandes des Médecins</span>
    <a href="<?php echo e(route('admin.medecins.index')); ?>" class="btn btn-info">👨‍⚕️ Liste des Médecins</a>
</div>

    <table class="table text-white table-bordered border-success ">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Spécialité</th>
                <th>Ville</th>
                <th>Langue</th>
                <th>Lieu de travail</th>
                <th>Matricule</th>
                <th>Licence</th>
                <th>Expérience</th>
                <th>Domaine d'expertise</th>
                <th>CV</th>
                <th>Approuvée le</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($app->name); ?></td>
                    <td><?php echo e($app->email); ?></td>
                    <td><?php echo e($app->phone); ?></td>
                    <td><?php echo e($app->specialite); ?></td>
                    <td><?php echo e($app->ville); ?></td>
                    <td><?php echo e($app->langue === 'fr' ? 'Français' : 'Anglais'); ?></td>
                    <td><?php echo e($app->lieu_travail); ?></td>
                    <td><?php echo e($app->matricule_professionnel); ?></td>
                    <td><?php echo e($app->numero_licence); ?></td>
                    <td><?php echo e($app->experience); ?></td>
                    <td><?php echo e($app->expertise); ?></td>
                    <td>
                        <?php if($app->cv): ?>
                            <a href="<?php echo e(asset('storage/' . $app->cv)); ?>" target="_blank" class="btn btn-outline-primary">
                                Télécharger le CV
                            </a>
                        <?php else: ?>
                            <span class="text-muted">Aucun CV disponible</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($app->approved_at): ?>
                        <?php echo e(\Carbon\Carbon::parse($app->approved_at)->format('d/m/Y à H:i')); ?>


                        <?php else: ?>
                        <?php
                            $userExists = in_array($app->email, $users);
                        ?>

                        <?php if($app->status === 'approved' && !$userExists): ?>
                            <span class="text-warning">Compte non encore créé</span>
                            <form action="<?php echo e(route('admin.doctor_applications.create_user', $app->id)); ?>" method="POST" style="display:inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-outline-light btn-sm">Créer le compte</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">Aucune action disponible</span>
                        <?php endif; ?>
                    <?php endif; ?>

                    </td>

                    <td>
                        <?php if($app->status === 'approved'): ?>
                            <span class="badge bg-success">Approuvée</span>
                        <?php elseif($app->status === 'rejected'): ?>
                            <span class="badge bg-danger">Rejetée</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">En attente</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($app->status === 'pending'): ?>
                            <form action="<?php echo e(route('admin.doctor_applications.approve', $app->id)); ?>" method="POST" style="display:inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-success btn-sm" type="submit">✅ Approuver</button>
                            </form>
                            <form action="<?php echo e(route('admin.doctor_applications.reject', $app->id)); ?>" method="POST" style="display:inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-danger btn-sm" type="submit">❌ Rejeter</button>
                            </form>
                        <?php else: ?>
                            <span class="text-muted">Aucune action disponible</span>
                        <?php endif; ?>
                    </td>

                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <form action="<?php echo e(route('admin.doctor_applications.deleteRejected')); ?>" method="POST" onsubmit="return confirm('Confirmer la suppression des demandes rejetées ?');">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
        <!-- Bouton pour ouvrir le modal -->
<button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
    🗑️ Supprimer les demandes rejetées
</button>

    </form>
<!-- Modal de confirmation de suppression -->
<div class="modal fade " id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark text-white modal-transparent">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          ⚠️ Cette action supprimera <strong>toutes les demandes rejetées</strong> de façon permanente. Voulez-vous continuer ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <!-- Formulaire de suppression -->
          <form action="<?php echo e(route('admin.doctor_applications.deleteRejected')); ?>" method="POST">
              <?php echo csrf_field(); ?>
              <?php echo method_field('DELETE'); ?>
              <button type="submit" class="btn btn-danger">Oui, supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark text-white modal-transparent">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          ⚠️ Cette action supprimera <strong>toutes les demandes rejetées</strong> de façon permanente. Voulez-vous continuer ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <!-- Formulaire de suppression -->
          <form action="<?php echo e(route('admin.doctor_applications.deleteRejected')); ?>" method="POST">
              <?php echo csrf_field(); ?>
              <?php echo method_field('DELETE'); ?>
              <button type="submit" class="btn btn-danger">Oui, supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <style>
      .modal-transparent {
          background-color: rgba(33, 37, 41, 0.121); /* noir transparent */
          backdrop-filter: blur(6px); /* effet de flou subtil */
          border-radius: 10px;
      }
  </style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/admin/doctor_applications/index.blade.php ENDPATH**/ ?>