<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vos Annonces') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <h2 class="mb-4">📢 Dernières annonces</h2>

        @if ($annonces->isEmpty())
            <div class="alert alert-info">Aucune annonce disponible pour le moment.</div>
        @else
            <div class="row">
                @foreach ($annonces as $annonce)
                    <div class="col-md-4 mb-4">
                        <div class="card h-70 w-auto shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $annonce->titre }}</h5>
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($annonce->contenu, 100) }}</p>
                            </div>
                            @if ($annonce->image)
                                <img src="{{ asset('storage/' . $annonce->image) }}" class="card-img-top" alt="Image de l'annonce">
                            @endif

                            <div class="card-footer text-muted">
                                Publié le {{ \Carbon\Carbon::parse($annonce->date_publication)->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>


</x-app-layout>
