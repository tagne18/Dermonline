<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center">
            <i class="fas fa-calendar-check me-3 text-primary fs-4"></i>
            <h2 class="mb-0 fw-bold text-dark">
    {{ __('Mes consultations') }}
</h2>
<a href="/patient/analyses" class="btn btn-outline-info ms-3 d-flex align-items-center" style="height:40px;">
    <i class="fas fa-image me-2"></i> Mes analyses d’images
</a>
        </div>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <!-- Notifications Section -->
                <div class="container">
                    <div class="title">
                        <h1>
                            notification
                        </h1>
                    </div>
                </div>
                <!-- /Notification Section -->
                 
                <!-- Appointments Section -->
                <div class="card border-0 shadow-sm appointments-card py-5 mt-5">
                    <div class="card-header bg-gradient-success text-white border-0 rounded-top">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <h5 class="mb-0 fw-semibold text-dark fz-6">Mes rendez-vous à venir</h5>
                            </div>
                            <span class="badge bg-light text-dark">
                                {{ isset($appointments) ? $appointments->where('statut', '!=', 'refuse')->count() : 0 }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if(isset($appointments) && $appointments->where('statut', '!=', 'refuse')->count())
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 modern-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 fw-semibold text-uppercase small ps-4">
                                                <i class="fas fa-user-md me-2 text-primary"></i>Médecin
                                            </th>
                                            <th class="border-0 fw-semibold text-uppercase small">
                                                <i class="fas fa-calendar me-2 text-primary"></i>Date/Heure
                                            </th>
                                            <th class="border-0 fw-semibold text-uppercase small">
                                                <i class="fas fa-tag me-2 text-primary"></i>Type
                                            </th>
                                            <th class="border-0 fw-semibold text-uppercase small">
                                                <i class="fas fa-check-circle me-2 text-primary"></i>Statut
                                            </th>
                                            <th class="border-0 fw-semibold text-uppercase small">
                                                <i class="fas fa-cogs me-2 text-primary"></i>Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appointments as $appointment)
                                            @if($appointment->statut !== 'refuse')
                                            <tr class="appointment-row">
                                                <td class="ps-4 py-3">
                                                    @php $medecin = $appointment->medecin ?? \App\Models\User::find($appointment->medecin_id); @endphp
                                                    <div class="d-flex align-items-center">
                                                        <div class="doctor-avatar me-3">
                                                            <i class="fas fa-user-md text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold text-dark">{{ $medecin->name ?? 'N/A' }}</div>
                                                            <small class="text-muted">
                                                                <i class="fas fa-stethoscope me-1"></i>
                                                                {{ $medecin->specialite ?? 'Non définie' }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="fw-semibold text-dark">
                                                        <i class="fas fa-calendar-day me-2 text-primary"></i>
                                                        {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ $appointment->heure }}
                                                    </small>
                                                </td>
                                                <td class="py-3">
                                                    @if($appointment->type === 'en_ligne')
                                                        <span class="badge bg-info text-white px-3 py-2 rounded-pill">
                                                            <i class="fas fa-video me-1"></i>
                                                            En ligne
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary text-white px-3 py-2 rounded-pill">
                                                            <i class="fas fa-hospital me-1"></i>
                                                            Présentiel
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3">
                                                    @if($appointment->statut === 'valide')
                                                        <span class="badge bg-success px-3 py-2 rounded-pill status-badge">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Validé
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning px-3 py-2 rounded-pill status-badge">
                                                            <i class="fas fa-hourglass-half me-1"></i>
                                                            En attente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3">
                                                    <div class="d-flex gap-2 flex-wrap">
                                                        @if($appointment->type === 'en_ligne' && $appointment->statut === 'valide')
                                                            @php
                                                                $rdvDateTime = \Carbon\Carbon::parse($appointment->date . ' ' . $appointment->heure);
                                                                $now = \Carbon\Carbon::now();
                                                            @endphp
                                                            @if($now->greaterThanOrEqualTo($rdvDateTime))
                                                                <button type="button"
   class="btn btn-primary btn-sm px-3 rounded-pill consultation-btn demarrer-consultation-btn"
   data-appointment-id="{{ $appointment->id }}"
   data-datetime="{{ \Carbon\Carbon::parse($appointment->date.' '.$appointment->heure)->format('Y-m-d\TH:i:s') }}">
    <i class="fas fa-play me-1"></i>
    Démarrer
