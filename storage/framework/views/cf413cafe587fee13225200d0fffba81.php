<?php $__env->startSection('title', 'Tableau de bord'); ?>

<?php $__env->startPush('styles'); ?>
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
            justify-content: space-between;
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

        .mini-chart-container {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
            margin-bottom: 1rem;
            height: 250px;
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

        .stat-mini {
            background: linear-gradient(135deg, rgba(78, 115, 223, 0.1), rgba(54, 185, 204, 0.1));
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
        }

        .metric-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
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
            <span class="text-muted"><i class="fas fa-clock me-1"></i>Dernière connexion: <?php echo e(now()->format('d/m/Y à H:i')); ?></span>
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
                    <div class="card-value text-primary" id="patient-count"><?php echo e($patientCount ?? 0); ?></div>
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
                    <div class="card-value text-warning" id="pending-rdv-count"><?php echo e($pendingRdvCount ?? 0); ?></div>
                    <div class="card-title">RDV en attente</div>
                    <div class="card-change text-info">
                        <i class="fas fa-clock"></i> Aujourd'hui: <span id="today-rdv-count"><?php echo e($todayRdvCount ?? 0); ?></span>
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
                    <div class="card-value text-success" id="consultation-count"><?php echo e($consultationCount ?? 0); ?></div>
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
                    <div class="card-value text-info" id="revenue"><?php echo e(number_format($revenue ?? 0)); ?>€</div>
                    <div class="card-title">Revenus du mois</div>
                    <div class="card-change text-success">
                        <i class="fas fa-chart-line"></i> +15% vs mois dernier
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Section graphiques principale -->
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

            <!-- Graphiques revenus et types de consultations -->
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h6 class="chart-title"><i class="fas fa-euro-sign me-2"></i>Revenus mensuels</h6>
                        </div>
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h6 class="chart-title"><i class="fas fa-stethoscope me-2"></i>Types de consultations</h6>
                        </div>
                        <canvas id="consultationTypeChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Répartition des patients par âge et sexe -->
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title"><i class="fas fa-users me-2"></i>Analyse démographique des patients</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-center mb-3">Répartition par âge</h6>
                        <canvas id="ageChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-center mb-3">Répartition par sexe</h6>
                        <canvas id="genderChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Nouveaux graphiques -->
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h6 class="chart-title"><i class="fas fa-calendar-week me-2"></i>RDV par jour de la semaine</h6>
                        </div>
                        <canvas id="weeklyAppointmentChart"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h6 class="chart-title"><i class="fas fa-pills me-2"></i>Prescriptions par catégorie</h6>
                        </div>
                        <canvas id="prescriptionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Graphique de satisfaction et temps d'attente -->
            <div class="chart-container">
                <div class="chart-header">
                    <h5 class="chart-title"><i class="fas fa-chart-bar me-2"></i>Métriques de performance</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-center mb-3">Satisfaction patients (sur 5)</h6>
                        <canvas id="satisfactionChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-center mb-3">Temps d'attente moyen (min)</h6>
                        <canvas id="waitTimeChart"></canvas>
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

            <!-- Métriques rapides -->
             
            <div class="metric-card mt-4">
                <h6><i class="fas fa-chart-pie me-2"></i>Indicateurs clés</h6>
                <div class="stat-mini">
                    <div class="d-flex justify-content-between">
                        <span>Taux d'occupation</span>
                        <strong class="text-success">87%</strong>
                    </div>
                </div>
                <div class="stat-mini">
                    <div class="d-flex justify-content-between">
                        <span>Nouveaux patients</span>
                        <strong class="text-info">+23</strong>
                    </div>
                </div>
                <div class="stat-mini">
                    <div class="d-flex justify-content-between">
                        <span>Annulations</span>
                        <strong class="text-warning">3%</strong>
                    </div>
                </div>
            </div>

            <!-- Mini graphique - Évolution hebdomadaire -->
            <div class="mini-chart-container">
                <h6><i class="fas fa-trending-up me-2"></i>Évolution cette semaine</h6>
                <canvas id="weeklyTrendChart"></canvas>
            </div>

            <!-- Prochains rendez-vous -->
            <div class="card border-0 shadow">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Prochains rendez-vous
                    </h6>
                </div>
                <div class="card-body">
                    <?php if(count($upcomingAppointments) > 0): ?>
                        <?php $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rdv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="appointment-card card priority-<?php echo e($rdv['priority'] ?? 'low'); ?>">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0"><?php echo e($rdv['patient_name'] ?? 'Patient'); ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i><?php echo e($rdv['time'] ?? '00:00'); ?>

                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary"><?php echo e($rdv['type'] ?? 'Consultation'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="appointment-card card priority-high">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Dr. Mballa Jean</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>09:00
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-danger">Urgent</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="appointment-card card priority-medium">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Mme Nkomo Marie</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>10:30
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary">Consultation</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="appointment-card card priority-low">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">M. Atangana Paul</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>14:00
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-success">Suivi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="notification-item p-3 mb-2">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-info-circle text-primary me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <p class="mb-1 small"><?php echo e($notif['message']); ?></p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i><?php echo e($notif['created_at']); ?>

                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="notification-item p-3 mb-2">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-user-plus text-success me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <p class="mb-1 small">Nouveau patient enregistré : Mme Kouam Diane</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>Il y a 5 minutes
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="notification-item p-3 mb-2">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-calendar-times text-warning me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <p class="mb-1 small">RDV annulé - M. Biyong à 15h30</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>Il y a 1 heure
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="notification-item p-3 mb-2">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-prescription text-info me-2 mt-1"></i>
                                <div class="flex-grow-1">
                                    <p class="mb-1 small">Ordonnance validée pour M. Essama</p>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>Il y a 2 heures
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
                        <?php if(isset($upcomingAppointments) && $upcomingAppointments->count() > 0): ?>
                            <?php $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $patient = $appointment->patient ?? null;
                                ?>
                                <?php if($patient): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-primary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                    <?php echo e(strtoupper(substr($patient->name[0] ?? 'P', 0, 1))); ?>

                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($patient->name ?? 'N/A'); ?></h6>
                                                    <small class="text-muted"><?php echo e($patient->email ?? 'N/A'); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo e($patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->age . ' ans' : 'N/A'); ?></td>
                                        <td><?php echo e($appointment->date_rdv ? \Carbon\Carbon::parse($appointment->date_rdv)->diffForHumans() : 'Jamais'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($appointment->statut === 'confirme' ? 'success' : ($appointment->statut === 'en_attente' ? 'warning' : 'secondary')); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $appointment->statut))); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="#" class="btn btn-outline-primary" title="Voir" data-bs-toggle="modal" data-bs-target="#patientDetailsModal" data-patient-id="<?php echo e($patient->id); ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-outline-secondary" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-users-slash fa-2x mb-2"></i>
                                        <p class="mb-0">Aucun patient récent</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Alertes système -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Alertes système
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-server me-2"></i>
                        <div>
                            Sauvegarde automatique programmée à 2h00
                        </div>
                    </div>
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-update me-2"></i>
                        <div>
                            Mise à jour du système disponible
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-transparent border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-tasks text-primary me-2"></i>Tâches à effectuer
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small">Validation des prescriptions</span>
                        <span class="badge bg-danger">3</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small">Rapports médicaux à signer</span>
                        <span class="badge bg-warning">7</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small">Réponses aux messages</span>
                        <span class="badge bg-info">12</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mise à jour de l'heure en temps réel
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    document.getElementById('currentTime').textContent = timeString;
}

