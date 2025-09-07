@extends('layouts.medecin')

@section('title', 'Détails du planning')

@push('styles')
    <style>
        .detail-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .detail-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 1.5rem 2rem;
        }
        
        .detail-body {
            padding: 2rem;
        }
        
        .detail-section {
            margin-bottom: 2rem;
        }
        
        .detail-section:last-child {
            margin-bottom: 0;
        }
        
        .detail-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
        }
        
        .detail-title i {
            margin-right: 0.75rem;
            color: #4e73df;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 1rem;
        }
        
        .detail-label {
            width: 200px;
            font-weight: 500;
            color: #4a5568;
        }
        
        .detail-value {
            flex: 1;
            color: #2d3748;
        }
        
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
        }
        
        .badge-status i {
            margin-right: 0.5rem;
        }
        
        .badge-status.planifie {
            background-color: #f6c23e33;
            color: #f6c23e;
        }
        
        .badge-status.confirme {
            background-color: #1cc88a33;
            color: #1cc88a;
        }
        
        .badge-status.annule {
            background-color: #e74a3b33;
            color: #e74a3b;
        }
        
        .badge-status.termine {
            background-color: #6c757d33;
            color: #6c757d;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.75rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.65rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-action i {
            margin-right: 0.5rem;
        }
        
        .btn-edit {
            background-color: #4e73df;
            color: white;
            border: 1px solid #4e73df;
        }
        
        .btn-edit:hover {
            background-color: #3a5ccc;
            border-color: #3a5ccc;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }
        
        .btn-back {
            background-color: white;
            color: #4a5568;
            border: 1px solid #e2e8f0;
        }
        
        .btn-back:hover {
            background-color: #f8f9fc;
            border-color: #cbd5e0;
            color: #2d3748;
            transform: translateY(-2px);
        }
        
        .btn-delete {
            background-color: #e74a3b;
            color: white;
            border: 1px solid #e74a3b;
            margin-left: auto;
        }
        
        .btn-delete:hover {
            background-color: #d52b1a;
            border-color: #d52b1a;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(231, 74, 59, 0.3);
        }
        
        .description-box {
            background-color: #f8f9fc;
            border-left: 4px solid #4e73df;
            padding: 1.25rem;
            border-radius: 0 8px 8px 0;
            margin-top: 0.5rem;
        }
        
        .empty-description {
            color: #a0aec0;
            font-style: italic;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: #718096;
            font-size: 0.875rem;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h2 mb-1 text-gray-800">
                <i class="fas fa-calendar-alt me-2"></i>Détails du planning
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('medecin.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('medecin.planning.index') }}">Mes plannings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Détails</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('medecin.planning.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            @if($planning->statut == 'planifie')
                <a href="{{ route('medecin.planning.edit', $planning) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Modifier
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="detail-card">
                <div class="detail-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 mb-0">{{ $planning->titre }}</h2>
                        <span class="badge-status {{ $planning->statut }}">
                            <i class="fas {{ $planning->statut == 'planifie' ? 'fa-clock' : ($planning->statut == 'confirme' ? 'fa-check-circle' : ($planning->statut == 'annule' ? 'fa-times-circle' : 'fa-calendar-check')) }}"></i>
                            {{ ucfirst($planning->statut) }}
                        </span>
                    </div>
                </div>
                
                <div class="detail-body">
                    <div class="detail-section">
                        <h3 class="detail-title">
                            <i class="far fa-calendar-alt"></i>
                            Informations générales
                        </h3>
                        
                        <div class="detail-row">
                            <div class="detail-label">Date de consultation</div>
                            <div class="detail-value">
                                {{ $planning->date_consultation->isoFormat('dddd D MMMM YYYY', 'fr') }}
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Plage horaire</div>
                            <div class="detail-value">
                                De <strong>{{ $planning->heure_debut }}</strong> à <strong>{{ $planning->heure_fin }}</strong>
                                <span class="text-muted ms-2">({{ $planning->duree_formattee }})</span>
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Type de consultation</div>
                            <div class="detail-value">
                                <i class="fas fa-{{ $planning->type_consultation == 'en_ligne' ? 'video' : ($planning->type_consultation == 'presentiel' ? 'building' : 'exchange-alt') }} me-2"></i>
                                {{ $planning->type_consultation_libelle }}
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Prix de la consultation</div>
                            <div class="detail-value">
                                <span class="h5 mb-0">{{ number_format($planning->prix, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Date de création</div>
                            <div class="detail-value">
                                {{ $planning->created_at->isoFormat('dddd D MMMM YYYY à HH:mm', 'fr') }}
                            </div>
                        </div>
                        
                        @if($planning->updated_at != $planning->created_at)
                            <div class="detail-row">
                                <div class="detail-label">Dernière mise à jour</div>
                                <div class="detail-value">
                                    {{ $planning->updated_at->isoFormat('dddd D MMMM YYYY à HH:mm', 'fr') }}
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="detail-section">
                        <h3 class="detail-title">
                            <i class="far fa-file-alt"></i>
                            Description
                        </h3>
                        
                        @if($planning->description)
                            <div class="description-box">
                                {!! nl2br(e($planning->description)) !!}
                            </div>
                        @else
                            <p class="empty-description">Aucune description fournie pour ce planning.</p>
                        @endif
                    </div>
                    
                    <div class="action-buttons">
                        <a href="{{ route('medecin.planning.index') }}" class="btn-action btn-back">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                        
                        @if($planning->statut == 'planifie')
                            <a href="{{ route('medecin.planning.edit', $planning) }}" class="btn-action btn-edit">
                                <i class="far fa-edit"></i> Modifier
                            </a>
                            
                            <form action="{{ route('medecin.planning.destroy', $planning) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce planning ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="far fa-trash-alt"></i> Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="detail-card">
                <div class="detail-header">
                    <h2 class="h4 mb-0">Statistiques</h2>
                </div>
                <div class="detail-body">
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-12">
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: rgba(78, 115, 223, 0.1); color: #4e73df;">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="stat-value">
                                    {{ $planning->date_consultation->diffForHumans() }}
                                </div>
                                <div class="stat-label">
                                    {{ $planning->date_consultation->isToday() ? 'Aujourd\'hui' : ($planning->date_consultation->isTomorrow() ? 'Demain' : '') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-12">
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: rgba(28, 200, 138, 0.1); color: #1cc88a;">
                                    <i class="fas fa-stopwatch"></i>
                                </div>
                                <div class="stat-value">{{ $planning->duree_formattee }}</div>
                                <div class="stat-label">Durée de consultation</div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-12">
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: rgba(231, 74, 59, 0.1); color: #e74a3b;">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <div class="stat-value">
                                    {{ $planning->date_consultation->isPast() ? 'Passé' : 'À venir' }}
                                </div>
                                <div class="stat-label">
                                    {{ $planning->date_consultation->isPast() ? 'Cette date est passée' : 'Disponible pour réservation' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-12">
                            <div class="stat-card">
                                <div class="stat-icon" style="background-color: rgba(108, 117, 125, 0.1); color: #6c757d;">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="stat-value">
                                    {{ ucfirst($planning->statut) }}
                                </div>
                                <div class="stat-label">
                                    @if($planning->statut == 'planifie')
                                        En attente de confirmation
                                    @elseif($planning->statut == 'confirme')
                                        Confirmé et actif
                                    @elseif($planning->statut == 'annule')
                                        Annulé et non disponible
                                    @else
                                        Terminé et archivé
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if($planning->statut == 'planifie')
                <div class="alert alert-warning mt-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <h6 class="mb-1">En attente de confirmation</h6>
                            <p class="mb-0 small">Ce planning est en attente de confirmation. Vous pouvez encore le modifier ou le supprimer si nécessaire.</p>
                        </div>
                    </div>
                </div>
            @elseif($planning->statut == 'confirme')
                <div class="alert alert-success mt-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>
                            <h6 class="mb-1">Planning confirmé</h6>
                            <p class="mb-0 small">Ce planning est actif et visible pour les patients. Vous ne pouvez plus le modifier.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
