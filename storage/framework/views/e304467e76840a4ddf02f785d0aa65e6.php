<?php $__env->startSection('title', 'Mes ordonnances'); ?>

<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Mes ordonnances</h1>
                <div class="flex space-x-2">
                    <!-- Bouton de recherche avancée -->
                    <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filtres
                    </button>
                </div>
            </div>

            <?php if($prescriptions->isEmpty()): ?>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Vous n'avez aucune ordonnance pour le moment.
                            </p>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white shadow overflow-hidden sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $prescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="h-6 w-6 text-gray-400 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                            <?php echo e($prescription->titre); ?>

                                        </p>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex space-x-2">
                                        <?php if($prescription->fichier_pdf): ?>
                                        <a href="<?php echo e(route('patient.ordonnances.download', $prescription->id)); ?>" 
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                           title="Télécharger le PDF">
                                            <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            PDF
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php if($prescription->fichiers->count() > 0): ?>
                                            <div class="relative group">
                                                <button class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg class="h-4 w-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                    Fichiers (<?php echo e($prescription->fichiers->count()); ?>)
                                                </button>
                                                <div class="absolute right-0 mt-2 w-64 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-10 hidden group-hover:block">
                                                    <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100">Fichiers joints</div>
                                                    <?php $__currentLoopData = $prescription->fichiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fichier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="px-4 py-2 hover:bg-gray-50">
                                                        <div class="flex items-center justify-between">
                                                            <span class="truncate text-sm"><?php echo e($fichier->nom_original); ?></span>
                                                            <div class="ml-2 flex-shrink-0 flex space-x-1">
                                                                <a href="<?php echo e(route('patient.ordonnances.fichiers.afficher', $fichier->id)); ?>" 
                                                                   class="text-indigo-600 hover:text-indigo-900"
                                                                   target="_blank"
                                                                   title="Voir le fichier">
                                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                    </svg>
                                                                </a>
                                                                <a href="<?php echo e(route('patient.ordonnances.fichiers.telecharger', $fichier->id)); ?>" 
                                                                   class="text-indigo-600 hover:text-indigo-900"
                                                                   title="Télécharger">
                                                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            <?php echo e(number_format($fichier->taille / 1024, 1)); ?> KB • <?php echo e(strtoupper($fichier->extension)); ?>

                                                        </div>
                                                    </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo e(route('patient.ordonnances.show', $prescription->id)); ?>" 
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Détails
                                        </a>
                                    </div>
                                </div>
                                
                                <?php if($prescription->description): ?>
                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                    <?php echo e($prescription->description); ?>

                                </p>
                                <?php endif; ?>
                                
                                <div class="mt-3 flex flex-wrap items-center text-xs text-gray-500 gap-4">
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Dr. <?php echo e($prescription->medecin->first_name ?? ''); ?> <?php echo e($prescription->medecin->last_name ?? $prescription->medecin->name); ?>

                                    </div>
                                    
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        Le <?php echo e(\Carbon\Carbon::parse($prescription->date_prescription)->format('d/m/Y')); ?>

                                    </div>
                                    
                                    <?php if($prescription->date_expiration): ?>
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Expire le <?php echo e(\Carbon\Carbon::parse($prescription->date_expiration)->format('d/m/Y')); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>

                <!-- Pagination -->
                <?php if($prescriptions->hasPages()): ?>
                <div class="mt-6">
                    <?php echo e($prescriptions->links()); ?>

                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .group:hover .group-hover\:block {
        display: block;
    }
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/patient/ordonnances/index.blade.php ENDPATH**/ ?>