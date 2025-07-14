@extends('layouts.admin')

@section('title', 'Créer une annonce')

@section('content')
    <h1 class=" text-white text-2xl font-bold mb-6">➕ Nouvelle Annonce</h1>

    <form action="{{ route('admin.annonces.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="text-white" for="titre">Titre</label>
            <input type="text" name="titre" id="titre" class="w-full p-2 border" required>
        </div>

        <div>
            <label class="text-white" for="contenu">Contenu</label>
            <textarea name="contenu" id="contenu" class="my-editor w-full p-2 border" rows="5" required></textarea>
        </div>

        {{-- <script>
            tinymce.init({
                selector: 'textarea.my-editor',
                menubar: false,
                plugins: 'lists link image preview',
                toolbar: 'undo redo | bold italic underline | bullist numlist | link | preview',
                branding: false
            });
        </script> --}}


        <div>
            <label class="text-white" for="image">Image (optionnelle)</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        {{-- <div>
            <label for="date_publication">Date de publication</label>
            <input type="date" name="date_publication" id="date_publication" class="border p-2">
        </div> --}}

        <button type="submit" class="btn btn-primary   px-4 py-2 rounded">Créer</button>
    </form>
@endsection

