<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startPush('styles'); ?>

<style>
    /* Reset des marges et conteneur */

    /*.container-fluid {
        width: 100%;
        max-width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
        box-sizing: border-box;
    }*/

    /* Espacement des sections 
    .dashboard-section {
        padding: 1 rem 0;
        margin: 0 auto;
        max-width: 100%;
        overflow-x: hidden;
    }
    
    */

    @media (max-width: 1199.98px) {
        .quick-action-btn {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            margin: 0.25rem 0.25rem 0.25rem 0;
        }
        
        .stats-card .card-text {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 767.98px) {
        .dashboard-section {
            padding: 0.75rem 0;
        }
        
        .stats-card, .chart-container {
            margin-bottom: 1rem;
        }
        
        .section-title {
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.5rem;
        }
        
        .section-title::after {
            height: 2px;
        }
        
        .quick-action-btn {
            padding: 0.4rem 0.9rem;
            font-size: 0.8rem;
            margin: 0.2rem 0.2rem 0.2rem 0;
        }
        
        .stats-card {
            min-height: 140px;
        }
        
        .stats-card .card-body {
            padding: 1rem;
        }
        
        .stats-card .card-text {
            font-size: 1.4rem;
        }
        
        .chart-container {
            min-height: 250px;
            padding: 1rem;
        }
        
        .notification-item, .patient-item {
            padding: 0.75rem;
        }
        
        .dropdown-menu {
            position: absolute !important;
            right: 0;
            left: auto;
        }
    }
    
    .stats-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.08));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.12);
        margin-bottom: 1.5rem;
        height: 100%;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }
    
    .stats-card .card-body {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .stats-card .card-title {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: rgba(255, 255, 255, 0.8);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stats-card .card-text {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0.5rem 0;
        color: #fff;
        line-height: 1.2;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }
    
    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    
    .chart-container {
        background: rgba(30, 30, 40, 0.8);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.08);
        margin-bottom: 1.5rem;
        height: 100%;
        min-height: 300px;
        transition: all 0.3s ease;
    }
    
    .chart-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
        border-color: rgba(255, 255, 255, 0.15);
    }
    
    /* Styles pour les graphiques */
    .chartjs-render-monitor {
        width: 100% !important;
        height: auto !important;
    }
    
    /* Correction de la couleur du texte dans les cartes */
    .card, .card-body {
        color: #fff;
    }
    
    .text-muted {
        color: rgba(255, 255, 255, 0.6) !important;
    }
    
    .text-white-50 {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0 0 1.5rem 0;
        color: #fff;
        position: relative;
        padding-bottom: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .section-title i {
        margin-right: 0.75rem;
        color: var(--bs-primary);
        font-size: 1.2em;
        vertical-align: middle;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        width: 50px;
        background: linear-gradient(90deg, var(--bs-primary), transparent);
        border-radius: 3px;
    }
    
    .notification-item, .patient-item {
        border-radius: 10px;
        margin-bottom: 12px;
        transition: all 0.3s ease;
        border: none;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        width: 100%;
        overflow: hidden;
    }
    
    .notification-item:hover, .patient-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        border-color: rgba(255, 255, 255, 0.15);
    }
    
    .notification-item {
        padding: 1rem;
    }
    
    .notification-item .notification-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
        background: rgba(var(--bs-primary-rgb), 0.2);
    }
    
    .notification-item .notification-content {
        flex: 1;
    }
    
    .notification-item .notification-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        margin-left: 1rem;
    }
    
    .patient-item {
        padding: 1rem;
        display: flex;
        align-items: center;
    }
    
    .patient-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
        background: rgba(var(--bs-info-rgb), 0.2);
    }
    
    .patient-info {
        flex: 1;
        min-width: 0;
    }
    
    .patient-info h6 {
        margin: 0;
        font-weight: 600;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .patient-info small {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.8rem;
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        border-radius: 0.375rem;
    }
    
    .section-title {
        position: relative;
        padding-bottom: 10px;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, #007bff, #28a745);
        border-radius: 2px;
    }
    
    .patient-item {
        border-radius: 10px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        border: none;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(5px);
    }
    
    .patient-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .metric-change {
        font-size: 0.8rem;
        margin-top: 5px;
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745, #20c997) !important;
    }
    
    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545, #fd7e14) !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #6f42c1) !important;
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14) !important;
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8, #007bff) !important;
    }
    
    .quick-action-btn {
        border-radius: 50px;
        padding: 0.6rem 1.25rem;
        margin: 0.25rem 0.5rem 0.25rem 0;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .quick-action-btn i {
        margin-right: 0.5rem;
    }
    
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        padding: 0;
        color: rgba(255, 255, 255, 0.7);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.2s ease;
    }
    
    .btn-icon:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        transform: translateY(-1px);
    }
    
    /* Styles pour les menus déroulants */
    .dropdown-menu {
        background: rgba(30, 30, 45, 0.98);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        padding: 0.5rem 0;
        min-width: 200px;
    }
    
    .dropdown-item {
        color: rgba(255, 255, 255, 0.8);
        padding: 0.5rem 1.25rem;
        transition: all 0.2s ease;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
    }
    
    .dropdown-item i {
        width: 20px;
        margin-right: 0.5rem;
        text-align: center;
        font-size: 0.9em;
    }
    
    .dropdown-item:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        padding-left: 1.5rem;
    }
    
    .dropdown-divider {
        border-color: rgba(255, 255, 255, 0.1);
        margin: 0.5rem 0;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-section">
    <!-- En-tête avec actions rapides -->
    <div class="row mb-3">
        <div class="col-md-9 px-5">
            <h1 class="text-white text-3xl font-bold mb-5 animate__animated animate__fadeInDown">
                <i class="fas fa-tachometer-alt  px-5"></i>
                <?php
                    $hour = now()->hour;
                    $greeting = 'Bienvenue';
                    if ($hour >= 5 && $hour < 12) {
                        $greeting = 'Bonjour';
                    } elseif ($hour >= 12 && $hour < 18) {
                        $greeting = 'Bon après-midi';
                    } else {
                        $greeting = 'Bonsoir';
                    }
                ?>
                <?php echo e($greeting); ?> M. <?php echo e(Auth::user()->name); ?>

            </h1>
            <p class="text-white-50 animate__animated animate__fadeInDown animate__delay-1s px-4">
                Tableau de bord administrateur - <?php echo e(date('d F Y')); ?>

            </p>
        </div>
        <div class="col-md-3 text-end">
            <div class="animate__animated animate__fadeInRight">
                <button class="btn btn-light quick-action-btn" onclick="refreshDashboard()">
                    <i class="fas fa-sync-alt me-2"></i>Actualiser
                </button>
                <button class="btn btn-info quick-action-btn" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="fas fa-download me-2"></i>Exporter
                </button>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row g-4  px-5 mx-5">
        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4 ">
            <div class="card text-black bg-gradient-success stats-card animate__animated animate__fadeInUp">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">👥</div>
                    <h5 class="card-title">Patients inscrits</h5>
                    <p class="card-text fs-3 fw-bold"><?php echo e($patientsInscrits ?? 0); ?></p>
                    <div class="metric-change">
                        <i class="fas fa-arrow-up text-light"></i> +12% ce mois
                    </div>
                    <!-- Debug conservé -->
                    <small class="text-white-50 d-block mt-2" style="font-size: 0.7rem;">
                        Debug: <?php echo e($patientsInscrits ?? 'Variable non définie'); ?> | 
                        Type: <?php echo e(gettype($patientsInscrits ?? 'null')); ?>

                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card text-black bg-gradient-danger stats-card animate__animated animate__fadeInUp animate__delay-1s">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">🚫</div>
                    <h5 class="card-title">Patients bloqués</h5>
                    <p class="card-text fs-3 fw-bold"><?php echo e($patientsBloques ?? 0); ?></p>
                    <div class="metric-change">
                        <i class="fas fa-arrow-down text-light"></i> -3% ce mois
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card text-black bg-gradient-primary stats-card animate__animated animate__fadeInUp animate__delay-2s">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">👨‍⚕️</div>
                    <h5 class="card-title">Médecins actifs</h5>
                    <p class="card-text fs-3 fw-bold"><?php echo e($medecinsActifs ?? 0); ?></p>
                    <div class="metric-change">
                        <i class="fas fa-arrow-up text-light"></i> +5% ce mois
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card text-black bg-gradient-warning stats-card animate__animated animate__fadeInUp animate__delay-3s">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">📅</div>
                    <h5 class="card-title">RDV en attente</h5>
                    <p class="card-text fs-3 fw-bold"><?php echo e($rendezVousEnAttente ?? 0); ?></p>
                    <div class="metric-change">
                        <i class="fas fa-arrow-up text-warning"></i> +8% ce mois
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="card text-black bg-gradient-info stats-card animate__animated animate__fadeInUp animate__delay-4s">
                <div class="card-body text-center">
                    <div class="stats-icon mb-2">🩺</div>
                    <h5 class="card-title">Consultations</h5>
                    <p class="card-text fs-3 fw-bold"><?php echo e($consultationsEffectuees ?? 0); ?></p>
                    <div class="metric-change">
                        <i class="fas fa-arrow-up text-light"></i> +15% ce mois
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques d'analyse -->
    <div class="row g-4 mb-5">
        <div class="col-xxl-7 col-lg-7 px-5 mx-4">
            <div class="chart-container animate__animated animate__fadeInLeft text-black">
                <h4 class="section-title mb-4 px-5 mx-4 text-white">
                    <i class="fas fa-chart-line me-2 text-primary "></i>Évolution des inscriptions
                </h4>
                <canvas id="inscriptionsChart" height="100"></canvas>
            </div>
        </div>
        <div class="col-xxl-4 col-lg-5">
            <div class="chart-container animate__animated animate__fadeInRight text-black">
                <h4 class="section-title mb-4 text-white">
                    <i class="fas fa-chart-pie me-2 text-success "></i>Répartition des utilisateurs
                </h4>
                <canvas id="utilisateursChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-lg-6">
            <div class="chart-container animate__animated animate__fadeInUp  text-black">
                <h4 class="section-title mb-4 px-5 text-white">
                    <i class="fas fa-chart-bar me-2 text-info"></i>Consultations par mois
                </h4>
                <canvas id="consultationsChart" height="150"></canvas>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="chart-container animate__animated animate__fadeInUp animate__delay-1s">
                <h4 class="section-title mb-4 text-white">
                    <i class="fas fa-chart-area me-2 text-warning"></i>Revenus mensuels
                </h4>
                <canvas id="revenusChart" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- Notifications et patients -->
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="animate__animated animate__fadeInLeft px-5 mx-4">
                <h4 class="section-title mb-4 text-white">
                    <i class="fas fa-bell me-2"></i>📢 Dernières notifications
                </h4>
                <div class="notifications-container text-white" style="max-height: 400px; overflow-y: auto;">
                    <?php $__empty_1 = true; $__currentLoopData = $notifications ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="notification-item d-flex">
                            <div class="notification-avatar">
                                <i class="fas fa-user text-primary"></i>
                            </div>
                            <div class="notification-content">
                                <h6 class="mb-1"><?php echo e($notif->user->name ?? 'Utilisateur'); ?></h6>
                                <p class="mb-0 small text-white-50"><?php echo e($notif->content); ?></p>
                            </div>
                            <div class="notification-meta">
                                <span class="badge bg-primary-soft"><?php echo e($notif->created_at->format('d/m/Y')); ?></span>
                                <small class="text-muted mt-1"><?php echo e($notif->created_at->format('H:i')); ?></small>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="notification-item list-group-item text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-3 text-muted"></i>
                            <p class="mb-0">Aucune notification pour le moment.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="animate__animated animate__fadeInRight">
                <h4 class="section-title mb-4 text-white">
                    <i class="fas fa-users me-2"></i>Liste des patients récents
                </h4>
                <div class="patients-container" style="max-height: 400px; overflow-y: auto;">
                    <?php $__empty_1 = true; $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="patient-item">
                            <div class="patient-avatar">
                                <i class="fas fa-user-injured text-info"></i>
                            </div>
                            <div class="patient-info text-white">
                                <h6 class="mb-0"><?php echo e($patient->name); ?></h6>
                                <small><?php echo e($patient->email); ?></small>
                            </div>
                            <div class="d-flex align-items-center text-white">
                                <span class="badge bg-<?php echo e($patient->abonnement && $patient->abonnement->statut === 'actif' ? 'success' : 'secondary'); ?> me-2">
                                    <?php echo e($patient->abonnement && $patient->abonnement->statut === 'actif' ? 'Abonné' : 'Non abonné'); ?>

                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-eye me-2"></i>Voir profil</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="fas fa-edit me-2"></i>Modifier</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="fas fa-ban me-2"></i>Bloquer</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="patient-item list-group-item text-center text-muted py-4">
                            <i class="fas fa-user-times fa-2x mb-3 text-muted"></i>
                            <p class="mb-0">Aucun patient trouvé.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'export -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-download me-2"></i>Exporter les données</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <button class="btn btn-success w-100 mb-3">
                            <i class="fas fa-file-excel me-2"></i>Excel
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-danger w-100 mb-3">
                            <i class="fas fa-file-pdf me-2"></i>PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// Configuration des graphiques
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution des inscriptions
    const inscriptionsCtx = document.getElementById('inscriptionsChart').getContext('2d');
    new Chart(inscriptionsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Patients inscrits',
                data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 38, 42, <?php echo e($patientsInscrits ?? 45); ?>],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Médecins actifs',
                data: [5, 8, 7, 10, 9, 12, 11, 14, 13, 15, 16, <?php echo e($medecinsActifs ?? 18); ?>],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique en camembert des utilisateurs
    const utilisateursCtx = document.getElementById('utilisateursChart').getContext('2d');
    new Chart(utilisateursCtx, {
        type: 'doughnut',
        data: {
            labels: ['Patients actifs', 'Patients bloqués', 'Médecins'],
            datasets: [{
                data: [<?php echo e($patientsInscrits ?? 45); ?>, <?php echo e($patientsBloques ?? 3); ?>, <?php echo e($medecinsActifs ?? 18); ?>],
                backgroundColor: ['#28a745', '#dc3545', '#007bff'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique des consultations
    const consultationsCtx = document.getElementById('consultationsChart').getContext('2d');
    new Chart(consultationsCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Consultations',
                data: [65, 78, 90, 81, 96, <?php echo e($consultationsEffectuees ?? 105); ?>],
                backgroundColor: '#17a2b8',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique des revenus
    const revenusCtx = document.getElementById('revenusChart').getContext('2d');
    new Chart(revenusCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Revenus (€)',
                data: [1200, 1900, 1500, 2500, 2200, 3000],
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '€';
                        }
                    }
                }
            }
        }
    });
});

// Fonction de rafraîchissement
function refreshDashboard() {
    const btn = event.target.closest('button');
    const icon = btn.querySelector('i');
    
    icon.classList.add('fa-spin');
    btn.disabled = true;
    
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Animation au scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate__animated', 'animate__fadeInUp');
        }
    });
}, observerOptions);

document.querySelectorAll('.chart-container').forEach(el => {
    observer.observe(el);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>