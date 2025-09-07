@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Statistiques des Médecins</h1>
        <a href="{{ route('admin.medecins.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i> Retour à la liste
        </a>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Médecins</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Médecins Actifs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['actifs'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Médecins Bloqués</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['bloques'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-lock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Nouveaux (7j)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['nouveaux_7j'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Graphique d'évolution -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Évolution des inscriptions</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top spécialités -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 des spécialités</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="specialitesChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($topSpecialites as $index => $specialite)
                            <span class="me-2">
                                <i class="fas fa-circle" style="color: {{ $colors[$index % count($colors)] }}"></i> {{ $specialite->specialite }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top villes -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 5 des villes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Ville</th>
                                    <th>Nombre de médecins</th>
                                    <th>Pourcentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topVilles as $ville)
                                <tr>
                                    <td>{{ $ville->ville }}</td>
                                    <td>{{ $ville->count }}</td>
                                    <td>{{ number_format(($ville->count / $stats['total']) * 100, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Répartition par statut -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Répartition par statut</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-3"><i class="fas fa-circle text-success"></i> Actifs ({{ $stats['actifs'] }})</span>
                        <span class="me-3"><i class="fas fa-circle text-warning"></i> Bloqués ({{ $stats['bloques'] }})</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .chart-area {
        position: relative;
        height: 20rem;
        width: 100%;
    }
    .chart-pie {
        position: relative;
        height: 15rem;
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Couleurs pour les graphiques
    const colors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
        '#5a5c69', '#858796', '#3a3b45', '#00bcd4', '#9c27b0'
    ];

    // Graphique d'évolution
    const ctxEvolution = document.getElementById('evolutionChart').getContext('2d');
    const evolutionData = @json($evolution->pluck('count'));
    const evolutionLabels = @json($evolution->pluck('mois'));
    
    new Chart(ctxEvolution, {
        type: 'line',
        data: {
            labels: evolutionLabels,
            datasets: [{
                label: 'Nouveaux médecins',
                data: evolutionData,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 3,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                borderWidth: 2,
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgb(255,255,255)',
                    bodyColor: '#858796',
                    titleMarginBottom: 10,
                    titleColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgb(234, 236, 244)',
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineColor: 'rgb(234, 236, 244)',
                        zeroLineBorderDash: [2],
                        zeroLineBorderDashOffset: [2],
                    },
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                },
            },
        }
    });

    // Graphique des spécialités
    const ctxSpecialites = document.getElementById('specialitesChart').getContext('2d');
    new Chart(ctxSpecialites, {
        type: 'doughnut',
        data: {
            labels: @json($topSpecialites->pluck('specialite')),
            datasets: [{
                data: @json($topSpecialites->pluck('count')),
                backgroundColor: colors.slice(0, {{ count($topSpecialites) }}),
                hoverBackgroundColor: colors.slice(0, {{ count($topSpecialites) }}),
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: 'rgb(255,255,255)',
                    bodyColor: '#858796',
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
            },
            cutout: '70%',
        },
    });

    // Graphique de statut
    const ctxStatus = document.getElementById('statusChart').getContext('2d');
    new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: ['Actifs', 'Bloqués'],
            datasets: [{
                data: [{{ $stats['actifs'] }}, {{ $stats['bloques'] }}],
                backgroundColor: ['#1cc88a', '#f6c23e'],
                hoverBackgroundColor: ['#17a673', '#dda20a'],
                hoverBorderColor: 'rgba(234, 236, 244, 1)',
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: 'rgb(255,255,255)',
                    bodyColor: '#858796',
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
            },
            cutout: '70%',
        },
    });
</script>
@endpush
@endsection
