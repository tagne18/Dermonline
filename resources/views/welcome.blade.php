<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dermonline - Consultation Dermatologique en Ligne</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Animations */
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

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }

        /* Utility Classes */
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fade-in-left {
            animation: fadeInLeft 0.8s ease-out forwards;
        }

        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out forwards;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-pulse {
            animation: pulse 2s ease-in-out infinite;
        }

        /* Header Styles */
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .header.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-lg);
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            background: var(--bg-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            position: relative;
            font-weight: 500;
            color: var(--text-dark) !important;
            transition: all 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle cx="200" cy="200" r="2" fill="rgba(255,255,255,0.3)"/><circle cx="600" cy="300" r="1.5" fill="rgba(255,255,255,0.2)"/><circle cx="800" cy="600" r="2.5" fill="rgba(255,255,255,0.25)"/><circle cx="300" cy="700" r="1" fill="rgba(255,255,255,0.3)"/><circle cx="900" cy="200" r="2" fill="rgba(255,255,255,0.2)"/></svg>');
            animation: float 6s ease-in-out infinite;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .hero h3 {
            font-size: 1.5rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
        }

        .hero-floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 4s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            right: 15%;
            animation-delay: 1s;
        }

        .floating-element:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 20%;
            left: 20%;
            animation-delay: 2s;
        }

        /* Cards */
        .card-modern {
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            border: none;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card-modern:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
        }

        .icon-box {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .icon-box:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .icon-box i {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 2rem;
            transition: all 0.3s ease;
        }

        .icon-box:hover i {
            transform: scale(1.1);
        }

        /* Buttons */
        .btn-modern {
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-modern:hover::before {
            left: 100%;
        }

        .btn-primary-modern {
            background: var(--bg-gradient);
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Sections */
        .section {
            padding: 100px 0;
            position: relative;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            position: relative;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            width: 60px;
            height: 4px;
            background: var(--bg-gradient);
            transform: translateX(-50%);
            border-radius: 2px;
        }

        /* Services Grid */
        .service-card {
            background: white;
            padding: 3rem 2rem;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
        }

        .service-card i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .service-card:hover i {
            transform: scale(1.1);
            color: var(--secondary-color);
        }

        /* Team Section */
        .team-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-md);
        }

        .team-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
        }

        .team-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .team-card:hover img {
            transform: scale(1.05);
        }

        /* Testimonial Section */
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
            text-align: center;
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
        }

        .testimonial-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1rem;
        }

        .testimonial-card .stars {
            color: var(--accent-color);
            margin-bottom: 1rem;
        }

        /* Department Section */
        .department-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .department-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
        }

        .department-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .department-card:hover i {
            color: var(--secondary-color);
        }

        /* Newsletter Section */
        .newsletter-section {
            background: var(--bg-light);
            padding: 80px 0;
            text-align: center;
        }

        .newsletter-form {
            max-width: 500px;
            margin: 0 auto;
        }

        .newsletter-form .input-group {
            border-radius: 50px;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }

        .newsletter-form .form-control {
            border: none;
            padding: 15px 20px;
        }

        .newsletter-form .btn {
            border-radius: 0 50px 50px 0;
        }

        /* Counter Section */
        .counter-section {
            background: var(--bg-gradient);
            color: white;
            padding: 60px 0;
            text-align: center;
        }

        .counter-card {
            padding: 2rem;
            text-align: center;
        }

        .counter-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .counter-card p {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
        }

        /* CAT Section */
        .cat-section {
            background: var(--bg-gradient);
            color: white;
            padding: 80px 0;
            text-align: center;
        }

        .cat-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .cat-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, 0.9);
        }

        /* Contact Section */
        .contact-form {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
        }

        .form-control {
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 10px;
            padding: 15px 20px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            color: white;
            padding: 60px 0 30px;
        }

        .footer h4 {
            color: white;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        /* Scroll Animations */
        .scroll-animate {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .scroll-animate.animated {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero h3 {
                font-size: 1.2rem;
            }
            
            .section {
                padding: 60px 0;
            }
            
            .cat-section h2,
            .newsletter-section h2,
            .counter-section h2 {
                font-size: 2rem;
            }
            
            .cat-section p,
            .newsletter-section p {
                font-size: 1rem;
            }
            
            .counter-card h3 {
                font-size: 2rem;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--bg-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.5s ease;
        }

        .loading-overlay.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .loader {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text-medical .loading-letter {
            opacity: 0.2;
            display: inline-block;
            transform: translateY(0);
            animation: medicalLetter 1.2s infinite;
        }
        .loading-text-medical .loading-letter:nth-child(1) { animation-delay: 0s; }
        .loading-text-medical .loading-letter:nth-child(2) { animation-delay: 0.1s; }
        .loading-text-medical .loading-letter:nth-child(3) { animation-delay: 0.2s; }
        .loading-text-medical .loading-letter:nth-child(4) { animation-delay: 0.3s; }
        .loading-text-medical .loading-letter:nth-child(5) { animation-delay: 0.4s; }
        .loading-text-medical .loading-letter:nth-child(6) { animation-delay: 0.5s; }
        .loading-text-medical .loading-letter:nth-child(7) { animation-delay: 0.6s; }
        .loading-text-medical .loading-letter:nth-child(8) { animation-delay: 0.7s; }
        .loading-text-medical .loading-letter:nth-child(9) { animation-delay: 0.8s; }
        .loading-text-medical .loading-letter:nth-child(10) { animation-delay: 0.9s; }
        @keyframes medicalLetter {
            0% { opacity: 0.2; transform: translateY(0); }
            30% { opacity: 1; transform: translateY(-10px) scale(1.2); color: #10b981; }
            60% { opacity: 1; transform: translateY(0) scale(1); color: #3b82f6; }
            100% { opacity: 0.2; transform: translateY(0); }
        }
    </style>
</head>
<body>
    @if(session('newsletter_success'))
        <!-- Modal Newsletter Success -->
        <div class="modal fade show" id="newsletterSuccessModal" tabindex="-1" aria-modal="true" style="display: block; background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Abonnement réussi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer" onclick="closeNewsletterModal()"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="bi bi-check-circle text-success fs-1 mb-3"></i>
                        <p>{{ session('newsletter_success') }}</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-primary" onclick="closeNewsletterModal()">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function closeNewsletterModal() {
                document.getElementById('newsletterSuccessModal').style.display = 'none';
                document.getElementById('newsletterSuccessModal').classList.remove('show');
            }
            // Empêche le scroll du fond tant que le modal est ouvert
            document.body.style.overflow = 'hidden';
            document.getElementById('newsletterSuccessModal').addEventListener('hidden.bs.modal', function () {
                document.body.style.overflow = '';
            });
        </script>
    @endif

    <!-- Loading Screen -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="d-flex flex-column align-items-center justify-content-center" style="height: 100vh;">
            <div class="mb-4">
                <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="animate-pulse">
                    <circle cx="30" cy="30" r="28" stroke="#3b82f6" stroke-width="4" fill="#f8fafc"/>
                    <path d="M20 35 Q30 50 40 35" stroke="#10b981" stroke-width="3" fill="none"/>
                    <circle cx="20" cy="35" r="3" fill="#10b981"/>
                    <circle cx="40" cy="35" r="3" fill="#10b981"/>
                    <rect x="27" y="15" width="6" height="18" rx="3" fill="#3b82f6">
                        <animate attributeName="y" values="15;10;15" dur="1s" repeatCount="indefinite"/>
                    </rect>
                </svg>
            </div>
            <div class="loading-text-medical" style="font-size:2rem; font-weight:700; letter-spacing:0.2em; color:#3b82f6;">
                <span class="loading-letter">D</span><span class="loading-letter">E</span><span class="loading-letter">R</span><span class="loading-letter">M</span><span class="loading-letter">O</span><span class="loading-letter">N</span><span class="loading-letter">L</span><span class="loading-letter">I</span><span class="loading-letter">N</span><span class="loading-letter">E</span>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="header fixed-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand logo" href="#">Dermonline</a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="#hero">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about"> propos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#services">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#doctors">Équipe</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#testimonials">Témoignages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#departments">Départements</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#newsletter">Newsletter</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#counters">Statistiques</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">Contact</a>
                        </li>
                    </ul>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-modern">Se connecter</a>
                        <a href="#" class="btn btn-primary-modern btn-modern">Rendez-vous</a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="hero">
        <div class="hero-floating-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>
        
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="animate-fade-in-left">Votre santé dermatologique à portée de clic</h1>
                    <h3 class="animate-fade-in-left" style="animation-delay: 0.2s;">Consultations en ligne avec des dermatologues certifiés, 24h/24 et 7j/7</h3>
                    
                    <div class="d-flex gap-3 flex-wrap animate-fade-in-left" style="animation-delay: 0.4s;">
                        <a href="#" class="btn btn-primary-modern btn-modern btn-lg">
                            <i class="bi bi-calendar-check me-2"></i>Prendre rendez-vous
                        </a>
                        <a href="{{ route('about') }}" class="btn btn-primary-modern btn-modern btn-lg">
                            <i class="bi bi-info-circle me-2"></i>En savoir plus
                        </a>
                    </div>
                    
                    <div class="row mt-5 animate-fade-in-up" style="animation-delay: 0.6s;">
                        <div class="col-4 text-center">
                            <div class="text-white">
                                <h3 class="mb-0">500+</h3>
                                <small>Patients satisfaits</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="text-white">
                                <h3 class="mb-0">24/7</h3>
                                <small>Support disponible</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="text-white">
                                <h3 class="mb-0">15+</h3>
                                <small>Experts certifiés</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6 text-center animate-fade-in-right">
                    <div class="position-relative">
                    <div class="animate-float">
                            <img src="https://images.pexels.com/photos/1181696/pexels-photo-1181696.jpeg?auto=compress&w=500&h=600&fit=crop" 
                                 alt="Médecin africain camerounais" class="img-fluid rounded-4 shadow-xl" style="max-width: 600px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section bg-light">
        <div class="container">
            <div class="section-title scroll-animate">
                <h2>Nos Services</h2>
                <p class="lead text-muted">Des solutions dermatologiques complètes adaptées à vos besoins</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="service-card">
                        <i class="bi bi-camera-video"></i>
                        <h4>Téléconsultation</h4>
                        <p>Consultez un dermatologue en vidéo depuis chez vous, en toute sécurité et confidentialité.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="service-card">
                        <i class="bi bi-search"></i>
                        <h4>Diagnostic rapide</h4>
                        <p>Obtenez un diagnostic précis en moins de 24h grâce à notre équipe d'experts.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="service-card">
                        <i class="bi bi-heart-pulse"></i>
                        <h4>Suivi personnalisé</h4>
                        <p>Bénéficiez d'un suivi médical continu adapté à votre condition et vos besoins.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="service-card">
                        <i class="bi bi-shield-check"></i>
                        <h4>Dépistage préventif</h4>
                        <p>Surveillez votre peau et détectez précocement les signes suspects.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="service-card">
                        <i class="bi bi-prescription2"></i>
                        <h4>Prescriptions en ligne</h4>
                        <p>Recevez vos ordonnances directement par email, valables en pharmacie.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="service-card">
                        <i class="bi bi-clock"></i>
                        <h4>Urgences dermatologiques</h4>
                        <p>Service d'urgence disponible 24h/24 pour les cas critiques.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 scroll-animate">

                         <alt="À propos - médecine" class="img-fluid rounded-4 shadow-lg">
                    <div style="margin-top: 20px;">
                    <iframe src="https://assets.pinterest.com/ext/embed.html?id=1060034831013239376" height="714" width="345" frameborder="0" scrolling="no" ></iframe>
                    </div>
                </div>
                
                <div class="col-lg-6 scroll-animate">
                    <h2>Excellence en dermatologie digitale</h2>
                    <p class="lead">Dermonline révolutionne l'accès aux soins dermatologiques en combinant expertise médicale et technologies de pointe.</p>
                    
                    <div class="row g-4 mt-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-check-circle text-primary fs-4"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">Certifié</h5>
                                    <small class="text-muted">Agréé par l'ordre des médecins</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                        <i class="bi bi-shield-check text-success fs-4"></i>
                                    </div>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">Sécurisé</h5>
                                    <small class="text-muted">Données cryptées et confidentielles</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('solution') }}" class="btn btn-primary-modern btn-modern mt-4">
                        Découvrir nos solutions
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="doctors" class="section bg-light">
        <div class="container">
            <div class="section-title scroll-animate">
                <h2>Notre Équipe d'Experts</h2>
                <p class="lead text-muted">Des dermatologues expérimentés à votre service</p>
            </div>
            <div class="row g-4">
                @foreach($medecins as $medecin)
                    <div class="col-lg-3 col-md-6 scroll-animate">
                        <div class="team-card">
                            <img src="{{ $medecin->profile_photo_url }}" alt="{{ $medecin->name }}" style="width:300px;height:300px;object-fit:cover;">
                            <div class="p-4 text-center">
                                <h5>{{ $medecin->name }}</h5>
                                <p class="text-muted mb-2">{{ $medecin->specialite ?? 'Dermatologue' }}</p>
                                <p class="small text-secondary mb-3">{{ $medecin->a_propos ? Str::limit($medecin->a_propos, 80) : '' }}</p>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Réseaux sociaux à ajouter si besoin --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="section bg-light">
        <div class="container">
            <div class="section-title scroll-animate">
                <h2>Témoignages</h2>
                <p class="lead text-muted">Ce que nos patients disent de nous</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="testimonial-card">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=80&h=80&fit=crop&crop=face" alt="Patient 1">
                        <div class="stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p>"Une expérience incroyable ! Le dermatologue était à l'écoute et m'a donné un diagnostic précis en 24h."</p>
                        <h5>Marie Dubois</h5>
                        <p class="text-muted">Patient, Paris</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="testimonial-card">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&crop=face" alt="Patient 2">
                        <div class="stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-half"></i>
                        </div>
                        <p>"Le suivi personnalisé m'a beaucoup aidé à gérer mon problème de peau. Je recommande vivement !"</p>
                        <h5>Jean Martin</h5>
                        <p class="text-muted">Patient, Lyon</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="testimonial-card">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80&h=80&fit=crop&crop=face" alt="Patient 3">
                        <div class="stars">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <p>"Service rapide et professionnel. J'ai pu obtenir une ordonnance sans quitter mon domicile."</p>
                        <h5>Sophie Laurent</h5>
                        <p class="text-muted">Patient, Marseille</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Departments Section -->
    <section id="departments" class="section">
        <div class="container">
            <div class="section-title scroll-animate">
                <h2>Nos Départements</h2>
                <p class="lead text-muted">Découvrez nos spécialisations médicales</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="department-card">
                        <i class="bi bi-heart-pulse"></i>
                        <h4>Dermatologie Générale</h4>
                        <p>Traitement des affections courantes de la peau comme l'acné, l'eczéma et le psoriasis.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="department-card">
                        <i class="bi bi-scissors"></i>
                        <h4>Chirurgie Dermatologique</h4>
                        <p>Interventions pour enlever les lésions cutanées, y compris les cancers de la peau.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="department-card">
                        <i class="bi bi-droplet"></i>
                        <h4>Dermatologie Esthétique</h4>
                        <p>Soins pour améliorer l'apparence de la peau, comme les peelings et les injections.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="department-card">
                        <i class="bi bi-shield-check"></i>
                        <h4>Allergologie Cutanée</h4>
                        <p>Diagnostic et gestion des allergies cutanées et des réactions dermatologiques.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="department-card">
                        <i class="bi bi-eye"></i>
                        <h4>Dépistage du Cancer</h4>
                        <p>Examens pour la détection précoce des cancers de la peau, comme le mélanome.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 scroll-animate">
                    <div class="department-card">
                        <i class="bi bi-band-aid"></i>
                        <h4>Dermatologie Pédiatrique</h4>
                        <p>Soins spécialisés pour les problèmes de peau des enfants et adolescents.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section id="newsletter" class="newsletter-section">
        <div class="container">
            <div class="section-title scroll-animate">
                <h2>Inscrivez-vous à notre Newsletter</h2>
                <p class="lead text-muted">Restez informé des dernières actualités et conseils en dermatologie</p>
            </div>
            <div class="newsletter-form scroll-animate">
                <form class="input-group" method="POST" action="{{ route('newsletter.subscribe') }}">
                    @csrf
                    @if(session('newsletter_success'))
                        <div class="alert alert-success w-100 text-center">{{ session('newsletter_success') }}</div>
                    @endif
                    <input type="email" class="form-control" name="email" placeholder="Votre adresse e-mail" required>
                    <button type="submit" class="btn btn-primary-modern btn-modern">S'inscrire</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Counter Section -->
    <section id="counters" class="counter-section">
        <div class="container">
            <div class="section-title scroll-animate">
                <h2>Nos Chiffres Clés</h2>
                <p class="lead text-white">Découvrez l'impact de Dermonline</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 scroll-animate">
                    <div class="counter-card">
                        <h3 id="patientCounter">500+</h3>
                        <p>Patients satisfaits</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 scroll-animate">
                    <div class="counter-card">
                        <h3 id="doctorCounter">15+</h3>
                        <p>Médecins enregistrés</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 scroll-animate">
                    <div class="counter-card">
                        <h3 id="consultationCounter">1000+</h3>
                        <p>Consultations réalisées</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 scroll-animate">
                    <div class="counter-card">
                        <h3 id="ratingCounter">4.8/5</h3>
                        <p>Évaluation moyenne</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CAT Section -->
    <section id="cat" class="cat-section">
        <div class="container">
            <div class="scroll-animate">
                <h2>Êtes-vous dermatologue ?</h2>
                <p>Rejoignez notre plateforme de téléconsultation pour proposer vos services en ligne et générer un revenu complémentaire.</p>
                <a href="{{ route('doctor-application.create') }}" class="btn btn-outline-light btn-modern btn-lg">
                    <i class="bi bi-person-plus me-2"></i>Devenir partenaire
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <div class="container">
            <div class="section-title scroll-animate">
                <h2>Contactez-nous</h2>
                <p class="lead text-muted">Prenez contact avec nous pour toute question ou pour planifier une consultation</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8 scroll-animate">
                    <div class="contact-form">
                        <form method="POST" action="{{ route('contact.send') }}">
                            @csrf
                            @if(session('success'))
                                <div class="alert alert-success text-center">{{ session('success') }}</div>
                            @endif
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" placeholder="Nom complet" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" placeholder="Adresse e-mail" required>
                                </div>
                                <div class="col-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Sujet" required>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" name="message" rows="5" placeholder="Votre message" required></textarea>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary-modern btn-modern">Envoyer le message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <h4>À propos de Dermonline</h4>
                    <p class="text-light">Dermonline offre des consultations dermatologiques en ligne avec des experts certifiés, disponibles 24h/24 et 7j/7 pour votre santé.</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                    <h4>Liens rapides</h4>
                    <ul class="list-unstyled">
                        <li><a href="#hero">Accueil</a></li>
                        <li><a href="#about">À propos</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="#doctors">Équipe</a></li>
                        <li><a href="#testimonials">Témoignages</a></li>
                        <li><a href="#departments">Départements</a></li>
                        <li><a href="#newsletter">Newsletter</a></li>
                        <li><a href="#counters">Statistiques</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <h4>Contact</h4>
                    <ul class="list-unstyled">
                        <li><a href="mailto:contact@dermonline.com">contact@dermonline.com</a></li>
                        <li><a href="tel:+33123456789">+33 1 23 45 67 89</a></li>
                        <li>123 Rue de la Santé, 75001 Paris, France</li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h4>Suivez-nous</h4>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <hr class="my-4 bg-light opacity-25">
            
            <div class="text-center">
                <p class="mb-0">© 2025 Dermonline. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Loading animation
        window.addEventListener('load', () => {
            const loadingOverlay = document.getElementById('loadingOverlay');
            setTimeout(() => {
                loadingOverlay.classList.add('hidden');
            }, 3000);
        });

        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Scroll animations
        const scrollElements = document.querySelectorAll('.scroll-animate');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        scrollElements.forEach(el => observer.observe(el));

        // Smooth scroll for nav links
        document.querySelectorAll('.nav-link').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Counter animation
        function animateCounter(id, start, end, duration) {
            const element = document.getElementById(id);
            if (!element) return;
            
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                element.textContent = value + (id === 'ratingCounter' ? '/5' : '+');
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter('patientCounter', 0, 500, 2000);
                    animateCounter('doctorCounter', 0, 15, 2000);
                    animateCounter('consultationCounter', 0, 1000, 2000);
                    animateCounter('ratingCounter', 0, 4.8, 2000);
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counterObserver.observe(document.querySelector('#counters'));
    </script>
</body>
</html>