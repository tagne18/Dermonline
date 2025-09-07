@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Détail de l'ordonnance</h2>
    <div class="card mb-3 mt-3">
        <div class="card-body">
            <p><strong>Titre :</strong> {{ $prescription->titre }}</p>
            
            @if($prescription->description)
                <p class="mt-3"><strong>Description :</strong><br>{{ $prescription->description }}</p>
            @endif
            
            <p class="mt-3"><strong>Médecin :</strong> 
                Dr. {{ $prescription->medecin->first_name ?? '' }} {{ $prescription->medecin->last_name ?? $prescription->medecin->name ?? '-' }}
            </p>
            <p><strong>Date de prescription :</strong> {{ \Carbon\Carbon::parse($prescription->date_prescription)->format('d/m/Y') }}</p>
            
            @if($prescription->date_emission)
                <p><strong>Date d'émission :</strong> {{ \Carbon\Carbon::parse($prescription->date_emission)->format('d/m/Y') }}</p>
            @endif
            
            @if($prescription->date_expiration)
                <p><strong>Date d'expiration :</strong> {{ \Carbon\Carbon::parse($prescription->date_expiration)->format('d/m/Y') }}</p>
            @endif
            
            <p><strong>Statut :</strong> 
                <span class="badge {{ $prescription->statut === 'active' ? 'bg-success' : 'bg-warning' }}">
                    {{ ucfirst($prescription->statut) }}
                </span>
            </p>
            
            @if($prescription->commentaires)
                <p class="mt-3"><strong>Commentaires :</strong><br>{{ $prescription->commentaires }}</p>
            @endif
            
            <div class="mt-4">
                @if($prescription->fichier_pdf)
                    <a href="{{ route('patient.ordonnances.download', $prescription->id) }}" class="btn btn-primary">
                        <i class="fas fa-download me-1"></i> Télécharger le PDF
                    </a>
                @endif
                
                <a href="{{ route('patient.ordonnances.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                </a>
            </div>
            
            <!-- Liste des médicaments -->
            <!-- Fichiers joints -->
            @if($prescription->fichiers->isNotEmpty())
                <div class="mt-4">
                    <h5 class="mb-3">Fichiers joints</h5>
                    <div class="list-group">
                        @foreach($prescription->fichiers as $fichier)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file me-2"></i>
                                        <span>{{ $fichier->nom_original }}</span>
                                        <small class="text-muted ms-2">({{ strtoupper($fichier->extension) }}, {{ number_format($fichier->taille / 1024, 1) }} KB)</small>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ route('patient.ordonnances.fichiers.afficher', $fichier->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           target="_blank"
                                           title="Voir le fichier">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('patient.ordonnances.fichiers.telecharger', $fichier->id) }}" 
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Télécharger">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Médicaments prescrits -->
            @if($prescription->medicaments->isNotEmpty())
                <div class="mt-4">
                    <h5 class="mb-3">Médicaments prescrits</h5>
                    <div class="list-group">
                        @foreach($prescription->medicaments as $medicament)
                            @php $pivot = $medicament->pivot; @endphp
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-1">
                                        {{ $medicament->nom }}
                                        @if($medicament->forme_pharmaceutique)
                                            <small class="text-muted">({{ $medicament->forme_pharmaceutique }})</small>
                                        @endif
                                    </h6>
                                    @if($medicament->est_sur_ordonnance)
                                        <span class="badge bg-warning">Sur ordonnance</span>
                                    @endif
                                </div>
                                
                                <div class="mt-2">
                                    @if($pivot->posologie ?? false)
                                        <p class="mb-1"><small class="text-muted">Posologie :</small> {{ $pivot->posologie }}</p>
                                    @endif
                                    
                                    @if($pivot->duree ?? false)
                                        <p class="mb-1"><small class="text-muted">Durée :</small> {{ $pivot->duree }}</p>
                                    @endif
                                    
                                    @if($pivot->instructions ?? false)
                                        <p class="mb-0"><small class="text-muted">Instructions :</small> {{ $pivot->instructions }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
