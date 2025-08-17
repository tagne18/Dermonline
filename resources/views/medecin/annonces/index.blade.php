@extends('layouts.medecin')

@section('title', 'Rendez-vous reçus')

@section('content')
<style>
    .appointments-container {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 1rem 0;
    }
    
    .content-wrapper {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        margin: 0 auto;
        max-width: 1400px;
    }
    
    .page-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .page-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #343a40;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        color: #6c757d;
        font-size: 0.95rem;
        margin: 0;
    }
    
    .stats-info {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        color: #495057;
    }
    
    .clean-table {
        background: white;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #dee2e6;
    }
    
    .clean-table thead th {
        background: #f8f9fa;
        color: #495057;
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: 2px solid #dee2e6;
        padding: 1rem 0.75rem;
        text-align: left;
    }
    
    .clean-table tbody tr {
        border-bottom: 1px solid #f1f3f4;
        transition: background-color 0.2s ease;
    }
    
    .clean-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .clean-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border: none;
        font-size: 0.9rem;
    }
    
    .patient-name {
        font-weight: 500;
        color: #343a40;
        margin-bottom: 0.2rem;
    }
    
    .patient-phone {
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .date-display {
        color: #495057;
        font-weight: 500;
    }
    
    .motif-text {
        color: #495057;
        font-weight: 500;
    }
    
    .description-text {
        color: #6c757d;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .type-text {
        color: #495057;
        text-transform: capitalize;
    }
    
    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .status-en-attente {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .status-valide {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-refuse {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .patient-photo {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }
    
    .no-photo {
        width: 50px;
        height: 50px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 1.2rem;
    }
    
    .actions-group {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
    }
    
    .btn-simple {
        border: 1px solid;
        border-radius: 4px;
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .btn-success-simple {
        background: white;
        color: #28a745;
        border-color: #28a745;
    }
    
    .btn-success-simple:hover {
        background: #28a745;
        color: white;
    }
    
    .btn-danger-simple {
        background: white;
        color: #dc3545;
        border-color: #dc3545;
    }
    
    .btn-danger-simple:hover {
        background: #dc3545;
        color: white;
    }
    
    .btn-primary-simple {
        background: white;
        color: #007bff;
        border-color: #007bff;
    }
    
    .btn-primary-simple:hover {
        background: #007bff;
        color: white;
    }
    
    .btn-warning-simple {
        background: white;
        color: #ffc107;
        border-color: #ffc107;
    }
    
    .btn-warning-simple:hover {
        background: #ffc107;
        color: #212529;
    }
    
    .btn-info-simple {
        background: white;
        color: #17a2b8;
        border-color: #17a2b8;
    }
    
    .btn-info-simple:hover {
        background: #17a2b8;
        color: white;
    }
    
    .btn-secondary-simple {
        background: white;
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary-simple:hover {
        background: #6c757d;
        color: white;
    }
    
    .alert-simple {
        border-radius: 6px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid;
    }
    
    .alert-success-simple {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }
    
    .modal-simple .modal-content {
        border-radius: 6px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        border: 1px solid #dee2e6;
    }
    
    .modal-simple .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }
    
    .modal-simple .modal-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #343a40;
    }
    
    .modal-simple .modal-body {
        padding: 1.5rem;
    }
    
    .modal-simple .modal-footer {
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }
    
    .form-control-simple {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 0.6rem 0.75rem;
        font-size: 0.9rem;
        transition: border-color 0.2s ease;
    }
    
    .form-control-simple:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: 0;
    }
    
    .form-label-simple {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6c757d;
    }
    
    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.6;
    }

    
    @media (max-width: 768px) {
        .content-wrapper {
            margin: 0.5rem;
            padding: 1rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .actions-group {
            flex-direction: column;
        }
        
        .btn-simple {
            width: 100%;
            text-align: center;
        }
        
        .clean-table {
            font-size: 0.85rem;
        }
        
        .clean-table tbody td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>

<div class="appointments-container">
    <div class="content-wrapper">
        <div class="page-header">
            <h1 class="page-title">Rendez-vous reçus</h1>
            <p class="page-subtitle">Gestion des consultations patients</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-simple alert-success-simple">
                <strong>Succès :</strong> {{ session('success') }}
            </div>
        @endif
        
        <div class="stats-info">
            <strong>Information :</strong> {{ $appointments->count() }} rendez-vous trouvés pour le médecin ID: {{ auth()->id() }}
        </div>

        <!-- Barre de recherche et filtres -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Rechercher un patient...">
                            <button id="clearSearch" class="btn btn-outline-secondary" type="button" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="form-check form-switch d-inline-block me-3">
                            <input class="form-check-input" type="checkbox" id="filterPending">
                            <label class="form-check-label" for="filterPending">Afficher uniquement les RDV en attente</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table clean-table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Photo</th>
                        <th>Date & Heure</th>
                        <th>Motif</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $rdv)
                        <tr class="fade-in" data-status="{{ $rdv->statut }}" data-patient-name="{{ strtolower($rdv->patient_name ?? $rdv->user->name ?? '') }}">
                            <td>
                                <div class="patient-name">{{ $rdv->patient_name ?? $rdv->user->name ?? 'Patient inconnu' }}</div>
                                <div class="patient-phone">{{ $rdv->patient_phone ?? $rdv->user->phone ?? 'Téléphone non renseigné' }}</div>
                            </td>
                            <td>
                                @if(!empty($rdv->photos))
                                    <img src="{{ asset('storage/' . $rdv->photos) }}" alt="Photo du patient" class="patient-photo">
                                @else
                                    <div class="no-photo">👤</div>
                                @endif
                            </td>
                            <td>
                                <div class="date-display">{{ $rdv->date }}</div>
                            </td>
                            <td>
                                <div class="motif-text">{{ $rdv->motif }}</div>
                            </td>
                            <td>
                                <div class="description-text" title="{{ $rdv->description }}">{{ $rdv->description }}</div>
                            </td>
                            <td>
                                <div class="type-text">{{ ucfirst($rdv->type ?? 'Non défini') }}</div>
                            </td>
                            <td>
                                @if($rdv->statut == 'en_attente')
                                    <span class="status-badge status-en-attente">En attente</span>
                                @elseif($rdv->statut == 'valide')
                                    <span class="status-badge status-valide">Validé</span>
                                @elseif($rdv->statut == 'refuse')
                                    <span class="status-badge status-refuse">Refusé</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-group">
                                    @if($rdv->statut == 'en_attente')
                                        <form method="POST" action="{{ route('medecin.appointments.validate', $rdv->id) }}" class="d-inline form-validate">
                                            @csrf
                                            <button type="submit" class="btn btn-simple btn-success-simple" onclick="return confirmAction(this, 'Valider ce rendez-vous ?')">
                                                <span class="button-text">Valider</span>
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('medecin.appointments.refuse', $rdv->id) }}" class="d-inline form-refuse">
                                            @csrf
                                            <button type="button" class="btn btn-simple btn-danger-simple" onclick="confirmRefuse(this)">
                                                <span class="button-text">Refuser</span>
                                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                            </button>
                                        </form>
                                        <a href="{{ route('medecin.prescriptions.create', ['appointment' => $rdv->id]) }}" class="btn btn-simple btn-primary-simple">Prescription</a>
                                        <button type="button" class="btn btn-simple btn-warning-simple" data-bs-toggle="modal" data-bs-target="#rescheduleModal" 
                                            data-appointment-id="{{ $rdv->id }}" data-patient-name="{{ $rdv->patient_name ?? $rdv->user->name ?? 'Patient inconnu' }}">
                                            Reprogrammer
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-simple btn-info-simple" data-bs-toggle="modal" data-bs-target="#messageModal" 
                                        data-patient-id="{{ $rdv->user_id ?? $rdv->patient_id }}" data-patient-name="{{ $rdv->patient_name ?? $rdv->user->name ?? 'Patient inconnu' }}">
                                        Message
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-state">
                                <div class="empty-icon">📅</div>
                                <h5>Aucun rendez-vous reçu</h5>
                                <p>Les rendez-vous de vos patients apparaîtront ici.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Reprogrammation -->
<div class="modal fade modal-simple" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('medecin.appointments.reschedule') }}">
        @csrf
        <input type="hidden" name="appointment_id" id="rescheduleAppointmentId">
        <div class="modal-header">
          <h5 class="modal-title" id="rescheduleModalLabel">Reprogrammer le rendez-vous</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label form-label-simple">Patient</label>
            <input type="text" class="form-control form-control-simple" id="reschedulePatientName" disabled>
          </div>
          <div class="mb-3">
            <label for="new_date" class="form-label form-label-simple">Nouvelle date</label>
            <input type="date" class="form-control form-control-simple" name="new_date" required>
          </div>
          <div class="mb-3">
            <label for="new_time" class="form-label form-label-simple">Nouvelle heure</label>
            <input type="time" class="form-control form-control-simple" name="new_time" required>
          </div>
          <div class="info-box">
            <strong>Information :</strong> Le patient recevra une notification et devra valider ou refuser ce nouveau créneau.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-simple btn-secondary-simple" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-simple btn-warning-simple">Envoyer la proposition</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Messagerie -->