setInterval(updateTime, 1000);
updateTime();

// Données pour les graphiques
const consultationData = {
    labels: <?php echo json_encode($chartData['consultation']['labels'] ?? [], 15, 512) ?>,
    datasets: [{
        label: 'Consultations',
        data: <?php echo json_encode($chartData['consultation']['data'] ?? [], 15, 512) ?>,
        borderColor: 'rgba(78, 115, 223, 1)',
        backgroundColor: 'rgba(78, 115, 223, 0.1)',
        borderWidth: 3,
        fill: true,
        tension: 0.4
    }]
};

const revenueData = {
    labels: <?php echo json_encode($chartData['revenue']['labels'] ?? [], 15, 512) ?>,
    datasets: [{
        label: 'Revenus (€)',
        data: <?php echo json_encode($chartData['revenue']['data'] ?? [], 15, 512) ?>,
        borderColor: 'rgba(28, 200, 138, 1)',
        backgroundColor: 'rgba(28, 200, 138, 0.1)',
        borderWidth: 2,
        fill: true
    }]
};

const consultationTypeData = {
    labels: <?php echo json_encode($chartData['consultationTypes']['labels'] ?? [], 15, 512) ?>,
    datasets: [{
        data: <?php echo json_encode($chartData['consultationTypes']['data'] ?? [], 15, 512) ?>,
        backgroundColor: [
            'rgba(78, 115, 223, 0.8)',
            'rgba(231, 74, 59, 0.8)',
            'rgba(246, 194, 62, 0.8)',
            'rgba(54, 185, 204, 0.8)'
        ]
    }]
};

