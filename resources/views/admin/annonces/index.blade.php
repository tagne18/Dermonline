@extends('layouts.admin')

@section('title', '📢 Annonces')

@section('content')
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-red text-white mt-5 py-5">📢 Liste des Annonces</h1>
        <a href="{{ route('admin.annonces.create') }}" class="btn btn-success bg-blue-600 py-2 rounded hover:bg-blue-700">
            ➕ Nouvelle Annonce
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 px-3">
        @forelse ($annonces as $annonce)
            <div class="bg-white rounded-lg shadow p-2 text-sm">
                <h2 class="px-3 text-lg font-semibold text-gray-800 mb-1 truncate">{{ $annonce->titre }}</h2>
                <p class="px-3 text-gray mb-2 line-clamp-3">{{ $annonce->contenu }}</p>

                @if($annonce->image)
                <img src="{{ asset('storage/' . $annonce->image) }}" alt="Image"
                class="w-full h-32 object-cover rounded">
                @endif

                <div class="text-xs text-gray-600 flex justify-between items-center">
                    <div>
                        <h6>creer le 🕒 {{ $annonce->created_at->format('d/m/Y') }} a: {{ $annonce->created_at->format('H:i') }}</h6><br>
                       {{-- <h6>modifier le 🕒 {{ $annonce->updated_at->format('d/m/Y':'H:i') }} </h6> --}}
                        👤 {{ $annonce->user->nom ?? 'Admin' }}
                    </div>
                    <div class="space-x-1 text-right">
                        <a href="{{ route('admin.annonces.edit', $annonce->id) }}" class="btn btn-primary text-yellow-500 hover:underline">Modifier</a>
                        <form action="{{ route('admin.annonces.destroy', $annonce->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Supprimer cette annonce ?')" class="btn btn-danger text-black-500 hover:underline">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500">Aucune annonce disponible.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $annonces->links() }}
    </div>
@endsection
