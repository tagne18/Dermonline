<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo $__env->yieldContent('title', 'Espace Médecin'); ?></title>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-light text-dark" x-data="{ open: true }">
    <div class="d-flex min-vh-100">
        <!-- Sidebar -->
        <aside class="bg-primary text-white p-3 position-fixed h-100" :class="open ? 'd-block' : 'd-none d-md-block'" style="width: 250px; overflow-y: auto;">
            <div class="d-flex flex-column align-items-center mb-4">
    <div style="width:74px;height:74px;position:relative;border-radius:50%;overflow:hidden;border:3px solid #fff;box-shadow:0 2px 10px rgba(36,180,126,0.13);background:#fff;margin-bottom:8px;">
    <img src="<?php echo e(Auth::user()->profile_photo_url ?? asset('images/avatar-medecin.png')); ?>" alt="Photo de profil" style="width:100%;height:100%;object-fit:cover;">
    <span style="position:absolute;bottom:4px;right:4px;width:18px;height:18px;
background:<?php echo e(isset($isOnline) && $isOnline ? '#22c55e' : '#ef4444'); ?>;
border:2px solid #fff;border-radius:50%;
box-shadow:0 0 2px <?php echo e(isset($isOnline) && $isOnline ? '#22c55e' : '#ef4444'); ?>;display:block;"></span>
</div>
    <div class="d-flex justify-content-between align-items-center w-100">
        <h2 class="fs-5 mb-0">👨‍⚕️ Dr <?php echo e(Auth::user()->name); ?></h2>
        <button class="btn btn-sm btn-light d-md-none" @click="open = false">✖️</button>
    </div>
</div>

            <nav class="nav flex-column gap-2">
                <a href="<?php echo e(route('medecin.dashboard')); ?>" class="nav-link text-white">
                    <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                </a>
                <a href="<?php echo e(route('medecin.profile.show')); ?>" class="nav-link text-white">
                    <i class="fas fa-user-cog me-2"></i>Mon Profil
                </a>
                <a href="<?php echo e(route('medecin.abonnements.index')); ?>" class="nav-link text-white">
                    <i class="fas fa-id-card me-2"></i>Mes Abonnements
                </a>
                <a href="<?php echo e(route('medecin.consultations.index')); ?>" class="nav-link text-white">
                    <i class="fas fa-stethoscope me-2"></i>Consultations
                </a>
                <a href="<?php echo e(route('medecin.dossiers.index')); ?>" class="nav-link text-white">
                    <i class="fas fa-folder me-2"></i>Dossiers Médicaux
                </a>
                <a href="<?php echo e(route('medecin.ordonnances.index')); ?>" class="nav-link text-white">
                    <i class="fas fa-prescription me-2"></i>Ordonnances
                </a>
                <a href="<?php echo e(route('medecin.examens.index')); ?>" class="nav-link text-white">
                    <i class="fas fa-microscope me-2"></i>Examens
                </a>
                <a href="<?php echo e(route('medecin.new-annonces.index')); ?>" class="nav-link text-white">
                    <i class="fas fa-bullhorn me-2"></i>Annonces
                </a>
                <a href="<?php echo e(route('medecin.annonces.index')); ?>" class="nav-link text-white">
                    <i class="fas fa-calendar-alt me-2"></i>Rendez-vous
                </a>
                <a href="<?php echo e(route('medecin.messages.index')); ?>" class="nav-link text-white">
                    <i class="fas fa-comments me-2"></i>Messages
                </a>
                
                <a href="<?php echo e(route('medecin.planning.index')); ?>" class="nav-link text-white">
                    <i class="fas fa-calendar-plus me-2"></i>Mon Planning
                </a>

                <a href="<?php echo e(route('medecin.new-annonces.create')); ?>" class="btn btn-warning text-white w-100 mt-3">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle annonce
                </a>

                <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger w-100">🔓 Déconnexion</button>
                </form>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="flex-grow-1 p-4 bg-white" style="margin-left: 250px; width: calc(100% - 250px);">
            <!-- Toggle sidebar button -->
            <button class="btn btn-outline-primary mb-3 d-md-none" @click="open = true">📂 Menu</button>

            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <?php echo $__env->make('components.chat.community', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<!-- Bouton flottant IA -->
<?php echo $__env->make('components.chat.ia', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->yieldPushContent('scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/layouts/medecin.blade.php ENDPATH**/ ?>