</button>
                                                            @else
                                                                <button id="btn-demarrer-{{ $appointment->id }}" type="button" disabled
    class="btn btn-outline-secondary btn-sm px-3 rounded-pill consultation-btn dynamic-demarrer-btn"
    data-appointment-id="{{ $appointment->id }}"
    data-datetime="{{ \Carbon\Carbon::parse($appointment->date.' '.$appointment->heure)->format('Y-m-d\TH:i:s') }}">
    <i class="fas fa-lock me-1"></i>
    Non disponible
</button>
<div id="countdown-{{ $appointment->id }}" class="countdown-display small text-muted mt-1">
    <i class="fas fa-stopwatch me-1"></i>
</div>
                                                                <script>
                                                                    document.addEventListener('DOMContentLoaded', function() {
                                                                        function startCountdown_{{ $appointment->id }}() {
                                                                            var rdvTime = new Date("{{ $rdvDateTime ? $rdvDateTime->format('Y-m-d H:i:s') : '' }}").getTime();
                                                                            var x = setInterval(function() {
                                                                                var now = new Date().getTime();
                                                                                var distance = rdvTime - now;
                                                                                if (distance < 0) {
                                                                                    clearInterval(x);
                                                                                    document.getElementById('countdown-{{ $appointment->id }}').innerHTML = '<span class="text-danger">Expiré</span>';
                                                                                } else {
                                                                                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                                                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                                                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                                                    document.getElementById('countdown-{{ $appointment->id }}').innerHTML = 
                                                                                        '<i class="fas fa-stopwatch me-1"></i>Dans ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
                                                                                }
                                                                            }, 1000);
                                                                        }
                                                                        startCountdown_{{ $appointment->id }}();
                                                                    });
                                                                </script>
                                                            @endif
                                                        @elseif($appointment->type === 'presentiel' && $appointment->statut === 'valide')
                                                            <div class="text-info small">
                                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                                Présentez-vous à la clinique
                                                            </div>
                                                        @endif
                                                        
                                                        <button type="button"
   class="btn btn-outline-primary btn-sm px-3 rounded-pill detail-btn btn-modal-detail"
   data-id="{{ $appointment->id }}"
   data-medecin="{{ $medecin->name ?? 'N/A' }}"
   data-specialite="{{ $medecin->specialite ?? 'Non définie' }}"
   data-date="{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}"
   data-heure="{{ $appointment->heure }}"
   data-type="{{ $appointment->type === 'en_ligne' ? 'En ligne' : 'Présentiel' }}"
   data-statut="{{ $appointment->statut }}">
    <i class="fas fa-eye me-1"></i>
    Détail
