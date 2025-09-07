<?php $__env->startSection('content'); ?>
    <h1>Messages de la communauté</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('medecin.messages.store')); ?>" class="mb-4">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <textarea name="contenu" class="form-control" placeholder="Votre message..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>

    <ul class="list-group">
        <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li class="list-group-item">
                <strong><?php echo e($message->user->name ?? 'Utilisateur'); ?> :</strong>
                <?php echo e($message->contenu); ?>

                <br>
                <small class="text-muted"><?php echo e($message->created_at->diffForHumans()); ?></small>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="list-group-item">Aucun message pour le moment.</li>
        <?php endif; ?>
    </ul>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.medecin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/medecin/messages/index.blade.php ENDPATH**/ ?>