@extends('layouts.medecin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Mes ordonnances</h1>
        <a href="{{ route('medecin.ordonnances.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouvelle ordonnance
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <!-- Barre de recherche et filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('medecin.ordonnances.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="Date de début">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="Date de fin">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filtrer
                    </button>
                </div>
                @if(request()->hasAny(['search', 'date_from', 'date_to']))
                    <div class="col-12">
                        <a href="{{ route('medecin.ordonnances.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Réinitialiser les filtres
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer cette ordonnance ? Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="align-middle">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'patient', 'direction' => request('sort') === 'patient' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                            Patient
                            @if(request('sort') === 'patient')
                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                            @else
                                <i class="fas fa-sort ms-1 text-muted"></i>
                            @endif
                        </a>
                    </th>
                    <th class="align-middle">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'titre', 'direction' => request('sort') === 'titre' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                            Titre
                            @if(request('sort') === 'titre')
                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                            @else
                                <i class="fas fa-sort ms-1 text-muted"></i>
                            @endif
                        </a>
                    </th>
                    <th class="align-middle">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_prescription', 'direction' => request('sort') === 'date_prescription' && request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="text-decoration-none text-dark">
                            Date
                            @if(request('sort') === 'date_prescription')
                                <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                            @else
                                <i class="fas fa-sort ms-1 text-muted"></i>
                            @endif
                        </a>
                    </th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        <tbody>
            @forelse($ordonnances as $ordonnance)
                <tr>
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-2">
                                <i class="fas fa-user-circle fa-lg text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-medium">{{ $ordonnance->patient->name ?? 'Patient inconnu' }}</div>
                                <small class="text-muted">{{ $ordonnance->patient->email ?? '' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="fw-medium">{{ $ordonnance->titre }}</div>
                        @if($ordonnance->description)
                            <small class="text-muted">{{ Str::limit($ordonnance->description, 50) }}</small>
                        @endif
                    </td>
                    <td class="align-middle">
                        <div class="d-flex flex-column">
                            <span class="badge bg-light text-dark mb-1">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ \Carbon\Carbon::parse($ordonnance->date_prescription)->translatedFormat('d M Y') }}
                            </span>
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>
                                {{ $ordonnance->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex gap-2">
                            <a href="{{ route('medecin.ordonnances.show', $ordonnance) }}" 
                               class="btn-action btn-view" 
                               data-bs-toggle="tooltip" 
                               title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('medecin.ordonnances.edit', $ordonnance) }}" 
                               class="btn-action btn-edit"
                               data-bs-toggle="tooltip"
                               title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($ordonnance->fichier)
                            <a href="{{ Storage::url($ordonnance->fichier) }}" 
                               class="btn-action btn-view"
                               target="_blank"
                               data-bs-toggle="tooltip"
                               title="Télécharger le fichier">
                                <i class="fas fa-download"></i>
                            </a>
                            @endif
                            <button type="button" 
                                    class="btn-action btn-delete delete-btn"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"
                                    data-url="{{ route('medecin.ordonnances.destroy', $ordonnance) }}"
                                    data-patient="{{ $ordonnance->patient->name ?? 'cette ordonnance' }}"
                                    data-bs-toggle="tooltip"
                                    title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p class="mb-0">Aucune ordonnance trouvée</p>
                            @if(request()->hasAny(['search', 'date_from', 'date_to']))
                                <p class="small">Essayez de modifier vos critères de recherche</p>
                            @else
                                <p class="small">Commencez par créer une nouvelle ordonnance</p>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Pagination -->
    @if($ordonnances->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Affichage de <strong>{{ $ordonnances->firstItem() }}</strong> à <strong>{{ $ordonnances->lastItem() }}</strong> sur <strong>{{ $ordonnances->total() }}</strong> ordonnances
            </div>
            <nav>
                {{ $ordonnances->withQueryString()->links() }}
            </nav>
        </div>
    @endif

    @push('styles')
    <style>
        .btn-action {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .btn-view {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
            border: 1px solid rgba(13, 110, 253, 0.2);
        }
        .btn-edit {
            color: #ffc107;
            background-color: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.2);
        }
        .btn-delete {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
            border: 1px solid rgba(220, 53, 69, 0.2);
        }
        .btn-view:hover {
            background-color: rgba(13, 110, 253, 0.2);
            color: #0d6efd;
        }
        .btn-edit:hover {
            background-color: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }
        .btn-delete:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }
        .sortable {
            cursor: pointer;
            position: relative;
        }
        .sortable:hover {
            background-color: rgba(0,0,0,0.02);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du modal de suppression
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const deleteButtons = document.querySelectorAll('.delete-btn');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    const patientName = this.getAttribute('data-patient');
                    const modalBody = deleteModal.querySelector('.modal-body');
                    modalBody.innerHTML = `Êtes-vous sûr de vouloir supprimer l'ordonnance pour <strong>${patientName}</strong> ? Cette action est irréversible.`;
                    deleteForm.action = url;
                });
            });

            // Initialiser les tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
    {{ $ordonnances->links() }}
@endsection
