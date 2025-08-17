@extends('layouts.medecin')

@section('title', 'Tableau de bord')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4e73df;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --info-color: #36b9cc;
            --secondary-color: #858796;
        }

        .stat-card {
            border-radius: 15px;
            border: none;
            transition: all 0.3s ease;
            height: 100%;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
            backdrop-filter: blur(10px);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--info-color));
        }

        .stat-card .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .stat-card .card-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card .card-title {
            color: #5a5c69;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .stat-card .card-change {
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .chart-container {
            position: relative;
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .chart-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f1f1;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #5a5c69;
            margin: 0;
        }

        .notification-item {
            border-left: 4px solid var(--primary-color);
            margin-bottom: 0.75rem;
            transition: all 0.3s;
            border-radius: 0 8px 8px 0;
            background: white;
        }

        .notification-item:hover {
            background-color: #f8f9fc;
            transform: translateX(5px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .appointment-card {
            border-radius: 12px;
            border: none;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .appointment-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .priority-high { border-left: 4px solid var(--danger-color); }
        .priority-medium { border-left: 4px solid var(--warning-color); }
        .priority-low { border-left: 4px solid var(--success-color); }

        .quick-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .quick-action-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .quick-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.3);
            color: white;
        }

        .weather-widget {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .time-widget {
            background: linear-gradient(135deg, #fd79a8, #e84393);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .quick-actions {
                flex-direction: column;
            }
            
            .stat-card .card-value {
                font-size: 1.8rem;
            }
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-circle {
            transition: stroke-dashoffset 0.35s;
            transform-origin: 50% 50%;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- En-tête avec actions rapides -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h2 mb-0 text-gray-800">
                <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
            </h1>
            <p class="text-muted mb-0">Aperçu de votre activité médicale</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted"><i class="fas fa-clock me-1"></i>Dernière connexion: {{ now()->format('d/m/Y à H:i') }}</span>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-1"></i>Période
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="updatePeriod('today')">Aujourd'hui</a></li>
                    <li><a class="dropdown-item" href="#" onclick="updatePeriod('week')">Cette semaine</a></li>
                    <li><a class="dropdown-item" href="#" onclick="updatePeriod('month')">Ce mois</a></li>
                    <li><a class="dropdown-item" href="#" onclick="updatePeriod('year')">Cette année</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="quick-actions mb-3">
        <a href="/patients/create" class="quick-action-btn">
            <i class="fas fa-user-plus"></i> Nouveau Patient
        </a>
        <a href="/rendez-vous/create" class="quick-action-btn">
            <i class="fas fa-calendar-plus"></i> Planifier RDV
        </a>
        <a href="/consultations" class="quick-action-btn">
            <i class="fas fa-stethoscope"></i> Consultations
        </a>
        <a href="/prescriptions/create" class="quick-action-btn">
            <i class="fas fa-prescription"></i> Ordonnance
        </a>
    </div>

    <!-- Statistiques principales -->
    <div class="row g-4 mb-4 mt-5">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow">
                <div class="card-body text-center">
                    <div class="card-icon text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-value text-primary">{{ $patientCount ?? 0 }}</div>
                    <div class="card-title">Patients Total</div>
                    <div class="card-change text-success">
                        <i class="fas fa-arrow-up"></i> +12% ce mois
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow">
                <div class="card-body text-center">
                    <div class="card-icon text-warning">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="card-value text-warning">{{ $pendingRdvCount ?? 0 }}</div>
                    <div class="card-title">RDV en attente</div>
                    <div class="card-change text-info">
                        <i class="fas fa-clock"></i> Aujourd'hui: {{ $todayRdvCount ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow">
                <div class="card-body text-center">
                    <div class="card-icon text-success">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="card-value text-success">{{ $consultationCount ?? 0 }}</div>
                    <div class="card-title">Consultations</div>
                    <div class="card-change text-success">
                        <i class="fas fa-arrow-up"></i> +8% cette semaine
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card border-0 shadow">
                <div class="card-body text-center">
                    <div class="card-icon text-info">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                    <div class="card-value text-info">{{ number_format($revenue ?? 0) }}€</div>
                    <div class="card-title">Revenus du mois</div>
                    <div class="card-change text-success">
                        <i class="fas fa-chart-line"></i> +15% vs mois dernier
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Graphiques -->
        <div class="col-xl-8">
            <!-- Graphique des consultations -->
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title"><i class="fas fa-chart-line me-2"></i>Évolution des consultations</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <input type="radio" class="btn-check" name="consultationPeriod" id="week" checked>
                        <label class="btn btn-outline-primary" for="week">7j</label>
                        <input type="radio" class="btn-check" name="consultationPeriod" id="month">
                        <label class="btn btn-outline-primary" for="month">30j</label>
                        <input type="radio" class="btn-check" name="consultationPeriod" id="year">
                        <label class="btn btn-outline-primary" for="year">1an</label>
                    </div>
                </div>
                <canvas id="consultationChart" height="300"></canvas>
            </div>

            <!-- Répartition des patients par âge -->
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title"><i class="fas fa-users me-2"></i>Répartition des patients par âge</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="ageChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar droite -->
        <div class="col-xl-4">
            <!-- Widget météo et heure -->
            <div class="weather-widget">
                <h6><i class="fas fa-cloud-sun me-2"></i>Yaoundé</h6>
                <div class="h4 mb-0">28°C</div>
                <small>Partiellement nuageux</small>
            </div>

            <div class="time-widget">
                <div id="currentTime" class="h4 mb-0"></div>
                <small>Heure locale</small>
            </div>

            <!-- Prochains rendez-vous -->
            <div class="card border-0 shadow mt-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Prochains rendez-vous
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($upcomingAppointments ?? [] as $rdv)
                        <div class="appointment-card card priority-{{ $rdv->priority ?? 'low' }}">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">{{ $rdv->patient_name ?? 'Patient' }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $rdv->time ?? '00:00' }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary">{{ $rdv->type ?? 'Consultation' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                            Aucun rendez-vous programmé
                        </p>
                    @endforelse
                </div>
            </div>

            <!-- Notifications récentes -->
            <div class="card border-0 shadow mt-4">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>Notifications récentes
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($notifications ?? [] as $notif)
                        <div class="notification-item p-3 mb-2">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle text-primary me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <p class="mb-1 small">{{ $notif->message }}</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">
                            <i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>
                            Aucune nouvelle notification
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des patients récents -->
    <div class="card border-0 shadow mt-4">
        <div class="card-header bg-transparent border-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Patients récents
                </h5>
                <a href="/patients" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Âge</th>
                            <th>Dernière visite</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPatients ?? [] as $patient)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            {{ strtoupper(substr($patient->nom ?? 'P', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $patient->nom ?? 'N/A' }} {{ $patient->prenom ?? '' }}</h6>
                                            <small class="text-muted">{{ $patient->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $patient->age ?? 'N/A' }} ans</td>
                                <td>{{ $patient->last_visit ?? 'Jamais' }}</td>
                                <td>
                                    <span class="badge bg-{{ $patient->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ $patient->status === 'active' ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/patients/{{ $patient->id ?? '#' }}" class="btn btn-outline-primary" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/patients/{{ $patient->id ?? '#' }}/edit" class="btn btn-outline-secondary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-user-slash fa-2x mb-2 d-block"></i>
                                    Aucun patient enregistré
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Mise à jour de l'heure
function updateTime() {
    const now = new Date();
    document.getElementById('currentTime').textContent = now.toLocaleTimeString('fr-FR');
}
setInterval(updateTime, 1000);
updateTime();

// Données pour les graphiques (à remplacer par de vraies données depuis le contrôleur)
const consultationData = {
    labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
    datasets: [{
        label: 'Consultations',
        data: [12, 19, 8, 15, 22, 8, 5],
        borderColor: '#4e73df',
        backgroundColor: 'rgba(78, 115, 223, 0.1)',
        borderWidth: 3,
        fill: true,
        tension: 0.4
    }]
};

const ageData = {
    labels: ['0-18', '19-35', '36-50', '51-65', '+65'],
    datasets: [{
        data: [15, 25, 30, 20, 10],
        backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#858796']
    }]
};

const genderData = {
    labels: ['Hommes', 'Femmes'],
    datasets: [{
        data: [45, 55],
        backgroundColor: ['#36b9cc', '#fd79a8']
    }]
};

// Configuration des graphiques
const chartConfig = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom'
        }
    }
};

// Initialisation des graphiques
const consultationChart = new Chart(document.getElementById('consultationChart'), {
    type: 'line',
    data: consultationData,
    options: {
        ...chartConfig,
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.1)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

const ageChart = new Chart(document.getElementById('ageChart'), {
    type: 'doughnut',
    data: ageData,
    options: chartConfig
});

const genderChart = new Chart(document.getElementById('genderChart'), {
    type: 'pie',
    data: genderData,
    options: chartConfig
});

// Fonction pour mettre à jour la période
function updatePeriod(period) {
    // Ici vous pouvez ajouter la logique pour actualiser les données selon la période
    console.log('Période sélectionnée:', period);
    // Faire un appel AJAX pour récupérer les nouvelles données
}

// Animation au scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observer tous les éléments à animer
document.querySelectorAll('.stat-card, .chart-container, .card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'all 0.6s ease';
    observer.observe(el);
});
</script>
@endpush
@endsection