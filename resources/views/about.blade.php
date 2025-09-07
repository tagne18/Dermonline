<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - Dermonline | Consultation Dermatologique en Ligne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style/welcome.css') }}">
    <style>
        .hero-about {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .hero-about::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle cx="200" cy="200" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="800" cy="300" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="600" cy="600" r="2.5" fill="rgba(255,255,255,0.12)"/></svg>');
            animation: float 6s ease-in-out infinite;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .feature-card:hover::before {
            transform: scaleX(1);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
        
        .stats-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 80px 0;
        }
        
        .stat-card {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        .timeline {
            position: relative;
            padding: 2rem 0;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: translateX(-50%);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 3rem;
        }
        
        .timeline-content {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
            width: 45%;
        }
        
        .timeline-item:nth-child(odd) .timeline-content {
            margin-left: 0;
        }
        
        .timeline-item:nth-child(even) .timeline-content {
            margin-left: 55%;
        }
        
        .timeline-dot {
            position: absolute;
            left: 50%;
            top: 2rem;
            width: 20px;
            height: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            transform: translateX(-50%);
            z-index: 2;
        }
        
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
        }
        
        .testimonial-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            border: 4px solid #667eea;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        
        .btn-glow {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .btn-glow:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="{{ route('welcome') }}">
                <span class="text-primary">Derm</span>online
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcome') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('about') }}">À propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-about">
        <div class="container position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center text-white">
                    <h1 class="display-4 fw-bold mb-4">Découvrez Dermonline</h1>
                    <p class="lead mb-4">La plateforme qui révolutionne l'accès aux soins dermatologiques en Afrique</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('doctor-application.create') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-person-plus me-2"></i>Devenir Médecin
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-arrow-right me-2"></i>Commencer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-6 fw-bold mb-4">Notre Mission</h2>
                    <p class="lead text-muted mb-4">
                        Démocratiser l'accès aux soins dermatologiques de qualité en Afrique, 
                        en connectant patients et dermatologues certifiés via une plateforme 
                        innovante et sécurisée.
                    </p>
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-heart-pulse text-primary fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Santé accessible</h5>
                                    <small class="text-muted">Pour tous, partout</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="bi bi-shield-check text-success fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Qualité garantie</h5>
                                    <small class="text-muted">Experts certifiés</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=600&h=400&fit=crop" 
                         alt="Mission" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold mb-3">Pourquoi choisir Dermonline ?</h2>
                <p class="lead text-muted">Une expérience unique alliant technologie et expertise médicale</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <h4>Disponibilité 24/7</h4>
                        <p class="text-muted">Accédez à des consultations dermatologiques à tout moment, 
                        même en dehors des heures de bureau traditionnelles.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <h4>Géolocalisation intelligente</h4>
                        <p class="text-muted">Trouvez les dermatologues les plus proches de vous 
                        et bénéficiez d'un suivi local si nécessaire.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h4>Sécurité maximale</h4>
                        <p class="text-muted">Vos données médicales sont protégées par un cryptage 
                        de niveau bancaire et des protocoles de sécurité stricts.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h4>Équipe multilingue</h4>
                        <p class="text-muted">Nos dermatologues parlent français, anglais et langues locales 
                        pour une communication optimale.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h4>Suivi personnalisé</h4>
                        <p class="text-muted">Bénéficiez d'un suivi médical personnalisé avec historique 
                        complet et recommandations adaptées.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <h4>Tarifs transparents</h4>
                        <p class="text-muted">Des prix clairs et abordables, sans surprise. 
                        Payez uniquement pour les services utilisés.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <p class="text-muted">Patients satisfaits</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">50+</div>
                        <p class="text-muted">Dermatologues certifiés</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <p class="text-muted">Disponibilité</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">98%</div>
                        <p class="text-muted">Taux de satisfaction</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold mb-3">Notre Histoire</h2>
                <p class="lead text-muted">Découvrez l'évolution de Dermonline</p>
            </div>
            
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>2023 - Naissance du projet</h4>
                        <p class="text-muted">Identification du besoin d'accès aux soins dermatologiques 
                        en Afrique et début du développement de la plateforme.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>2025 - Lancement beta</h4>
                        <p class="text-muted">Première version de la plateforme avec un groupe 
                        de dermatologues partenaires au Cameroun.</p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <h4>2025 - Expansion</h4>
                        <p class="text-muted">Développement de nouvelles fonctionnalités et 
                        expansion vers d'autres pays d'Afrique francophone.</p>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold mb-3">Ce que disent nos utilisateurs</h2>
                <p class="lead text-muted">Témoignages de patients et médecins</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150&h=150&fit=crop&crop=face" 
                             alt="Marie K." class="testimonial-avatar">
                        <h5>Marie K.</h5>
                        <p class="text-muted mb-3">Patient</p>
                        <p>"Grâce à Dermonline, j'ai pu consulter un dermatologue en urgence 
                        sans me déplacer. Le diagnostic était précis et le traitement efficace."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?w=150&h=150&fit=crop&crop=face" 
                             alt="Dr. Martin D." class="testimonial-avatar">
                        <h5>Dr. Martin D.</h5>
                        <p class="text-muted mb-3">Dermatologue</p>
                        <p>"Dermonline m'a permis d'étendre ma pratique et d'aider plus de patients. 
                        L'interface est intuitive et le support technique excellent."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face" 
                             alt="Pierre L." class="testimonial-avatar">
                        <h5>Pierre L.</h5>
                        <p class="text-muted mb-3">Patient</p>
                        <p>"Service exceptionnel ! J'ai obtenu un rendez-vous en moins de 2 heures 
                        et le dermatologue était très professionnel. Je recommande vivement."</p>
                        <div class="text-warning">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="display-6 fw-bold mb-4">Prêt à commencer votre expérience Dermonline ?</h2>
                    <p class="lead mb-5">Rejoignez des milliers de patients qui font confiance à notre plateforme 
                    pour leurs soins dermatologiques.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('doctor-application.create') }}" class="btn btn-glow">
                            <i class="bi bi-person-plus me-2"></i>Devenir Médecin Partenaire
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-glow">
                            <i class="bi bi-arrow-right me-2"></i>Commencer une Consultation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray text-black py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="mb-3">Dermonline</h5>
                    <p class="text-muted">Révolutionner l'accès aux soins dermatologiques en Afrique 
                    grâce à la technologie et l'expertise médicale.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-linkedin fs-5"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-instagram fs-5"></i></a>
                    </div>
                </div>
                <div class="col-lg-2">
                    <h6 class="mb-3">Services</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Téléconsultation</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Diagnostic</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Suivi médical</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Urgences</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h6 class="mb-3">Support</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Aide</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contact</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Sécurité</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h6 class="mb-3">Légal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Mentions légales</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Confidentialité</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">CGU</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Cookies</a></li>
                    </ul>
                </div>
                <div class="col-lg-2">
                    <h6 class="mb-3">Contact</h6>
                    <ul class="list-unstyled">
                        <li class="text-muted">+237 686913777</li>
                        <li class="text-muted">loictagne07@gmail.com</li>
                        <li class="text-muted">Yaoundé, Cameroun</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <!-- <div class="text-center">
                <p class="text-muted mb-0">&copy; 2025 Dermonline. Tous droits réservés.</p>
            </div> -->
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation au scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.scroll-animate').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html> 