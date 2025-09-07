@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Détails de la consultation</h1>
        <a href="{{ route('admin.consultations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Informations générales</h6>
                    @php
                        $statusClass = [
                            'scheduled' => 'info',
                            'completed' => 'success',
                            'cancelled' => 'danger'
                        ][$consultation->status] ?? 'secondary';
                    @endphp
                    <span class="badge badge-{{ $statusClass }}">
                        {{ $statuses[$consultation->status] ?? $consultation->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">ID de la consultation :</div>
                        <div class="col-md-8">#{{ $consultation->id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Date :</div>
                        <div class="col-md-8">{{ $consultation->date->format('d/m/Y') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Heure :</div>
                        <div class="col-md-8">{{ $consultation->time ?? 'Non spécifiée' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Médecin :</div>
                        <div class="col-md-8">
                            {{ $consultation->medecin->name ?? 'N/A' }}
                            @if(isset($consultation->medecin))
                                <a href="#" class="btn btn-sm btn-link">
                                    <i class="fas fa-envelope"></i> Contacter
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Patient :</div>
                        <div class="col-md-8">
                            {{ $consultation->patient->name ?? 'N/A' }}
                            @if(isset($consultation->patient))
                                <a href="#" class="btn btn-sm btn-link">
                                    <i class="fas fa-envelope"></i> Contacter
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Motif :</div>
                        <div class="col-md-8">{{ $consultation->reason ?? 'Non spécifié' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Notes :</div>
                        <div class="col-md-8">{{ $consultation->notes ?? 'Aucune note' }}</div>
                    </div>
                </div>
            </div>

            @if($consultation->examen)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Examen associé</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Type d'examen :</strong> {{ $consultation->examen->type ?? 'N/A' }}</p>
                            <p><strong>Résultats :</strong> {{ $consultation->examen->results ?? 'Non disponibles' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Date de l'examen :</strong> {{ $consultation->examen->date->format('d/m/Y') ?? 'N/A' }}</p>
                            <p><strong>Statut :</strong> 
                                <span class="badge {{ $consultation->examen->status === 'completed' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $consultation->examen->status === 'completed' ? 'Terminé' : 'En attente' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    @if($consultation->examen->notes)
                        <div class="mt-3">
                            <h6>Notes complémentaires :</h6>
                            <p class="border p-3 rounded">{{ $consultation->examen->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($consultation->status !== 'completed')
                            <button type="button" class="btn btn-success mb-2" data-toggle="modal" data-target="#completeConsultationModal">
                                <i class="fas fa-check"></i> Marquer comme terminée
                            </button>
                        @endif
                        
                        @if($consultation->status !== 'cancelled')
                            <button type="button" class="btn btn-danger mb-2" data-toggle="modal" data-target="#cancelConsultationModal">
                                <i class="fas fa-times"></i> Annuler la consultation
                            </button>
                        @endif

                        <a href="#" class="btn btn-primary mb-2">
                            <i class="fas fa-file-pdf"></i> Générer un PDF
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Historique des modifications</h6>
                </div>
                <div class="card-body">
                    @if($consultation->activities->count() > 0)
                        <ul class="list-unstyled">
                            @foreach($consultation->activities->sortByDesc('created_at') as $activity)
                                <li class="mb-2">
                                    <div class="font-weight-bold">
                                        {{ $activity->description }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Aucun historique disponible</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour la complétion de la consultation -->
<div class="modal fade" id="completeConsultationModal" tabindex="-1" role="dialog" aria-labelledby="completeConsultationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeConsultationModalLabel">Confirmer la fin de la consultation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.consultations.update', $consultation) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="completed">
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir marquer cette consultation comme terminée ?</p>
                    <div class="form-group">
                        <label for="notes">Notes complémentaires (optionnel) :</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour l'annulation de la consultation -->
<div class="modal fade" id="cancelConsultationModal" tabindex="-1" role="dialog" aria-labelledby="cancelConsultationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelConsultationModalLabel">Annuler la consultation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.consultations.update', $consultation) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="cancelled">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cancellation_reason">Motif de l'annulation :</label>
                        <select class="form-control" id="cancellation_reason" name="cancellation_reason" required>
                            <option value="">Sélectionnez un motif</option>
                            <option value="patient_request">Demande du patient</option>
                            <option value="doctor_unavailable">Médecin indisponible</option>
                            <option value="other">Autre raison</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Détails (optionnel) :</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialisation des tooltips Bootstrap
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
