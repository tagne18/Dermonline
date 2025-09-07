<!-- resources/views/layouts/admin.blade.php -->

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <script src="https://cdn.tiny.cloud/1/6lhclmnbc6onyi4z36sx630zl5bnoe30ofxjjvfzonub2l3g/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- si tu utilises Laravel Mix ou Vite -->
    <style>
        /* Styles personnalisés pour le menu de navigation */
        .main-content {
            margin-left: 20rem;
            width: calc(100% - 20rem);
            transition: all 0.3s;
          
        }
        
        @media (max-width: 1199.98px) {
            .main-content {
                margin-left: 4rem;
                width: calc(100% - 4rem);
            }
        }
        
        @media (max-width: 767.98px) {
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 1rem !important;
            }
            
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
                z-index: 1040;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .navbar-toggler {
                display: block !important;
            }
        }
        
        .sidebar {
            scrollbar-width: thin;
            scrollbar-color: #4B5563 #1F2937;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: #1F2937;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #4B5563;
            border-radius: 3px;
        }
        
        .sidebar {
            height: calc(100vh - 12rem);
            overflow-y: auto;
            margin: 1rem 0;
            padding-right: 0.5rem;
            border-radius: 0.5rem;
        }
        
        .sidebar-nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 0.25rem;
            position: relative;
            overflow: hidden;
            border-radius: 0.375rem;
        }
        
        .sidebar-nav-item:not(.active-nav-item):hover {
            background-color: rgba(255, 255, 255, 0.03);
            transform: translateX(6px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .sidebar-nav-item i {
            position: relative;
            transition: transform 0.3s ease;
        }
        
        .sidebar-nav-item:hover i {
            transform: scale(1.1);
        }
        
        .sidebar-nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1), transparent);
            transition: width 0.3s ease;
            z-index: 0;
        }
        
        .sidebar-nav-item:hover::before {
            width: 100%;
        }
        
        .sidebar-nav-item > * {
            position: relative;
            z-index: 1;
        }
        
        .notification-badge {
            font-size: 0.7rem;
            min-width: 1.25rem;
            height: 1.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .active-nav-item {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.15), transparent);
            color: #3B82F6 !important;
            font-weight: 500;
            position: relative;
            transform: translateX(0) !important;
        }
        
        .active-nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #3B82F6;
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }
        
        .active-nav-item:hover {
            background: linear-gradient(90deg, rgba(37, 99, 235, 0.2), transparent);
        }
        
        .notification-badge {
            font-size: 0.7rem;
            min-width: 1.25rem;
            height: 1.25rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        /* Animation pour les badges de notification */
        .notification-badge.animated {
            position: relative;
            animation: pulse 2s infinite;
        }
        
        .notification-badge.animated::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 9999px;
            background: currentColor;
            opacity: 0.5;
            z-index: -1;
            animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
        }
        
        @keyframes ping {
            75%, 100% {
                transform: scale(2);
                opacity: 0;
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }
    </style>
</head>
<body class="bg-black text-white-800">
    <div class="d-flex flex-column min-vh-100">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar bg-black text-white shadow-md p-4 position-fixed h-100">
            {{-- <h2 class="text-xl font-bold mb-6">{{ Auth::user()->email }}</h2> --}}

            <!-- Photo et nom de l'admin -->
            <div class="text-center mb-6">
                <div class="relative inline-block">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" 
                         alt="Photo de profil" 
                         class="w-20 h-20 rounded-full border-2 border-blue-500 object-cover">
                </div>
                <h3 class="mt-2 text-lg font-medium">{{ Auth::user()->name }}</h3>
                <p class="text-sm text-gray-400">{{ Auth::user()->email }}</p>
            </div>

            <nav class="sidebar space-y-2">
                <!-- Section Tableau de bord -->
                <div class="px-3 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Tableau de bord
                </div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.dashboard') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-tachometer-alt w-5 text-center mr-3"></i>
                    <span>Tableau de bord</span>
                </a>

                <!-- Section Gestion des utilisateurs -->
                <div class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Gestion des utilisateurs
                </div>
                <a href="{{ route('admin.profile.edit') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.profile.*') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-user-edit w-5 text-center mr-3"></i>
                    <span>Mon Profil</span>
                </a>
                <a href="{{ route('admin.doctor_applications.index') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.doctor_applications.*') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-user-md w-5 text-center mr-3"></i>
                    <span>Médecins</span>
                    <span class="ml-auto bg-blue-500 text-white text-xs px-2 py-1 rounded-full notification-badge animated">Nouveau</span>
                </a>
                <a href="{{ route('admin.users.patients') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.users.*') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-users w-5 text-center mr-3"></i>
                    <span>Patients</span>
                </a>
                <a href="{{ route('admin.blocked') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.blocked') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-user-lock w-5 text-center mr-3"></i>
                    <span>Utilisateurs bloqués</span>
                </a>

                <!-- Section Contenus -->
                <div class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Contenus
                </div>
                <a href="{{ route('admin.announcements') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.announcements') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-bullhorn w-5 text-center mr-3"></i>
                    <span>Annonces</span>
                </a>
                <a href="{{ route('admin.consultations.index') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.consultations.*') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-notes-medical w-5 text-center mr-3"></i>
                    <span>Consultations</span>
                </a>
                <a href="{{ route('admin.testimonials') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.testimonials') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-star w-5 text-center mr-3"></i>
                    <span>Témoignages</span>
                </a>

                <!-- Section Abonnements et Paiements -->
                <div class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Abonnements & Paiements
                </div>
                <a href="{{ route('admin.abonnements.index') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.abonnements.*') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-credit-card w-5 text-center mr-3"></i>
                    <span>Gestion des abonnements</span>
                </a>
                <a href="#" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors text-gray-300">
                    <i class="fas fa-receipt w-5 text-center mr-3"></i>
                    <span>Paiements</span>
                </a>

                <!-- Section Communication -->
                <div class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Communication
                </div>
                <a href="{{ route('admin.newsletters.index') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.newsletters.*') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-paper-plane w-5 text-center mr-3"></i>
                    <span>Newsletters</span>
                </a>
                <a href="{{ route('admin.contacts.index') }}" class="sidebar-nav-item flex items-center px-4 py-3 text-sm rounded-lg hover:bg-gray-800 transition-colors {{ request()->routeIs('admin.contacts.*') ? 'active-nav-item' : 'text-gray-300' }}">
                    <i class="fas fa-envelope w-5 text-center mr-3"></i>
                    <span>Messages</span>
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full notification-badge animated">3</span>
                </a>
            </nav>

            <div class="mt-8">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger text-black-500 hover:text-red-700">🔓 Déconnexion</button>
                </form>
            </div>
        </nav>

        <!-- Bouton de basculement du menu pour mobile -->
        <button class="navbar-toggler position-fixed d-md-none" type="button" style="z-index: 1050; top: 1rem; left: 1rem;" onclick="toggleSidebar()">
            <i class="fas fa-bars text-white"></i>
        </button>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </main>
    </div>

@stack('scripts')

<script>
// Fonction pour basculer le menu latéral sur mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('show');
}

// Fermer le menu lorsqu'on clique à l'extérieur
window.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.navbar-toggler');
    
    if (window.innerWidth <= 767.98 && 
        !sidebar.contains(event.target) && 
        !toggleBtn.contains(event.target)) {
        sidebar.classList.remove('show');
    }
});

// Redimensionnement de la fenêtre
window.addEventListener('resize', function() {
    const sidebar = document.getElementById('sidebar');
    if (window.innerWidth > 767.98) {
        sidebar.classList.remove('show');
    }
});
</script>

    <!-- Bootstrap Bundle JS (incl. Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
