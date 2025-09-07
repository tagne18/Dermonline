@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Utilisateurs bloqués</h1>
        <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des utilisateurs bloqués</h6>
            <span class="badge badge-danger">{{ $users->count() }} utilisateur(s) bloqué(s)</span>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date de blocage</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>#{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? 'Non renseigné' }}</td>
                                <td>{{ $user->blocked_at ? \Carbon\Carbon::parse($user->blocked_at)->format('d/m/Y H:i') : 'Date inconnue' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success" 
                                            data-toggle="modal" 
                                            data-target="#unblockUserModal{{ $user->id }}">
                                        <i class="fas fa-unlock"></i> Débloquer
                                    </button>
                                    
                                    <!-- Modal de confirmation de déblocage -->
                                    <div class="modal fade" id="unblockUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-unlock-alt me-2"></i>
                                                        Confirmer le déblocage
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.utilisateurs.debloquer', $user) }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir débloquer cet utilisateur ?</p>
                                                        <div class="alert alert-info">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            L'utilisateur pourra à nouveau se connecter à son compte.
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-unlock-alt me-1"></i> Confirmer le déblocage
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <a href="#" class="btn btn-sm btn-info" 
                                       data-toggle="modal" 
                                       data-target="#blockReasonModal{{ $user->id }}"
                                       title="Voir la raison du blocage">
                                        <i class="fas fa-info-circle"></i>
                                    </a>

                                    <!-- Modal pour la raison du blocage -->
                                    <div class="modal fade" id="blockReasonModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Raison du blocage</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Raison :</strong></p>
                                                    <p>{{ $user->block_reason ?? 'Aucune raison spécifiée' }}</p>
                                                    
                                                    @if($user->blocked_at)
                                                        <p class="text-muted mt-3">
                                                        <small>Bloqué le : {{ \Carbon\Carbon::parse($user->blocked_at)->format('d/m/Y à H:i') }}</small>
                                                    </p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Aucun utilisateur n'est actuellement bloqué.
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.8rem;
        padding: 0.35em 0.65em;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialisation des tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
@endsection