<div class="modal fade modal-simple" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('medecin.messages.send') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="receiver_id" id="messagePatientId">
        <div class="modal-header">
          <h5 class="modal-title" id="messageModalLabel">Envoyer un message au patient</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label form-label-simple">Patient</label>
            <input type="text" class="form-control form-control-simple" id="messagePatientName" disabled>
          </div>
          <div class="mb-3">
            <label for="message" class="form-label form-label-simple">Message</label>
            <textarea class="form-control form-control-simple" name="message" rows="3" required placeholder="Saisissez votre message ici..."></textarea>
          </div>
          <div class="mb-3">
            <label for="attachment" class="form-label form-label-simple">Fichier à joindre</label>
            <input type="file" class="form-control form-control-simple" name="attachment" accept="image/*,.pdf,.doc,.docx,.png,.jpg,.jpeg">
            <small class="text-muted">Optionnel. Images ou documents acceptés (Max 5 Mo).</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-simple btn-secondary-simple" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-simple btn-info-simple">Envoyer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Pré-remplir le modal de reprogrammation
var rescheduleModal = document.getElementById('rescheduleModal');
rescheduleModal && rescheduleModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var appointmentId = button.getAttribute('data-appointment-id');
    var patientName = button.getAttribute('data-patient-name');
    document.getElementById('rescheduleAppointmentId').value = appointmentId;
    document.getElementById('reschedulePatientName').value = patientName;
});

