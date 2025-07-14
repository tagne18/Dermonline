<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Espace Médecin')</title>

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/6lhclmnbc6onyi4z36sx630zl5bnoe30ofxjjvfzonub2l3g/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light text-dark" x-data="{ open: true }">
    <div class="d-flex min-vh-100">
        <!-- Sidebar -->
        <aside class="bg-primary text-white p-3" :class="open ? 'd-block' : 'd-none d-md-block'" style="width: 250px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-5">👨‍⚕️ Dr {{ Auth::user()->name }}</h2>
                <button class="btn btn-sm btn-light d-md-none" @click="open = false">✖️</button>
            </div>

            <nav class="nav flex-column gap-2">
                <a href="{{ route('medecin.dashboard') }}" class="nav-link text-white">🏠 Tableau de bord</a>
                <a href="{{ route('medecin.profile.edit') }}" class="nav-link text-white">⚙️ Mon Profil</a>
                <a href="{{ route('medecin.abonnements.index') }}" class="nav-link text-white">📋 Mes Abonnements</a>
                <a href="{{ route('medecin.consultations.index') }}" class="nav-link text-white">🩺 Consultations</a>
                <a href="{{ route('medecin.dossiers.index') }}" class="nav-link text-white">📁 Dossiers Médicaux</a>
                <a href="{{ route('medecin.annonces.index') }}" class="nav-link text-white">📢 Annonces</a>
                <a href="{{ route('medecin.messages.index') }}" class="nav-link text-white">💬 Messages</a>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">🔓 Déconnexion</button>
                </form>
            </nav>
        </aside>

        <!-- Main content -->
        <main class="flex-grow-1 p-4 bg-white">
            <!-- Toggle sidebar button -->
            <button class="btn btn-outline-primary mb-3 d-md-none" @click="open = true">📂 Menu</button>

            @yield('content')
        </main>
    </div>

    @include('components.chat.community')

<!-- Bouton flottant IA -->
@include('components.chat.ia')
@stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