const ageData = {
    labels: <?php echo json_encode($chartData['demographics']['age']['labels'] ?? [], 15, 512) ?>,
    datasets: [{
        data: <?php echo json_encode($chartData['demographics']['age']['data'] ?? [], 15, 512) ?>,
        backgroundColor: [
            'rgba(78, 115, 223, 0.8)',
            'rgba(28, 200, 138, 0.8)',
            'rgba(246, 194, 62, 0.8)',
            'rgba(231, 74, 59, 0.8)',
            'rgba(133, 135, 150, 0.8)'
        ]
    }]
};

const genderData = {
    labels: <?php echo json_encode($chartData['demographics']['gender']['labels'] ?? [], 15, 512) ?>,
    datasets: [{
        data: <?php echo json_encode($chartData['demographics']['gender']['data'] ?? [], 15, 512) ?>,
        backgroundColor: [
            'rgba(54, 185, 204, 0.8)',
            'rgba(246, 194, 62, 0.8)'
        ]
    }]
};

const weeklyAppointmentData = {
    labels: <?php echo json_encode($chartData['weeklyAppointments']['labels'] ?? [], 15, 512) ?>,
    datasets: [{
        label: 'Rendez-vous',
        data: <?php echo json_encode($chartData['weeklyAppointments']['data'] ?? [], 15, 512) ?>,
        backgroundColor: 'rgba(78, 115, 223, 0.6)',
        borderColor: 'rgba(78, 115, 223, 1)',
        borderWidth: 1
    }]
};

const prescriptionData = {
    labels: <?php echo json_encode($chartData['prescriptions']['labels'] ?? [], 15, 512) ?>,
    datasets: [{
        data: <?php echo json_encode($chartData['prescriptions']['data'] ?? [], 15, 512) ?>,
        backgroundColor: [
            'rgba(231, 74, 59, 0.8)',
            'rgba(246, 194, 62, 0.8)',
            'rgba(28, 200, 138, 0.8)',
            'rgba(54, 185, 204, 0.8)',
            'rgba(133, 135, 150, 0.8)'
        ]
    }]
};

// Configuration commune pour les graphiques
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        x: {
            grid: {
                display: false
            }
        },
        y: {
            grid: {
                color: 'rgba(0,0,0,0.05)'
            }
        }
    }
};

