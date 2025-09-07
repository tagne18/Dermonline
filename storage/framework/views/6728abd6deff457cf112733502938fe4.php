<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Dermonline | Consultation Dermatologique en Ligne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('style/welcome.css')); ?>">
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --secondary-color: #10b981;
            --accent-color: #f59e0b;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f8fafc;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --shadow-sm: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .video-bg {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            object-fit: cover;
            z-index: 0;
        }
        
        .overlay-bg {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(20, 30, 60, 0.6);
            z-index: 1;
        }
        
        .register-content {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }

        .register-header {
            background: var(--bg-gradient);
            color: white;
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            position: relative;
        }

        .register-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle cx="200" cy="200" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="800" cy="300" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="600" cy="600" r="2.5" fill="rgba(255,255,255,0.12)"/></svg>');
            animation: float 6s ease-in-out infinite;
        }

        .register-header-content {
            position: relative;
            z-index: 2;
        }

        .logo-large {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .register-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .register-body {
            padding: 2.5rem 2rem;
            max-height: 70vh;
            overflow-y: auto;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating .form-control,
        .form-floating .form-select {
            border: 2px solid rgba(0,0,0,0.1);
            border-radius: 15px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.8);
        }

        .form-floating .form-control:focus,
        .form-floating .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
            background: white;
        }

        .form-floating label {
            padding: 1rem 1.25rem;
            color: var(--text-light);
        }

        .form-text {
            font-size: 0.85rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        .btn-register {
            background: var(--bg-gradient);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-register::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        .btn-login {
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            text-decoration: none;
            display: inline-block;
        }

        .btn-login:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.5);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: rgba(0,0,0,0.1);
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }

        .benefits-list li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: rgba(59, 130, 246, 0.05);
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .benefits-list i {
            color: var(--primary-color);
            margin-right: 1rem;
            font-size: 1.2rem;
        }

        .alert {
            border-radius: 15px;
            border: none;
            padding: 1rem 1.5rem;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .back-to-home {
            position: fixed;
            top: 2rem;
            left: 2rem;
            z-index: 10;
            background: rgba(255,255,255,0.9);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .back-to-home:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .progress-bar {
            height: 4px;
            background: rgba(59, 130, 246, 0.2);
            border-radius: 2px;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--bg-gradient);
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        @media (max-width: 768px) {
            .register-content {
                padding: 1rem;
            }
            
            .register-card {
                margin: 0 1rem;
            }
            
            .back-to-home {
                top: 1rem;
                left: 1rem;
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            
            .register-body {
                max-height: 60vh;
            }
        }
    </style>
</head>
<body>
    <!-- Vidéo de fond -->
    <video class="video-bg" autoplay muted loop playsinline poster="https://images.pexels.com/videos/5452209/free-video-5452209.jpg">
        <source src="https://videos.pexels.com/video-files/5452209/5452209-hd_1920_1080_25fps.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="overlay-bg"></div>

    <!-- Bouton retour -->
    <a href="<?php echo e(route('welcome')); ?>" class="back-to-home">
        <i class="bi bi-arrow-left me-2"></i>Retour à l'accueil
    </a>

    <!-- Contenu principal -->
    <div class="register-content">
        <div class="register-card animate-fade-in-up">
            <!-- En-tête -->
            <div class="register-header">
                <div class="register-header-content">
                    <div class="logo-large">Dermonline</div>
                    <p class="register-subtitle">Rejoignez notre communauté de patients</p>
                </div>
            </div>

            <!-- Corps du formulaire -->
            <div class="register-body">
                <!-- Barre de progression -->
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
            </div>

                <!-- Messages d'erreur/succès -->
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
            </div>
                <?php endif; ?>

                <!-- Formulaire d'inscription -->
                <form method="POST" action="<?php echo e(route('register')); ?>" id="registerForm">
                    <?php echo csrf_field(); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Nom" value="<?php echo e(old('last_name')); ?>" required>
                                <label for="last_name">
                                    <i class="bi bi-person me-2"></i>Nom
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Prénom" value="<?php echo e(old('first_name')); ?>" required>
                                <label for="first_name">
                                    <i class="bi bi-person me-2"></i>Prénom
                                </label>
            </div>
            </div>
            </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Téléphone" value="<?php echo e(old('phone')); ?>" required>
                                <label for="phone">
                                    <i class="bi bi-telephone me-2"></i>Téléphone
                                </label>
                            </div>
            </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" id="gender" name="gender" required>
                    <option value="">-- Sélectionnez --</option>
                    <option value="homme" <?php if(old('gender') === 'homme'): echo 'selected'; endif; ?>>Homme</option>
                    <option value="femme" <?php if(old('gender') === 'femme'): echo 'selected'; endif; ?>>Femme</option>
                </select>
                                <label for="gender">
                                    <i class="bi bi-gender-ambiguous me-2"></i>Sexe
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo e(old('email')); ?>" required>
                        <label for="email">
                            <i class="bi bi-envelope me-2"></i>Adresse email
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="birth_date" name="birth_date" placeholder="Date de naissance" value="<?php echo e(old('birth_date')); ?>" required>
                                <label for="birth_date">
                                    <i class="bi bi-calendar me-2"></i>Date de naissance
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="city" name="city" placeholder="Ville" value="<?php echo e(old('city')); ?>" required>
                                <label for="city">
                                    <i class="bi bi-geo-alt me-2"></i>Ville
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control" id="profession" name="profession" placeholder="Profession" value="<?php echo e(old('profession')); ?>" required>
                        <label for="profession">
                            <i class="bi bi-briefcase me-2"></i>Profession
                        </label>
                    </div>

                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
                        <label for="password">
                            <i class="bi bi-lock me-2"></i>Mot de passe
                        </label>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>Minimum 8 caractères, une lettre, un chiffre et un caractère spécial.
                        </div>
            </div>

                    <div class="form-floating">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirmation du mot de passe" required>
                        <label for="password_confirmation">
                            <i class="bi bi-lock-fill me-2"></i>Confirmation du mot de passe
                        </label>
            </div>

                    <button type="submit" class="btn btn-register w-100 mb-3">
                        <i class="bi bi-person-plus me-2"></i>Créer mon compte
                    </button>

                    <div class="divider">
                        <span>ou</span>
            </div>

                    <a href="<?php echo e(route('login')); ?>" class="btn btn-login w-100 text-center text-dark">
                        <i class="bi bi-box-arrow-in-left me-2 text-dark"></i>Déjà inscrit ? Se connecter
                    </a>
                </form>

                <!-- Avantages -->
                <div class="mt-4">
                    <h6 class="text-center mb-3" style="color: var(--text-light);">
                        <i class="bi bi-star me-2"></i>Avantages de l'inscription
                    </h6>
                    <ul class="benefits-list">
                        <li>
                            <i class="bi bi-shield-check"></i>
                            <span>Accès sécurisé à votre dossier médical</span>
                        </li>
                        <li>
                            <i class="bi bi-calendar-check"></i>
                            <span>Réservation de consultations en ligne</span>
                        </li>
                        <li>
                            <i class="bi bi-chat-dots"></i>
                            <span>Communication directe avec les médecins</span>
                        </li>
                        <li>
                            <i class="bi bi-file-medical"></i>
                            <span>Historique complet de vos soins</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation de la barre de progression
        const form = document.getElementById('registerForm');
        const progressFill = document.querySelector('.progress-fill');
        const inputs = form.querySelectorAll('input, select');

        function updateProgress() {
            const filledInputs = Array.from(inputs).filter(input => input.value.trim() !== '').length;
            const progress = (filledInputs / inputs.length) * 100;
            progressFill.style.width = progress + '%';
        }

        inputs.forEach(input => {
            input.addEventListener('input', updateProgress);
            input.addEventListener('change', updateProgress);
        });

        // Initialiser la progression
        updateProgress();
    </script>
</body>
</html>
<?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/auth/register.blade.php ENDPATH**/ ?>