</button>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
                                <h5 class="text-muted">Aucun rendez-vous à venir</h5>
                                <p class="text-muted mb-0">Vos prochains rendez-vous apparaîtront ici.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .notification-card,
        .appointments-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .notification-card:hover,
        .appointments-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
        }

        .notification-item {
            transition: all 0.3s ease;
            position: relative;
        }

        .notification-item:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .notification-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .notification-item:hover .notification-glow {
            transform: translateX(100%);
        }

        .modern-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .appointment-row {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .appointment-row:hover {
            background-color: #f8f9ff !important;
            border-left-color: #667eea;
            transform: scale(1.01);
        }

        .doctor-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .status-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(var(--bs-success-rgb), 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(var(--bs-success-rgb), 0); }
            100% { box-shadow: 0 0 0 0 rgba(var(--bs-success-rgb), 0); }
        }

        .consultation-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .consultation-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .detail-btn {
            transition: all 0.3s ease;
        }

        .detail-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        }

        .countdown-display {
            padding: 5px 10px;
            background: linear-gradient(135deg, #ffeaa7, #fab1a0);
            border-radius: 15px;
            color: #636e72;
            font-weight: 500;
            display: inline-block;
            animation: countdownPulse 1s infinite alternate;
        }

        @keyframes countdownPulse {
            from { opacity: 0.8; }
            to { opacity: 1; }
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }

        .table th {
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .table-responsive {
                border-radius: 10px;
            }
            
            .d-flex.gap-2 {
                flex-direction: column;
                gap: 0.5rem !important;
            }
        }
    </style>
    @endpush

    <!-- Modal d'alerte RDV 5 minutes restantes -->
    <div id="modal-rdv-5min" style="display:none; position:fixed; z-index:2100; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.38); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:15px; max-width:370px; margin:auto; box-shadow:0 8px 32px rgba(0,0,0,0.22); padding:32px 24px 24px 24px; text-align:center; position:relative;">
            <button id="close-modal-5min" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.3rem; color:#888; cursor:pointer;"><i class="fas fa-times"></i></button>
            <i class="fas fa-exclamation-circle text-danger" style="font-size:2.6rem; color:#e53935;"></i>
            <h5 style="margin:18px 0 10px 0; color:#e53935; font-weight:700;">Attention : Dernière chance !</h5>
            <div style="color:gray; font-size:1.08rem; margin-bottom:12px;">Vous avez <span id="countdown-5min" style="font-weight:bold; color:gray;">5:00</span> pour démarrer votre consultation en ligne.<br>Passé ce délai, il faudra reprendre un rendez-vous.</div>
            <button id="close-modal-5min2" style="background:#2196f3; color:#fff; border:none; border-radius:7px; padding:8px 22px; font-weight:600; font-size:1rem; margin-top:8px; cursor:pointer;">Fermer</button>
        </div>
    </div>

    <!-- Modal d'alerte RDV dépassé -->
    <div id="modal-rdv-expire" style="display:none; position:fixed; z-index:2000; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.38); align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:15px; max-width:370px; margin:auto; box-shadow:0 8px 32px rgba(0,0,0,0.22); padding:32px 24px 24px 24px; text-align:center; position:relative;">
            <button id="close-modal-expire" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.3rem; color:#888; cursor:pointer;"><i class="fas fa-times"></i></button>
            <i class="fas fa-exclamation-triangle text-warning" style="font-size:2.6rem; color:#e53935;"></i>
            <h5 style="margin:18px 0 10px 0; color:#e53935; font-weight:700;">Consultation expirée</h5>
            <div style="color:#555; font-size:1.08rem; margin-bottom:12px;">L'heure de votre consultation est déjà passée.<br>Veuillez prendre un nouveau rendez-vous.</div>
            <button onclick="window.location.href='/patient/appointments'" style="background:#43a047; color:#fff; border:none; border-radius:7px; padding:8px 22px; font-weight:600; font-size:1rem; margin-top:8px; cursor:pointer;">Prendre un rendez-vous</button>
            <button id="close-modal-expire2" style="background:#2196f3; color:#fff; border:none; border-radius:7px; padding:8px 22px; font-weight:600; font-size:1rem; margin-top:8px; cursor:pointer; margin-left:10px;">Fermer</button>
        </div>
    </div>

    <div id="camera-popup" style="display:none; position:fixed; top:30px; right:30px; z-index:1050; background:#fff; border-radius:8px; box-shadow:0 2px 32px rgba(0,0,0,0.18); padding:0 0 12px 0; width:380px; max-width:98vw; min-width:260px;">
        <div style="display:flex; justify-content:space-between; align-items:center; background:#2196f3; border-radius:18px 18px 0 0; padding:13px 18px 13px 18px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <i class="fas fa-user-circle" style="font-size:1.8rem; color:#fff;"></i>
                <span style="font-weight:600; color:#fff; font-size:1.12rem;">{{ auth()->user()->name ?? 'Patient' }}</span>
                <i class="fas fa-video ms-2" style="color:#fff; font-size:1.2rem;"></i>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <button id="btn-demarrer-consult" style="background:#43a047; border:none; color:#fff; font-weight:600; border-radius:7px; padding:6px 14px; font-size:0.96rem; cursor:pointer;">
                    <i class="fas fa-play me-1"></i>Démarrer
                </button>
                <button id="btn-fin-consult" style="background:#e53935; border:none; color:#fff; font-weight:600; border-radius:7px; padding:6px 14px; font-size:0.96rem; cursor:pointer;">
                    <i class="fas fa-stop me-1"></i>Fin de consultation
                </button>
                <button id="close-camera-popup" style="background:transparent; border:none; color:#fff; font-size:1.3rem; cursor:pointer; margin-left:5px;" aria-label="Fermer"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div style="padding:18px; display:flex; flex-direction:column; align-items:center;">
            <video id="camera-video" autoplay playsinline style="width:100%; border-radius:12px; background:#222; min-height:180px;"></video>
            <div id="camera-error" style="display:none; color:#e53935; margin-top:12px; text-align:center; font-size:0.95em;"></div>
        </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation d'entrée pour les cartes
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Animation pour les lignes de rendez-vous
            const rows = document.querySelectorAll('.appointment-row');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateX(-20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.4s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                }, 800 + (index * 100));
            });

            // Gestion des compteurs
            const countdownElements = document.querySelectorAll('.countdown-timer');
            countdownElements.forEach(element => {
                const rdvTime = new Date(element.dataset.datetime).getTime();
                const interval = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = rdvTime - now;
                    if (distance < 0) {
                        clearInterval(interval);
                        element.innerHTML = '<span class="text-danger">Expiré</span>';
                    } else {
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        element.innerHTML = '<i class="fas fa-stopwatch me-1"></i>Dans ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
                    }
                }, 1000);
            });

            // === Gestion du bouton Démarrer et activation caméra (robuste, DRY) ===
            function setupDemarrerBtn(btn, rdvTime) {
                // Nettoie les anciens écouteurs (évite les doublons)
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);
                // Transforme le bouton
                newBtn.disabled = false;
                newBtn.classList.remove('btn-outline-secondary');
                newBtn.classList.add('btn-primary', 'demarrer-consultation-btn');
                newBtn.innerHTML = '<i class="fas fa-play me-1"></i> Démarrer';
                // Handler unique
                newBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const nowClick = new Date().getTime();
                    const fiveMinutes = 5 * 60 * 1000;
                    if (newBtn.disabled) return;
                    // Refus si hors créneau
                    if (nowClick > (rdvTime + fiveMinutes)) {
                        document.getElementById('modal-rdv-expire').style.display = 'flex';
                        return;
                    }
                    ouvrirCameraPopup();
                });
            }

            function ouvrirCameraPopup() {
                const popup = document.getElementById('camera-popup');
                popup.style.display = 'block';
                if(window.innerWidth < 500) {
                    popup.style.width = '98vw';
                    popup.style.right = '1vw';
                    popup.style.top = '12vw';
                } else {
                    popup.style.width = '380px';
                    popup.style.right = '30px';
                    popup.style.top = '30px';
                }
                // Démarre la caméra si besoin
                if (!window.currentStream) {
                    demarrerCamera();
                }
            }

            document.querySelectorAll('.dynamic-demarrer-btn').forEach(function(btn) {
                const rdvDateTime = btn.getAttribute('data-datetime');
                if (!rdvDateTime) return;
                const rdvTime = new Date(rdvDateTime).getTime();
                const now = new Date().getTime();
                const diff = rdvTime - now;
                const fiveMinutes = 5 * 60 * 1000;
                if (diff > 0) {
                    btn.disabled = true;
                    const interval = setInterval(function() {
                        const now2 = new Date().getTime();
                        const diff2 = rdvTime - now2;
                        if (diff2 <= 0) {
                            clearInterval(interval);
                            setupDemarrerBtn(btn, rdvTime);
                            document.getElementById('modal-rdv-5min').style.display = 'flex';
                            lancerCompteARebours5min(btn, rdvTime);
                        }
                    }, 1000);
                } else if (diff > -fiveMinutes) {
                    setupDemarrerBtn(btn, rdvTime);
                    document.getElementById('modal-rdv-5min').style.display = 'flex';
                    lancerCompteARebours5min(btn, rdvTime);
                } else {
                    btn.disabled = true;
                }
            });

            // Compatibilité anciens boutons
            document.querySelectorAll('.demarrer-consultation-btn').forEach(function(btn) {
                if (btn.classList.contains('dynamic-demarrer-btn')) return;
                const rdvDateTime = btn.getAttribute('data-datetime');
                if (!rdvDateTime) return;
                const rdvTime = new Date(rdvDateTime).getTime();
                const now = new Date().getTime();
                const diff = rdvTime - now;
                const fiveMinutes = 5 * 60 * 1000;
                if (diff > 0) {
                    btn.disabled = true;
                    const interval = setInterval(function() {
                        const now2 = new Date().getTime();
                        const diff2 = rdvTime - now2;
                        if (diff2 <= 0) {
                            clearInterval(interval);
                            btn.disabled = false;
                            document.getElementById('modal-rdv-5min').style.display = 'flex';
                            lancerCompteARebours5min(btn, rdvTime);
                        }
                    }, 1000);
                } else if (diff > -fiveMinutes) {
                    btn.disabled = false;
                    document.getElementById('modal-rdv-5min').style.display = 'flex';
                    lancerCompteARebours5min(btn, rdvTime);
                } else {
                    btn.disabled = true;
                }
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (btn.disabled) return;
                    ouvrirCameraPopup();
                });
            });

            function lancerCompteARebours5min(btn, rdvTime) {
                var fiveMin = 5 * 60;
                var debut = rdvTime;
                var interval = setInterval(function() {
                    var now = new Date().getTime();
                    var elapsed = Math.floor((now - debut) / 1000);
                    var restant = fiveMin - elapsed;
                    if (restant <= 0) {
                        clearInterval(interval);
                        btn.disabled = true;
                        document.getElementById('modal-rdv-5min').style.display = 'none';
                        document.getElementById('modal-rdv-expire').style.display = 'flex';
                        document.getElementById('countdown-5min').textContent = '0:00';
                    } else {
                        var min = Math.floor(restant / 60);
                        var sec = restant % 60;
                        document.getElementById('countdown-5min').textContent = min + ':' + (sec < 10 ? '0' : '') + sec;
                    }
                }, 1000);
            }

            document.getElementById('close-camera-popup').onclick = function() {
                fermerCameraPopup();
            };
            document.getElementById('btn-fin-consult').onclick = function() {
                fermerCameraPopup();
            };
            document.getElementById('btn-demarrer-consult').onclick = function() {
                if (!currentStream) {
                    demarrerCamera();
                }
            };
            let currentStream = null;
            function demarrerCamera() {
                const video = document.getElementById('camera-video');
                const errorDiv = document.getElementById('camera-error');
                errorDiv.style.display = 'none';
                navigator.mediaDevices.getUserMedia({ video: true, audio: false })
                    .then(function(stream) {
                        currentStream = stream;
                        video.srcObject = stream;
                    })
                    .catch(function(err) {
                        errorDiv.style.display = 'block';
                        errorDiv.textContent = "Impossible d'accéder à la caméra : " + err.message + "\nVérifiez les permissions du navigateur.";
                    });
            }
            function activerCameraPopup() {
                const popup = document.getElementById('camera-popup');
                popup.style.display = 'block';
                // Responsive ajustement mobile
                if(window.innerWidth < 500) {
                    popup.style.width = '98vw';
                    popup.style.right = '1vw';
                    popup.style.top = '12vw';
                } else {
                    popup.style.width = '380px';
                    popup.style.right = '30px';
                    popup.style.top = '30px';
                }
            }
            function fermerCameraPopup() {
                const popup = document.getElementById('camera-popup');
                const video = document.getElementById('camera-video');
                popup.style.display = 'none';
                if (video.srcObject) {
                    video.srcObject.getTracks().forEach(track => track.stop());
                    video.srcObject = null;
                }
                currentStream = null;
            }    
            // Gestion centralisée des fermetures de modals
            document.getElementById('close-modal-expire')?.addEventListener('click', function() {
                document.getElementById('modal-rdv-expire').style.display = 'none';
            });
            document.getElementById('close-modal-expire2')?.addEventListener('click', function() {
                document.getElementById('modal-rdv-expire').style.display = 'none';
            });
            document.getElementById('close-modal-5min')?.addEventListener('click', function() {
                document.getElementById('modal-rdv-5min').style.display = 'none';
            });
            document.getElementById('close-modal-5min2')?.addEventListener('click', function() {
                document.getElementById('modal-rdv-5min').style.display = 'none';
            });
        });
    </script>
    @endpush
</x-app-layout>