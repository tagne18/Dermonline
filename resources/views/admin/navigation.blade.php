<nav class="bg-black border-b p-4">
    <a href="{{ route('admin.dashboard') }}" class="font-bold text-indigo-600">Admin Dashboard</a>
    <form method="POST" action="{{ route('logout') }}" class="inline ml-4">
        @csrf
        <button class="btn btn-danger text-red-500">Déconnexion</button>
    </form>
</nav>
