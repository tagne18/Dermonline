@extends('layouts.medecin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Liste des patients</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Patients ayant pris rendez-vous</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Dernier rendez-vous</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->email }}</td>
                                <td>{{ $patient->phone ?? 'Non renseigné' }}</td>
                                <td>
                                    @if($patient->last_appointment)
                                        {{ $patient->last_appointment->date->format('d/m/Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $patient->is_subscribed ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $patient->is_subscribed ? 'Abonné' : 'Non abonné' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('medecin.messages.create', ['user' => $patient->id]) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="Envoyer un message">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    @if(!$patient->is_subscribed)
                                        <button class="btn btn-sm btn-success" 
                                                data-toggle="modal" 
                                                data-target="#subscribeModal{{ $patient->id }}"
                                                title="Proposer un abonnement">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal pour proposer un abonnement -->
                            <div class="modal fade" id="subscribeModal{{ $patient->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('medecin.abonnements.propose', $patient) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Proposer un abonnement</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="type">Type d'abonnement :</label>
                                                    <select class="form-control" id="type" name="type" required>
                                                        <option value="mensuel">Mensuel - 10 000 FCFA</option>
                                                        <option value="trimestriel">Trimestriel - 25 000 FCFA</option>
                                                        <option value="annuel">Annuel - 80 000 FCFA</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="message">Message (optionnel) :</label>
                                                    <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Envoyer la proposition</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun patient trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endpush
