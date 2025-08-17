@extends('layouts.medecin')

@push('styles')
<style>
    /* Variables CSS pour une cohérence de design */
    :root {
        --primary-color: #4e73df;
        --light-bg: #f8f9fa;
        --card-radius: 16px;
        --shadow-light: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        --shadow-hover: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        --transition: all 0.3s ease;
    }

    .container-fluid {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        min-height: 100vh;
    }

    .header-section {
        background: white;
        border-radius: var(--card-radius);
        box-shadow: var(--shadow-light);
        margin-bottom: 2rem;
        padding: 2rem;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-light);
        transition: var(--transition);
        border-left: 4px solid;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    .stat-card.primary { border-left-color: #4e73df; }
    .stat-card.success { border-left-color: #1cc88a; }
    .stat-card.warning { border-left-color: #f6c23e; }
    .stat-card.info { border-left-color: #36b9cc; }

    .main-card {
        background: white;
        border-radius: var(--card-radius);
        box-shadow: var(--shadow-light);
        overflow: hidden;
        transition: var(--transition);
    }

    .main-card:hover {
        box-shadow: var(--shadow-hover);
    }

    .card-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border: none;
    }

    .search-filter-section {
        background: var(--light-bg);
        padding: 1.5rem;
        border-bottom: 1px solid #e3e6f0;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .search-box {
        position: relative;
        flex: 1;
        min-width: 250px;
        max-width: 400px;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        z-index: 2;
    }

    .search-input {
        padding-left: 2.5rem;
        border-radius: 50px;
        border: 2px solid #e3e6f0;
        height: 45px;
        transition: var(--transition);
        background: white;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }

    .filter-dropdown {
        min-width: 140px;
    }

    .filter-btn {
        border-radius: 50px;
        height: 45px;
        padding: 0 1.5rem;
        border: 2px solid #e3e6f0;
        background: white;
        transition: var(--transition);
    }

    .filter-btn:hover {
        border-color: var(--primary-color);
        background: var(--primary-color);
        color: white;
    }

    .table-container {
        padding: 0;
    }

    .modern-table {
        margin: 0;
        background: white;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        padding: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #5a5c69;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .modern-table tbody tr {
        transition: var(--transition);
        border: none;
        border-bottom: 1px solid #f1f3f4;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
        transform: scale(1.01);
    }

    .modern-table td {
        padding: 1rem;
        border: none;
        vertical-align: middle;
    }

    .patient-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .patient-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .patient-details h6 {
        margin: 0;
        font-weight: 600;
        color: #2c3e50;
    }

    .patient-details small {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .exam-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }

    .exam-description {
        color: #6c757d;
        font-size: 0.85rem;
        max-width: 250px;
    }

    .date-info {
        text-align: center;
    }

    .date-main {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
    }

    .date-relative {
        color: #6c757d;
        font-size: 0.75rem;
    }

    .type-badge {
        padding: 0.4em 1em;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.75rem;
        background: linear-gradient(135deg, #36b9cc 0%, #1cc88a 100%);
        color: white;
        border: none;
    }

    .actions-group {
        display: flex;
        gap: 0.25rem;
        justify-content: flex-end;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        font-size: 0.8rem;
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    .btn-view {
        background: linear-gradient(135deg, #36b9cc 0%, #1cc88a 100%);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, #f6c23e 0%, #e74a3b 100%);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
        color: white;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
        color: #6c757d;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .pagination-wrapper {
        padding: 1.5rem;
        background: var(--light-bg);
        display: flex;
        justify-content: between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .create-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 500;
        transition: var(--transition);
    }

    .create-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
        color: white;
    }

    .alert-modern {
        border: none;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        box-shadow: var(--shadow-light);
    }

    .modal-modern .modal-content {
        border: none;
        border-radius: var(--card-radius);
        overflow: hidden;
    }

    .modal-modern .modal-header {
        background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
        color: white;
        border: none;
    }

    .fade-in {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-section {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .search-filter-section {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box {
            min-width: auto;
            max-width: none;
        }

        .actions-group {
            justify-content: center;
        }

        .modern-table {
            font-size: 0.85rem;
        }

        .stats-cards {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- En-tête moderne -->
    <div class="header-section fade-in">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-1 fw-light text-dark">Gestion des Examens</h1>
                <p class="text-muted mb-0">Consultez et gérez tous les examens médicaux</p>
            </div>
            <a href="{{ route('medecin.examens.create') }}" class="btn btn-primary create-btn d-flex align-items-center">
                <i class="fas fa-plus me-2"></i>
                Nouvel Examen
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="stats-cards fade-in">
        <div class="stat-card primary">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="text-muted mb-1">Total Examens</h6>
                    <h3 class="mb-0 text-primary">{{ $examens->total() ?? count($examens) }}</h3>
                </div>
                <i class="fas fa-file-medical fa-2x text-primary opacity-25"></i>
            </div>
        </div>
        <div class="stat-card success">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="text-muted mb-1">Cette Semaine</h6>
                    <h3 class="mb-0 text-success">{{ $examens->filter(function($exam) { return \Carbon\Carbon::parse($exam->date_examen)->isCurrentWeek(); })->count() }}</h3>
                </div>
                <i class="fas fa-calendar-week fa-2x text-success opacity-25"></i>
            </div>
        </div>
        <div class="stat-card warning">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="text-muted mb-1">Aujourd'hui</h6>
                    <h3 class="mb-0 text-warning">{{ $examens->filter(function($exam) { return \Carbon\Carbon::parse($exam->date_examen)->isToday(); })->count() }}</h3>
                </div>
                <i class="fas fa-calendar-day fa-2x text-warning opacity-25"></i>
            </div>
        </div>
        <div class="stat-card info">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <h6 class="text-muted mb-1">Ce Mois</h6>
                    <h3 class="mb-0 text-info">{{ $examens->filter(function($exam) { return \Carbon\Carbon::parse($exam->date_examen)->isCurrentMonth(); })->count() }}</h3>
                </div>
                <i class="fas fa-calendar-alt fa-2x text-info opacity-25"></i>
            </div>
        </div>
    </div>

    <!-- Messages de session -->
    @if(session('success'))
    <div class="alert alert-modern fade-in" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
    </div>
    @endif

    <!-- Carte principale -->
    <div class="main-card fade-in">
        <!-- En-tête de la carte -->
        <div class="card-header-modern">
            <h5 class="mb-0 d-flex align-items-center mt-3">
                <i class="fas fa-list me-2"></i>
                Liste des Examens Médicaux
            </h5>
        </div>

        <!-- Section recherche et filtres -->
        <div class="search-filter-section">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Rechercher un patient, titre ou description...">
            </div>
            <div class="filter-dropdown">
                <div class="dropdown">
                    <button class="btn filter-btn dropdown-toggle d-flex align-items-center" type="button" id="filterDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-2"></i>
                        Filtrer
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#" data-filter="all">
                            <i class="fas fa-list me-2"></i>Tous les examens
                        </a>
                        <a class="dropdown-item" href="#" data-filter="today">
                            <i class="fas fa-calendar-day me-2"></i>Aujourd'hui
                        </a>
                        <a class="dropdown-item" href="#" data-filter="week">
                            <i class="fas fa-calendar-week me-2"></i>Cette semaine
                        </a>
                        <a class="dropdown-item" href="#" data-filter="month">
                            <i class="fas fa-calendar-alt me-2"></i>Ce mois-ci
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau moderne -->
        <div class="table-container">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Patient</th>
                        <th style="width: 30%;">Examen</th>
                        <th style="width: 15%;">Date</th>
                        <th style="width: 15%;">Type</th>
                        <th style="width: 15%;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($examens as $examen)
                    <tr class="examen-row" data-date="{{ $examen->date_examen }}">
                        <td>
                            <div class="patient-info">
                                <div class="patient-avatar">
                                    {{ substr($examen->patient->name ?? 'P', 0, 1) }}
                                </div>
                                <div class="patient-details">
                                    <h6>{{ $examen->patient->name ?? 'Patient inconnu' }}</h6>
                                    <small>{{ $examen->patient->email ?? 'Email non disponible' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="exam-title">{{ $examen->titre }}</div>
                            @if($examen->description)
                            <div class="exam-description text-truncate" title="{{ $examen->description }}">
                                {{ Str::limit($examen->description, 60) }}
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="date-info">
                                <div class="date-main">{{ \Carbon\Carbon::parse($examen->date_examen)->translatedFormat('d M Y') }}</div>
                                <div class="date-relative">{{ \Carbon\Carbon::parse($examen->date_examen)->diffForHumans() }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="type-badge">{{ $examen->type ?? 'Non spécifié' }}</span>
                        </td>
                        <td>
                            <div class="actions-group">
                                <a href="{{ route('medecin.examens.show', $examen) }}" class="text-info action-btn btn-view" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('medecin.examens.edit', $examen) }}" class="text-warning action-btn btn-edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="text-danger action-btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $examen->id }}" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal de suppression moderne -->
                    <div class="modal fade modal-modern" id="deleteModal{{ $examen->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Confirmer la suppression
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="text-center mb-3">
                                        <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                                        <h6>Supprimer l'examen "{{ $examen->titre }}" ?</h6>
                                    </div>
                                    <p class="text-muted text-center mb-0">
                                        Cette action est irréversible. Toutes les données associées seront définitivement supprimées.
                                    </p>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
                                        <i class="fas fa-times me-1"></i>Annuler
                                    </button>
                                    <form action="{{ route('medecin.examens.destroy', $examen) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger rounded-pill">
                                            <i class="fas fa-trash me-1"></i>Supprimer définitivement
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-folder-open empty-icon"></i>
                                <h5 class="mb-2">Aucun examen trouvé</h5>
                                <p class="mb-3">Commencez par créer votre premier examen médical</p>
                                <a href="{{ route('medecin.examens.create') }}" class="create-btn">
                                    <i class="fas fa-plus me-2"></i>Créer un examen
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination moderne -->
        @if($examens->hasPages())
        <div class="pagination-wrapper">
            <div class="text-muted small">
                Affichage de <strong>{{ $examens->firstItem() }}</strong> à <strong>{{ $examens->lastItem() }}</strong> sur <strong>{{ $examens->total() }}</strong> résultats
            </div>
            <nav>
                {{ $examens->links('pagination::bootstrap-5') }}
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialisation des tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Variables pour le filtrage
        const searchInput = document.getElementById('searchInput');
        const examensRows = document.querySelectorAll('.examen-row');
        const filterLinks = document.querySelectorAll('[data-filter]');

        // Fonction de recherche améliorée
        function filterExamens() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            
            examensRows.forEach(row => {
                const patientName = row.querySelector('.patient-details h6')?.textContent.toLowerCase() || '';
                const examTitle = row.querySelector('.exam-title')?.textContent.toLowerCase() || '';
                const examDesc = row.querySelector('.exam-description')?.textContent.toLowerCase() || '';
                const searchText = patientName + ' ' + examTitle + ' ' + examDesc;
                
                const isVisible = searchText.includes(searchTerm);
                row.style.display = isVisible ? '' : 'none';
                
                // Animation d'apparition
                if (isVisible && searchTerm !== '') {
                    row.style.animation = 'fadeInUp 0.3s ease-out';
                }
            });
            
            // Afficher un message si aucun résultat
            updateEmptyState();
        }

        // Fonction de filtrage par date améliorée
        function filterByDate(filterType) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            examensRows.forEach(row => {
                const dateStr = row.getAttribute('data-date');
                if (!dateStr) {
                    row.style.display = 'none';
                    return;
                }
                
                const examDate = new Date(dateStr);
                examDate.setHours(0, 0, 0, 0);
                
                let showRow = false;
                
                switch(filterType) {
                    case 'today':
                        showRow = examDate.getTime() === today.getTime();
                        break;
                    case 'week':
                        const startOfWeek = new Date(today);
                        startOfWeek.setDate(today.getDate() - today.getDay());
                        const endOfWeek = new Date(today);
                        endOfWeek.setDate(today.getDate() + (6 - today.getDay()));
                        showRow = examDate >= startOfWeek && examDate <= endOfWeek;
                        break;
                    case 'month':
                        showRow = examDate.getMonth() === today.getMonth() && 
                                 examDate.getFullYear() === today.getFullYear();
                        break;
                    default: // 'all'
                        showRow = true;
                }
                
                row.style.display = showRow ? '' : 'none';
                
                // Animation
                if (showRow) {
                    row.style.animation = 'fadeInUp 0.3s ease-out';
                }
            });
            
            updateEmptyState();
        }

        // Fonction pour afficher/masquer l'état vide
        function updateEmptyState() {
            const visibleRows = Array.from(examensRows).filter(row => row.style.display !== 'none');
            const emptyRow = document.querySelector('tbody tr td[colspan]')?.parentElement;
            
            if (visibleRows.length === 0 && !emptyRow) {
                // Créer un message d'état vide temporaire pour les filtres
                const tbody = document.querySelector('tbody');
                const tempEmptyRow = document.createElement('tr');
                tempEmptyRow.innerHTML = `
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-search empty-icon"></i>
                            <h5 class="mb-2">Aucun résultat trouvé</h5>
                            <p class="mb-0">Essayez de modifier vos critères de recherche ou de filtrage</p>
                        </div>
                    </td>
                `;
                tempEmptyRow.id = 'temp-empty-state';
                tbody.appendChild(tempEmptyRow);
            } else if (visibleRows.length > 0) {
                // Supprimer le message temporaire s'il existe
                const tempEmpty = document.getElementById('temp-empty-state');
                if (tempEmpty) {
                    tempEmpty.remove();
                }
            }
        }

        // Événements
        searchInput.addEventListener('input', debounce(filterExamens, 300));
        
        filterLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Nettoyer la recherche
                searchInput.value = '';
                
                const filterType = this.getAttribute('data-filter');
                
                // Mettre à jour l'apparence du bouton actif
                filterLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                if (filterType === 'all') {
                    examensRows.forEach(row => {
                        row.style.display = '';
                        row.style.animation = 'fadeInUp 0.3s ease-out';
                    });
                } else {
                    filterByDate(filterType);
                }
                
                updateEmptyState();
            });
        });

        // Fonction utilitaire de debounce pour optimiser les performances
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Animation d'entrée pour les lignes au chargement
        examensRows.forEach((row, index) => {
            row.style.animationDelay = (index * 0.1) + 's';
            row.classList.add('fade-in');
        });
    });
</script>
@endpush