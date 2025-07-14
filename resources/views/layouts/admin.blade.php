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


    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- si tu utilises Laravel Mix ou Vite -->
</head>
<body class="bg-black text-white-800">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-black text-white shadow-md p-4 space-y-4">
            {{-- <h2 class="text-xl font-bold mb-6">{{ Auth::user()->email }}</h2> --}}

            <nav class="space-y-6">
                <a href="{{ route('admin.dashboard') }}" class="block hover:text-blue-600">🏠 Tableau de bord</a>
                <a href="{{ route('admin.doctor_applications.index') }}" class="block hover:text-blue-600">👨‍⚕️ Gérer les Médecins</a>
                <a href="{{ route('admin.users.patients') }}" class="block hover:text-blue-600">🧑‍🦽 Gérer les Patients</a>
                <a href="{{ route('admin.announcements') }}" class="block hover:text-blue-600">📢 Annonces</a>
                <a href="{{ route('admin.consultations.index') }}" class="block hover:text-blue-600">📋 Consultations</a>
                <a href="{{ route('admin.testimonials') }}" class="block hover:text-blue-600">💬 Témoignages</a>
                <a href="{{ route('admin.abonnements.index') }}" class="block hover:text-blue-600">💳 Abonnements</a>
                <a href="{{ route('admin.blocked') }}" class="block hover:text-blue-600">🚫 Utilisateurs bloqués</a>
                <a href="{{ route('admin.newsletters.index') }}" class="block hover:text-blue-600">📰 Gérer les Newsletters</a>
                <a href="{{ route('admin.contacts.index') }}" class="block hover:text-blue-600">📧 Gérer les Contacts</a>
            </nav>

            <div class="mt-8">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger text-black-500 hover:text-red-700">🔓 Déconnexion</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            @yield('content')
        </main>
    </div>

@stack('scripts')

    <!-- Bootstrap Bundle JS (incl. Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