// Initialisation des graphiques
document.addEventListener('DOMContentLoaded', function() {
    // Graphique principal des consultations
    const consultationChart = new Chart(document.getElementById('consultationChart'), {
        type: 'line',
        data: consultationData,
        options: {
            ...commonOptions,
            plugins: {
                legend: { display: true }
            },
            scales: {
                ...commonOptions.scales,
                y: {
                    ...commonOptions.scales.y,
                    beginAtZero: true
                }
            }
        }
    });

    // Graphique des revenus
    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: revenueData,
        options: commonOptions
    });

    // Graphique des types de consultations
    new Chart(document.getElementById('consultationTypeChart'), {
        type: 'doughnut',
        data: consultationTypeData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique des âges
    new Chart(document.getElementById('ageChart'), {
        type: 'pie',
        data: ageData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique des sexes
    new Chart(document.getElementById('genderChart'), {
        type: 'doughnut',
        data: genderData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique RDV hebdomadaires
    new Chart(document.getElementById('weeklyAppointmentChart'), {
        type: 'bar',
        data: weeklyAppointmentData,
        options: commonOptions
    });

    // Graphique des prescriptions
    new Chart(document.getElementById('prescriptionChart'), {
        type: 'doughnut',
        data: prescriptionData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Graphique de satisfaction
    new Chart(document.getElementById('satisfactionChart'), {
        type: 'bar',
        data: satisfactionData,
        options: {
            ...commonOptions,
            scales: {
                ...commonOptions.scales,
                y: {
                    ...commonOptions.scales.y,
                    min: 0,
                    max: 5
                }
            }
        }
    });

    // Graphique du temps d'attente
    new Chart(document.getElementById('waitTimeChart'), {
        type: 'line',
        data: waitTimeData,
        options: {
            ...commonOptions,
            scales: {
                ...commonOptions.scales,
                y: {
                    ...commonOptions.scales.y,
                    beginAtZero: true
                }
            }
        }
    });

    // Mini graphique de tendance
    new Chart(document.getElementById('weeklyTrendChart'), {
        type: 'line',
        data: weeklyTrendData,
        options: {
            ...commonOptions,
            elements: {
                point: {
                    radius: 3
                }
            }
        }
    });

    // Gestion des boutons de période pour le graphique principal
    document.querySelectorAll('input[name="consultationPeriod"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Ici vous pouvez ajouter la logique pour changer les données selon la période
            console.log('Période changée:', this.id);
        });
    });
});

// Fonction pour mettre à jour la période
function updatePeriod(period) {
    // Afficher un indicateur de chargement
    const loadingIndicator = document.getElementById('period-loading');
    loadingIndicator.classList.remove('d-none');
    
    // Désactiver les boutons pendant le chargement
    document.querySelectorAll('input[name="consultationPeriod"]').forEach(radio => {
        radio.disabled = true;
    });
    
    // Envoyer une requête pour récupérer les données mises à jour
    fetch(`/api/dashboard/update-period?period=${period}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        // Mettre à jour les graphiques avec les nouvelles données
        updateCharts(data);
        
        // Mettre à jour les compteurs
        if (data.stats) {
            document.getElementById('patient-count').textContent = data.stats.patientCount || 0;
            document.getElementById('today-rdv-count').textContent = data.stats.todayRdvCount || 0;
            document.getElementById('pending-rdv-count').textContent = data.stats.pendingRdvCount || 0;
            document.getElementById('consultation-count').textContent = data.stats.consultationCount || 0;
            document.getElementById('revenue').textContent = (data.stats.revenue || 0).toLocaleString() + '€';
        }
    })
    .catch(error => {
        console.error('Erreur lors de la mise à jour de la période:', error);
        // Afficher un message d'erreur à l'utilisateur
        alert('Une erreur est survenue lors de la mise à jour des données. Veuillez réessayer.');
    })
    .finally(() => {
        // Cacher l'indicateur de chargement et réactiver les boutons
        loadingIndicator.classList.add('d-none');
        document.querySelectorAll('input[name="consultationPeriod"]').forEach(radio => {
            radio.disabled = false;
        });
    });
}

// Fonction pour mettre à jour les graphiques avec de nouvelles données
function updateCharts(data) {
    // Mettre à jour chaque graphique avec les nouvelles données
    if (data.consultation) {
        consultationChart.data.labels = data.consultation.labels;
        consultationChart.data.datasets[0].data = data.consultation.data;
        consultationChart.update();
    }
    
    if (data.revenue) {
        revenueChart.data.labels = data.revenue.labels;
        revenueChart.data.datasets[0].data = data.revenue.data;
        revenueChart.update();
    }
    
    // Ajouter des mises à jour pour les autres graphiques selon les besoins
}

// Fonction pour rafraîchir les données du tableau de bord
function refreshDashboard() {
    fetch('/api/dashboard/data', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        // Mettre à jour les compteurs
        document.getElementById('patient-count').textContent = data.patientCount || 0;
        document.getElementById('today-rdv-count').textContent = data.todayRdvCount || 0;
        document.getElementById('pending-rdv-count').textContent = data.pendingRdvCount || 0;
        document.getElementById('consultation-count').textContent = data.consultationCount || 0;
        document.getElementById('revenue').textContent = (data.revenue || 0).toLocaleString() + '€';
        
        // Mettre à jour la liste des patients récents
        const patientsList = document.getElementById('recent-patients-list');
        if (patientsList && data.recentPatients) {
            // Implémentez la logique pour mettre à jour la liste des patients
        }
    })
    .catch(error => {
        console.error('Erreur lors du rafraîchissement du tableau de bord:', error);
    });
}

// Rafraîchir les données toutes les 5 minutes
setInterval(refreshDashboard, 5 * 60 * 1000);

// Initialisation du tableau de bord
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes au survol
    const cards = document.querySelectorAll('.stat-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Initialiser les tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Charger les données initiales
    refreshDashboard();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.medecin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/medecin/dashboard.blade.php ENDPATH**/ ?>