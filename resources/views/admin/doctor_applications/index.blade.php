@extends('layouts.admin') <!-- ou layouts.app selon ton template -->

@section('content')@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@elseif(session('info'))
<div class="alert alert-info">
    {{ session('info') }}
</div>

@if(session('approved_password'))
<!-- Modal Mot de passe par défaut -->
<div class="modal fade" id="approvedModal" tabindex="-1" aria-labelledby="approvedModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-success text-white">
      <div class="modal-header">
        <h5 class="modal-title" id="approvedModalLabel">Compte Médecin Approuvé ✅</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body">
        <p><strong>Email :</strong> {{ session('approved_email') }}</p>
        <p><strong>Mot de passe par défaut :</strong> <code>{{ session('approved_password') }}</code></p>
        <p>⚠️ Demandez au médecin de le modifier à sa première connexion.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Script pour ouvrir le modal automatiquement -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var approvedModal = new bootstrap.Modal(document.getElementById('approvedModal'));
        approvedModal.show();
    });
</script>
@endif

@endif


<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <span class="text-white mt-5 fz-5">Demandes des Médecins</span>
    <a href="{{ route('admin.medecins.index') }}" class="btn btn-info">👨‍⚕️ Liste des Médecins</a>
</div>

    <table class="table text-white table-bordered border-success ">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Spécialité</th>
                <th>Ville</th>
                <th>Langue</th>
                <th>Lieu de travail</th>
                <th>Matricule</th>
                <th>Licence</th>
                <th>Expérience</th>
                <th>Domaine d'expertise</th>
                <th>CV</th>
                <th>Approuvée le</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $app)
                <tr>
                    <td>{{ $app->name }}</td>
                    <td>{{ $app->email }}</td>
                    <td>{{ $app->phone }}</td>
                    <td>{{ $app->specialite }}</td>
                    <td>{{ $app->ville }}</td>
                    <td>{{ $app->langue === 'fr' ? 'Français' : 'Anglais' }}</td>
                    <td>{{ $app->lieu_travail }}</td>
                    <td>{{ $app->matricule_professionnel }}</td>
                    <td>{{ $app->numero_licence }}</td>
                    <td>{{ $app->experience }}</td>
                    <td>{{ $app->expertise }}</td>
                    <td>
                        @if ($app->cv)
                            <a href="{{ asset('storage/' . $app->cv) }}" target="_blank" class="btn btn-outline-primary">
                                Télécharger le CV
                            </a>
                        @else
                            <span class="text-muted">Aucun CV disponible</span>
                        @endif
                    </td>
                    <td>
                        @if($app->approved_at)
                        {{ \Carbon\Carbon::parse($app->approved_at)->format('d/m/Y à H:i') }}

                        @else
                        @php
                            $userExists = in_array($app->email, $users);
                        @endphp

                        @if ($app->status === 'approved' && !$userExists)
                            <span class="text-warning">Compte non encore créé</span>
                            <form action="{{ route('admin.doctor_applications.create_user', $app->id) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm">Créer le compte</button>
                            </form>
                        @else
                            <span class="text-muted">Aucune action disponible</span>
                        @endif
                    @endif

                    </td>

                    <td>
                        @if($app->status === 'approved')
                            <span class="badge bg-success">Approuvée</span>
                        @elseif($app->status === 'rejected')
                            <span class="badge bg-danger">Rejetée</span>
                        @else
                            <span class="badge bg-warning text-dark">En attente</span>
                        @endif
                    </td>

                    <td>
                        @if($app->status === 'pending')
                            <form action="{{ route('admin.doctor_applications.approve', $app->id) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-success btn-sm" type="submit">✅ Approuver</button>
                            </form>
                            <form action="{{ route('admin.doctor_applications.reject', $app->id) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-danger btn-sm" type="submit">❌ Rejeter</button>
                            </form>
                        @else
                            <span class="text-muted">Aucune action disponible</span>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    <form action="{{ route('admin.doctor_applications.deleteRejected') }}" method="POST" onsubmit="return confirm('Confirmer la suppression des demandes rejetées ?');">
        @csrf
        @method('DELETE')
        <!-- Bouton pour ouvrir le modal -->
<button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
    🗑️ Supprimer les demandes rejetées
</button>

    </form>
<!-- Modal de confirmation de suppression -->
<div class="modal fade " id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark text-white modal-transparent">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          ⚠️ Cette action supprimera <strong>toutes les demandes rejetées</strong> de façon permanente. Voulez-vous continuer ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <!-- Formulaire de suppression -->
          <form action="{{ route('admin.doctor_applications.deleteRejected') }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">Oui, supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark text-white modal-transparent">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmer la suppression</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          ⚠️ Cette action supprimera <strong>toutes les demandes rejetées</strong> de façon permanente. Voulez-vous continuer ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <!-- Formulaire de suppression -->
          <form action="{{ route('admin.doctor_applications.deleteRejected') }}" method="POST">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger">Oui, supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <style>
      .modal-transparent {
          background-color: rgba(33, 37, 41, 0.121); /* noir transparent */
          backdrop-filter: blur(6px); /* effet de flou subtil */
          border-radius: 10px;
      }
  </style>

@endsection
