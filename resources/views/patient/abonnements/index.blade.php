<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vos Abonnements') }}
        </h2>
    </x-slot>

    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos Abonnements - Packs Santé</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .hero-section {
            background: var(--primary-gradient);
            color: white;
            padding: 4rem 0 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .card-modern {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .card-modern:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .card-popular {
            border: 3px solid transparent;
            background: linear-gradient(white, white) padding-box, var(--primary-gradient) border-box;
            position: relative;
            overflow: visible;
        }
        
        .card-popular::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: var(--primary-gradient);
            border-radius: 20px;
            z-index: -1;
        }
        
        .popular-badge {
            background: var(--secondary-gradient);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.8rem;
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 10;
        }
        
        .price-display {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8ecff 100%);
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }
        
        .price-number {
            font-size: 3rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        
        .feature-item:last-child {
            border-bottom: none;
        }
        
        .feature-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 0.8rem;
        }
        
        .feature-icon.available {
            background: var(--success-gradient);
            color: white;
        }
        
        .feature-icon.unavailable {
            background: #f8f9fa;
            color: #6c757d;
        }
        
        .btn-modern {
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            text-transform: none;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary-modern {
            background: var(--primary-gradient);
            color: white;
        }
        
        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-outline-modern {
            background: transparent;
            border: 2px solid;
            border-image: var(--primary-gradient) 1;
            color: #667eea;
        }
        
        .btn-outline-modern:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
        }
        
        .benefits-section {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-top: 4rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .benefit-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            background: white;
            border-radius: 12px;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        
        .benefit-item:hover {
            transform: translateX(5px);
        }
        
        .benefit-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
        }
        
        .animate-fade-in {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 0 1rem;
            }
            .price-number {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container hero-content">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">Nos Packs Santé</h1>
                    <p class="lead mb-0">Choisissez la formule adaptée à vos besoins pour un suivi dermatologique de qualité</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Cards -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4 justify-content-center" style="margin-top: -2rem;">
                <!-- Pack Découverte -->
                <div class="col-lg-4 col-md-6">
                    <div class="card card-modern h-100 animate-fade-in animate-delay-1">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <h3 class="h4 fw-bold mb-3">Pack Découverte</h3>
                                <div class="price-display">
                                    <div class="price-number">Gratuit</div>
                                    <small class="text-muted">Pour commencer</small>
                                </div>
                            </div>
                            
                            <ul class="feature-list">
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Consultations de base limitées</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Accès à votre dossier médical</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Notifications santé par email</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon unavailable">
                                        <i class="bi bi-x-lg"></i>
                                    </div>
                                    <span class="text-muted">Téléconsultations vidéo</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon unavailable">
                                        <i class="bi bi-x-lg"></i>
                                    </div>
                                    <span class="text-muted">Support prioritaire</span>
                                </li>
                            </ul>
                            
                            <div class="mt-4">
                                <button class="btn btn-primary-modern btn-modern w-100 ">
                                    <i class="bi bi-person-plus me-2"></i>S'inscrire
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pack Premium -->
                <div class="col-lg-4 col-md-6">
                    <div class="card card-modern card-popular h-100 animate-fade-in animate-delay-2" style="position: relative;">
                        <div class="popular-badge">
                            <i class="bi bi-star-fill me-1"></i>Populaire
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <h3 class="h4 fw-bold mb-3">Pack Premium</h3>
                                <div class="price-display">
                                    <div class="d-flex align-items-baseline justify-content-center">
                                        <span class="price-number">5000 fcfa</span>
                                        <span class="text-muted ms-2">/mois</span>
                                    </div>
                                    <small class="text-muted">Le plus choisi</small>
                                </div>
                            </div>
                            
                            <ul class="feature-list">
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Consultations spécialisées illimitées</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Téléconsultations vidéo incluses</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Accès à tous les documents médicaux</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Support prioritaire 7j/7</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Suivi santé personnalisé</span>
                                </li>
                            </ul>
                            
                            <div class="mt-4">
                                <a href="{{ route('patient.paiement.form') }}?pack=premium&montant=5000" class="btn btn-primary-modern btn-modern w-100">
    <i class="bi bi-crown me-2"></i>Souscrire Maintenant
</a>
                            </div>
                        </div> 
                    </div>
                </div>

                <!-- Pack Expert Santé -->
                <div class="col-lg-4 col-md-6">
                    <div class="card card-modern h-100 animate-fade-in animate-delay-3">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <h3 class="h4 fw-bold mb-3">Pack Expert Santé</h3>
                                <div class="price-display">
                                    <div class="d-flex align-items-baseline justify-content-center">
                                        <span class="price-number">10000 fcfa</span>
                                        <span class="text-muted ms-2">/mois</span>
                                    </div>
                                    <small class="text-muted">Solution complète</small>
                                </div>
                            </div>
                            
                            <ul class="feature-list">
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Accès complet à toutes les spécialités</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Support 24/7 avec médecins certifiés</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Suivi patient avancé</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Archivage sécurisé des dossiers</span>
                                </li>
                                <li class="feature-item">
                                    <div class="feature-icon available">
                                        <i class="bi bi-check-lg"></i>
                                    </div>
                                    <span>Assistance administrative médicale</span>
                                </li>
                            </ul>
                            
                            <div class="mt-4">
                                <button class="btn btn-primary-modern btn-modern w-100">
                                    <i class="bi bi-gem me-2"></i>Souscrire
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="benefits-section">
                        <h3 class="text-center fw-bold mb-4">Pourquoi choisir nos packs santé ?</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="benefit-item">
                                    <div class="benefit-icon">
                                        <i class="bi bi-lightning-charge"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Accès rapide</h6>
                                        <small class="text-muted">Consultation avec un dermatologue en moins de 24h</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="benefit-item">
                                    <div class="benefit-icon">
                                        <i class="bi bi-shield-check"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Suivi sécurisé</h6>
                                        <small class="text-muted">Vos données médicales protégées et cryptées</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="benefit-item">
                                    <div class="benefit-icon">
                                        <i class="bi bi-gift"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Offres exclusives</h6>
                                        <small class="text-muted">Avantages et réductions réservés aux abonnés</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="benefit-item">
                                    <div class="benefit-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Créneaux prioritaires</h6>
                                        <small class="text-muted">Réservation en priorité des meilleurs créneaux</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-fade-in').forEach(el => {
            observer.observe(el);
        });

        // Add subtle parallax effect to hero
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            const heroSection = document.querySelector('.hero-section');
            if (heroSection) {
                heroSection.style.transform = `translateY(${rate}px)`;
            }
        });
    </script>
    <!-- Modal Souscription Pack Premium -->
    <div class="modal fade" id="modalSouscriptionPremium" tabindex="-1" aria-labelledby="modalSouscriptionPremiumLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <form method="POST" action="#" id="formSouscriptionPremium">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="modalSouscriptionPremiumLabel">Souscription au Pack Premium</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="medecin_id" class="form-label">Choix du médecin référent <span class="text-danger">*</span></label>
                <select class="form-select" id="medecin_id" name="medecin_id" required>
                  <option value="">Sélectionner un médecin</option>
                  @foreach($medecins as $medecin)
                    <option value="{{ $medecin->id }}">{{ $medecin->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Numéro de téléphone <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="phone" name="phone" value="{{ auth()->user()->phone ?? '' }}" required pattern="[0-9]{8,15}" placeholder="Ex : 690123456">
              </div>
              <div class="mb-3">
                <label class="form-label">Montant à prélever</label>
                <div class="form-control-plaintext fw-bold fs-5">5000 fcfa</div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="submit" class="btn btn-primary">Confirmer la souscription</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    </body>
</html>
</x-app-layout>