// Pré-remplir le modal de messagerie
var messageModal = document.getElementById('messageModal');
messageModal && messageModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var patientId = button.getAttribute('data-patient-id');
    var patientName = button.getAttribute('data-patient-name');
    document.getElementById('messagePatientId').value = patientId;
    document.getElementById('messagePatientName').value = patientName;
});

// Gestion de la recherche
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearSearch = document.getElementById('clearSearch');
    const filterPending = document.getElementById('filterPending');
    const tableRows = document.querySelectorAll('.clean-table tbody tr');

    // Fonction de recherche
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const showOnlyPending = filterPending.checked;

        tableRows.forEach(row => {
            const patientName = row.querySelector('.patient-name')?.textContent.toLowerCase() || '';
            const statusBadge = row.querySelector('.status-badge');
            const isPending = statusBadge && statusBadge.textContent.includes('attente');
            
            const matchesSearch = patientName.includes(searchTerm);
            const matchesFilter = !showOnlyPending || isPending;
            
            row.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
        });
    }

    // Événements
    searchInput.addEventListener('input', function() {
        clearSearch.style.display = this.value ? 'block' : 'none';
        filterTable();
    });

    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        this.style.display = 'none';
        filterTable();
    });

    filterPending.addEventListener('change', filterTable);

    // Activer les tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Confirmation de suppression/refus
function confirmAction(button, message) {
    if (!confirm(message)) {
        return false;
    }
    showLoading(button);
    return true;
}

function confirmRefuse(button) {
    if (confirm('Êtes-vous sûr de vouloir refuser ce rendez-vous ? Cette action est irréversible.')) {
        showLoading(button);
        button.closest('form').submit();
    } else {
        return false;
    }
}

// Gestion des états de chargement
function showLoading(button) {
    const form = button.closest('form');
    const buttons = form ? form.querySelectorAll('button[type="submit"], button[type="button"]') : [button];
    
    buttons.forEach(btn => {
        const spinner = btn.querySelector('.spinner-border');
        const buttonText = btn.querySelector('.button-text');
        
        if (spinner) spinner.classList.remove('d-none');
        if (buttonText) buttonText.textContent = 'Traitement...';
        btn.disabled = true;
    });
}

// Désactiver la double soumission
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton && submitButton.disabled) {
            e.preventDefault();
            return false;
        }
        showLoading(submitButton);
    });
});

// Gestion des confirmations de suppression
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ? Cette action est irréversible.')) {
                e.preventDefault();
                return false;
            }
            showLoading(this);
        });
    });
});
</script>

@endsection