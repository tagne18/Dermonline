<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos solutions - Dermonline</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style/welcome.css') }}">
    <style>
        .hero-solutions {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 40vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
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
    </style>
</head>
<body>
    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-house-door-fill me-2"></i>Accueil
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-solutions">
        <div class="container py-5">
            <h1 class="display-4 fw-bold mb-3">Nos solutions dermatologiques</h1>
            <p class="lead mb-0">Découvrez comment Dermonline révolutionne la prise en charge de votre peau grâce à la technologie et à l'expertise médicale.</p>
        </div>
    </section>

    <!-- Solutions Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon mb-3"><i class="bi bi-calendar-check"></i></div>
                        <h3 class="feature-title mb-2">Consultations en ligne</h3>
                        <p class="feature-description">Prenez rendez-vous avec un dermatologue certifié, échangez en toute sécurité et recevez un diagnostic sans vous déplacer.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon mb-3"><i class="bi bi-chat-dots"></i></div>
                        <h3 class="feature-title mb-2">Assistant IA</h3>
                        <p class="feature-description">Obtenez des conseils personnalisés 24h/24 grâce à notre intelligence artificielle spécialisée en dermatologie.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon mb-3"><i class="bi bi-file-earmark-medical"></i></div>
                        <h3 class="feature-title mb-2">Dossier médical sécurisé</h3>
                        <p class="feature-description">Accédez à vos résultats, ordonnances et suivis en toute confidentialité, centralisés sur votre espace personnel.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon mb-3"><i class="bi bi-people"></i></div>
                        <h3 class="feature-title mb-2">Communauté & entraide</h3>
                        <p class="feature-description">Partagez vos expériences, posez vos questions et bénéficiez du soutien d'autres patients et professionnels.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon mb-3"><i class="bi bi-shield-lock"></i></div>
                        <h3 class="feature-title mb-2">Sécurité & confidentialité</h3>
                        <p class="feature-description">Vos données sont protégées et traitées dans le respect des normes médicales et de la vie privée.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <div class="feature-icon mb-3"><i class="bi bi-lightbulb"></i></div>
                        <h3 class="feature-title mb-2">Innovation continue</h3>
                        <p class="feature-description">Nous intégrons les dernières avancées technologiques pour améliorer sans cesse votre expérience de soin.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 text-center bg-light">
        <div class="container">
            <h2 class="mb-4">Prêt à prendre soin de votre peau ?</h2>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Commencer maintenant</a>
        </div>
    </section>

    <footer class="py-4 bg-white border-top text-center">
        <div class="container">
            <span class="text-muted">&copy; {{ date('Y') }} Dermonline. Tous droits réservés.</span>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>