<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Toutes les annonces') }}
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="container border-success py-3">
            <h1 class="mb-4">Dernières annonces</h1>
        </div>

        @if ($annonces->isEmpty())
            <div class="alert alert-info">Aucune annonce disponible pour le moment.</div>
        @else
            <div class="row">
                @foreach ($annonces as $annonce)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if ($annonce->image)
                                <img src="{{ asset('storage/' . $annonce->image) }}" class="card-img-top" alt="Image de l'annonce" style="max-height: 200px; object-fit: cover;">
                            @endif
                            
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $annonce->titre }}</h5>
                                    <span class="badge {{ $annonce->type === 'medecin' ? 'bg-primary' : 'bg-success' }} text-white">
                                        {{ $annonce->type === 'medecin' ? 'Médecin' : 'Administration' }}
                                    </span>
                                </div>
                                
                                <div class="card-text mb-3 flex-grow-1">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($annonce->contenu), 150) !!}
                                </div>
                                
                                <a href="#" class="btn btn-outline-primary btn-sm align-self-start" data-bs-toggle="modal" data-bs-target="#annonceModal{{ $loop->index }}">
                                    Voir plus
                                </a>
                            </div>
                            
                            <div class="card-footer bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $annonce->created_at->format('d/m/Y') }}
                                    </small>
                                    <small>
                                        @if($annonce->type === 'medecin' && $annonce->user)
                                            <i class="fas fa-user-md me-1"></i>
                                            Dr. {{ $annonce->user->name }}
                                        @else
                                            <i class="fas fa-shield-alt me-1"></i>
                                            Administration
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal pour afficher le contenu complet -->
                    <div class="modal fade" id="annonceModal{{ $loop->index }}" tabindex="-1" aria-labelledby="annonceModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="annonceModalLabel">{{ $annonce->titre }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    @if($annonce->image)
                                        <img src="{{ asset('storage/' . $annonce->image) }}" class="img-fluid rounded mb-3" alt="Image de l'annonce">
                                    @endif
                                    <div class="mb-3">
                                        {!! $annonce->contenu !!}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <small class="text-muted me-auto">
                                        Publié le {{ $annonce->created_at->format('d/m/Y à H:i') }}
                                        @if($annonce->type === 'medecin' && $annonce->user)
                                            par <strong>Dr. {{ $annonce->user->name }}</strong>
                                        @else
                                            par <strong>l'administration</strong>
                                        @endif
                                    </small>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease-in-out;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .card-title {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
    
    .modal-content {
        border: none;
        border-radius: 10px;
    }
    
    .modal-header {
        border-bottom: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
    
    .modal-footer {
        border-top: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
</style>
@endpush
