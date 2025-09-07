<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Dermonline | Consultation Dermatologique en Ligne</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('style/welcome.css') }}">
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
        
        .login-content {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            box-shadow: var(--shadow-xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }

        .login-header {
            background: var(--bg-gradient);
            color: white;
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle cx="200" cy="200" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="800" cy="300" r="1.5" fill="rgba(255,255,255,0.08)"/><circle cx="600" cy="600" r="2.5" fill="rgba(255,255,255,0.12)"/></svg>');
            animation: float 6s ease-in-out infinite;
        }

        .login-header-content {
            position: relative;
            z-index: 2;
        }

        .logo-large {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .login-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .login-body {
            padding: 2.5rem 2rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating .form-control {
            border: 2px solid rgba(0,0,0,0.1);
            border-radius: 15px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.8);
        }

        .form-floating .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
            background: white;
        }

        .form-floating label {
            padding: 1rem 1.25rem;
            color: var(--text-light);
        }

        .form-check {
            margin: 1.5rem 0;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-login {
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

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            color: white;
        }

        .btn-register {
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-register:hover {
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

        .features-list {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }

        .features-list li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.75rem;
            background: rgba(59, 130, 246, 0.05);
            border-radius: 10px;
            border-left: 4px solid var(--primary-color);
        }

        .features-list i {
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

        @media (max-width: 768px) {
            .login-content {
                padding: 1rem;
            }
            
            .login-card {
                margin: 0 1rem;
            }
            
            .back-to-home {
                top: 1rem;
                left: 1rem;
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
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
    <a href="{{ route('welcome') }}" class="back-to-home">
        <i class="bi bi-arrow-left me-2"></i>Retour à l'accueil
    </a>

    <!-- Contenu principal -->
    <div class="login-content">
        <div class="login-card animate-fade-in-up">
            <!-- En-tête -->
            <div class="login-header">
                <div class="login-header-content">
                    <div class="logo-large">Dermonline</div>
                    <p class="login-subtitle">Accédez à votre espace personnel</p>
                </div>
            </div>

            <!-- Corps du formulaire -->
            <div class="login-body">
                <!-- Messages d'erreur/succès -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Formulaire de connexion -->
                <form method="POST" action="{{ route('login') }}">
            @csrf

                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required autofocus>
                        <label for="email">
                            <i class="bi bi-envelope me-2"></i>Adresse email
                        </label>
            </div>

                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
                        <label for="password">
                            <i class="bi bi-lock me-2"></i>Mot de passe
                        </label>
            </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Se souvenir de moi
                </label>
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                    </button>

                @if (Route::has('password.request'))
                        <div class="text-center mb-3">
                            <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: var(--primary-color);">
                                <i class="bi bi-question-circle me-1"></i>Mot de passe oublié ?
                            </a>
                        </div>
                    @endif

                    <div class="divider">
                        <span>ou</span>
                    </div>

                    <a href="{{ route('register') }}" class="btn btn-register text-dark w-100">
                        <i class="bi bi-person-plus me-2 text-dark"></i>Créer un compte 
                    </a>
                </form>

                <!-- Fonctionnalités -->

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
