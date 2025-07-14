@extends('layouts.admin')

@section('title', 'Modifier l’annonce')

@section('content')
    <h1 class="text-2xl font-bold mb-6">✏️ Modifier l’annonce</h1>

    <form action="{{ route('admin.annonces.update', $annonce->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="titre" class="block font-semibold mb-1">Titre</label>
            <input type="text" name="titre" id="titre" value="{{ old('titre', $annonce->titre) }}" class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label for="contenu" class="block font-semibold mb-1">Contenu</label>
            <textarea name="contenu" id="contenu" rows="6" class="w-full p-2 border rounded" required>{{ old('contenu', $annonce->contenu) }}</textarea>
        </div>

        <div>
            <label for="image" class="block font-semibold mb-1">Image actuelle</label>
            @if($annonce->image)
                <img src="{{ asset('storage/' . $annonce->image) }}" alt="Image de l’annonce" class="w-32 h-32 object-cover rounded mb-2">
            @else
                <p class="text-gray-500">Aucune image</p>
            @endif
            <input type="file" name="image" id="image" class="mt-2">
        </div>

        {{-- <div>
            <label for="date_publication" class="block font-semibold mb-1">Date de publication</label>
            <input type="date" name="date_publication" id="date_publication"
                   value="{{ old('date_publication', $annonce->date_publication ? $annonce->date_publication->format('Y-m-d') : '') }}"
                   class="p-2 border rounded">
        </div> --}}

        <div>
            <button type="submit" class="btn btn-primary text-white px-4 py-2 rounded hover:bg-blue-700">
                💾 Enregistrer les modifications
            </button>
        </div>
    </form>
@endsection
