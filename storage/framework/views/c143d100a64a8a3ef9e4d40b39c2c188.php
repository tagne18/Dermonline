<?php $__env->startSection('content'); ?>
<div class="container my-5">
    <h2 class="mb-4">Mes résultats d'examens</h2>
    <?php if($examResults->isEmpty()): ?>
        <div class="alert alert-info">Aucun résultat d'examen disponible.</div>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Médecin</th>
                    <th>Date</th>
                    <th>Fichier</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $examResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($exam->titre); ?></td>
                    <td><?php echo e($exam->medecin ? $exam->medecin->name : '-'); ?></td>
                    <td><?php echo e($exam->date_examen); ?></td>
                    <td>
                        <?php if($exam->fichier): ?>
                            <a href="<?php echo e(route('patient.examens.download', $exam->id)); ?>" class="btn btn-sm btn-outline-primary">Télécharger</a>
                        <?php else: ?>
                            <span class="text-muted">Aucun</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(route('patient.examens.show', $exam->id)); ?>" class="btn btn-sm btn-info">Détail</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/patient/examens/index.blade.php ENDPATH**/ ?>