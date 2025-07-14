<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de création de compte médecin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --medical-blue: #2563eb;
            --medical-teal: #0d9488;
            --medical-green: #059669;
            --medical-light-blue: #e0f2fe;
            --medical-light-green: #f0fdfa;
            --medical-accent: #06b6d4;
            --medical-gray: #64748b;
            --medical-dark: #1e293b;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--medical-light-blue) 0%, var(--medical-light-green) 50%, #f8fafc 100%);
            min-height: 100vh;
        }

        .medical-navbar {
            background: linear-gradient(90deg, var(--medical-blue) 0%, var(--medical-teal) 100%);
            border-bottom: 3px solid var(--medical-accent);
            backdrop-filter: blur(10px);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            color: var(--medical-light-blue) !important;
            transform: translateX(-5px);
        }

        .main-container {
            padding: 3rem 0;
            position: relative;
        }

        .medical-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: none;
            border-radius: 24px;
            box-shadow: 
                0 20px 40px rgba(37, 99, 235, 0.1),
                0 8px 16px rgba(13, 148, 136, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            overflow: hidden;
            position: relative;
        }

        .medical-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--medical-blue), var(--medical-teal), var(--medical-green));
        }

        .header-section {
            background: linear-gradient(135deg, var(--medical-light-blue) 0%, rgba(255, 255, 255, 0.8) 100%);
            padding: 2rem;
            margin: -2rem -2rem 2rem -2rem;
            border-radius: 24px 24px 0 0;
            text-align: center;
            position: relative;
        }

        .medical-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--medical-blue), var(--medical-teal));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.3);
        }

        .medical-logo i {
            font-size: 2.5rem;
            color: white;
        }

        .form-title {
            background: linear-gradient(135deg, var(--medical-blue), var(--medical-teal));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: var(--medical-gray);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            color: var(--medical-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: var(--medical-teal);
            font-size: 1rem;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--medical-teal);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
            background: white;
        }

        .required-field::after {
            content: ' *';
            color: #ef4444;
            font-weight: bold;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--medical-blue) 0%, var(--medical-teal) 100%);
            border: none;
            border-radius: 16px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.3);
        }

        .medical-decoration {
            position: absolute;
            opacity: 0.05;
            font-size: 8rem;
            color: var(--medical-teal);
            z-index: 1;
        }

        .decoration-1 {
            top: 10%;
            right: -5%;
            transform: rotate(15deg);
        }

        .decoration-2 {
            bottom: 10%;
            left: -5%;
            transform: rotate(-15deg);
        }

        .error-alert {
            background: linear-gradient(135deg, #fef2f2, #fecaca);
            border: 1px solid #fca5a5;
            border-radius: 12px;
            color: #dc2626;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: -2s;
        }

        .floating-element:nth-child(2) {
            top: 60%;
            right: 10%;
            animation-delay: -4s;
        }

        .floating-element:nth-child(3) {
            bottom: 20%;
            left: 20%;
            animation-delay: -1s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 1.5rem 0;
            }
            
            .medical-card {
                margin: 1rem;
                border-radius: 16px;
            }
            
            .header-section {
                padding: 1.5rem;
                margin: -1.5rem -1.5rem 1.5rem -1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Éléments flottants décoratifs -->
    <div class="floating-elements">
        <i class="bi bi-heart-pulse floating-element" style="font-size: 3rem; color: var(--medical-teal);"></i>
        <i class="bi bi-shield-plus floating-element" style="font-size: 2.5rem; color: var(--medical-blue);"></i>
        <i class="bi bi-hospital floating-element" style="font-size: 2rem; color: var(--medical-green);"></i>
    </div>

    <!-- Barre de navigation -->
    <nav class="navbar medical-navbar shadow-lg">
        <div class="container">
            <a class="navbar-brand" href="#" onclick="goBack()">
                <i class="bi bi-arrow-left me-2"></i>
                <span>Retour à l'accueil</span>
            </a>
        </div>
    </nav>

    <div class="container main-container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                <div class="card medical-card p-4 position-relative">
                    <!-- Décorations médicales -->
                    <i class="bi bi-heart-pulse-fill medical-decoration decoration-1"></i>
                    <i class="bi bi-shield-plus-fill medical-decoration decoration-2"></i>

                    <!-- En-tête -->
                    <div class="header-section">
                        <div class="medical-logo">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h1 class="form-title">Demande de création de compte médecin</h1>
                        <p class="form-subtitle">Rejoignez notre plateforme médicale professionnelle</p>
                    </div>

                    <!-- Formulaire -->
                    <form action="{{ route('doctor_application.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Affichage des erreurs de validation -->
                        @if ($errors->any())
                            <div class="error-alert" id="errorAlert">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div>
                                        <strong>Erreurs détectées :</strong>
                                        <ul class="mb-0 mt-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Affichage du message de succès -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label required-field">
                                        <i class="bi bi-person"></i>
                                        Nom complet
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label required-field">
                                        <i class="bi bi-envelope"></i>
                                        Adresse e-mail
                                    </label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">
                                        <i class="bi bi-telephone"></i>
                                        Téléphone
                                    </label>
                                    <input type="tel" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="specialite" class="form-label required-field">
                                        <i class="bi bi-heart-pulse"></i>
                                        Spécialité médicale
                                    </label>
                                    <input type="text" name="specialite" id="specialite" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ville" class="form-label required-field">
                                        <i class="bi bi-geo-alt"></i>
                                        Ville
                                    </label>
                                    <input type="text" name="ville" id="ville" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="langue" class="form-label required-field">
                                        <i class="bi bi-translate"></i>
                                        Langue de pratique
                                    </label>
                                    <select name="langue" id="langue" class="form-select" required>
                                        <option value="">-- Sélectionnez --</option>
                                        <option value="fr">Français</option>
                                        <option value="en">Anglais</option>
                                        <option value="both">Français et Anglais</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="lieu_travail" class="form-label">
                                <i class="bi bi-building"></i>
                                Lieu de travail / Établissement
                            </label>
                            <input type="text" name="lieu_travail" id="lieu_travail" class="form-control">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="matricule_professionnel" class="form-label">
                                        <i class="bi bi-card-text"></i>
                                        Matricule professionnel
                                    </label>
                                    <input type="text" name="matricule_professionnel" id="matricule_professionnel" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero_licence" class="form-label">
                                        <i class="bi bi-award"></i>
                                        Numéro de licence
                                    </label>
                                    <input type="text" name="numero_licence" id="numero_licence" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="experience" class="form-label required-field">
                                        <i class="bi bi-clock-history"></i>
                                        Expérience professionnelle
                                    </label>
                                    <select name="experience" id="experience" class="form-select" required>
                                        <option value="">-- Sélectionnez --</option>
                                        <option value="1 an">1 an</option>
                                        <option value="2 ans">2 ans</option>
                                        <option value="3 ans">3 ans</option>
                                        <option value="5 ans">5 ans</option>
                                        <option value="10 ans">10 ans</option>
                                        <option value="15 ans et plus">15 ans et plus</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="expertise" class="form-label">
                                        <i class="bi bi-mortarboard"></i>
                                        Domaine d'expertise
                                    </label>
                                    <input type="text" name="expertise" id="expertise" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="cv" class="form-label required-field">
                                <i class="bi bi-file-earmark-pdf"></i>
                                Curriculum Vitae (PDF uniquement)
                            </label>
                            <input type="file" name="cv" id="cv" class="form-control" accept="application/pdf" required>
                            <small class="text-muted mt-1 d-block">
                                <i class="bi bi-info-circle me-1"></i>
                                Taille maximale : 5 MB
                            </small>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="submit-btn">
                                <i class="bi bi-send me-2"></i>
                                Soumettre ma demande
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="bi bi-shield-check me-1"></i>
                                Vos informations sont sécurisées et confidentielles
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }

        // Animation des champs au focus
        document.querySelectorAll('.form-control, .form-select').forEach(field => {
            field.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });
            
            field.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Validation basique du formulaire
        document.querySelector('form').addEventListener('submit', function(e) {
            // Ne pas empêcher la soumission, laisser Laravel gérer la validation
            const submitBtn = document.querySelector('.submit-btn');
            const originalContent = submitBtn.innerHTML;
            
            // Afficher un indicateur de chargement
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Envoi en cours...';
            submitBtn.disabled = true;
            
            // Réactiver le bouton après 5 secondes au cas où
            setTimeout(() => {
                submitBtn.innerHTML = originalContent;
                submitBtn.disabled = false;
            }, 5000);
        });
    </script>
</body>
</html>