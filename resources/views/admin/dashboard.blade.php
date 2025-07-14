@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-white text-2xl font-bold mb-4 mt-5">
        Bienvenue, {{ Auth::user()->name }}
    </h1>

    <div class="row g-3 mt-5">
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">👥 Patients inscrits</h5>
                    <p class="card-text fs-4">{{ $patientsInscrits ?? 0 }}</p>
                    <!-- Debug: Affichage des données pour diagnostiquer -->
                    <small class="text-white-50">
                        Debug: {{ $patientsInscrits ?? 'Variable non définie' }} | 
                        Type: {{ gettype($patientsInscrits ?? 'null') }}
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">🚫 Patients bloqués</h5>
                    <p class="card-text fs-4">{{ $patientsBloques ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">👨‍⚕️ Médecins actifs</h5>
                    <p class="card-text fs-4">{{ $medecinsActifs ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📅 Rendez-vous en attente</h5>
                    <p class="card-text fs-4">{{ $rendezVousEnAttente ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">🩺 Consultations effectuées</h5>
                    <p class="card-text fs-4">{{ $consultationsEffectuees ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <h4 class="mb-3 text-white">📢 Dernières notifications</h4>
    <ul class="list-group">
        @forelse($notifications ?? [] as $notif)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $notif->user->name ?? 'Utilisateur' }}</strong>: {{ $notif->content }}
                </div>
                <span class="badge bg-secondary">{{ $notif->created_at->format('d/m/Y H:i') }}</span>
            </li>
        @empty
            <li class="list-group-item text-muted">Aucune notification pour le moment.</li>
        @endforelse
    </ul>

    <h4 class="mb-3 text-white mt-5">Liste des patients</h4>
    <ul class="list-group mb-4">
        @forelse($patients as $patient)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $patient->name }} - {{ $patient->email }}
                <span class="badge bg-{{ $patient->abonnement && $patient->abonnement->statut === 'actif' ? 'success' : 'secondary' }}">
                    {{ $patient->abonnement && $patient->abonnement->statut === 'actif' ? 'Abonné' : 'Non abonné' }}
                </span>
            </li>
        @empty
            <li class="list-group-item">Aucun patient trouvé.</li>
        @endforelse
    </ul>

@endsection
