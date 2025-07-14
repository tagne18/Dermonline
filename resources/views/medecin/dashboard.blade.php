@extends('layouts.medecin')

@section('title', 'Dashboard Médecin')

@section('content')
    {{-- <h1 class="text-mt-5 ">Bienvenue, Dr. {{ Auth::user()->name }}</h1> --}}
    <h1 class="text-dark text-2xl font-bold px-5 mb-4 mt-5">
        Bienvenue, Dr {{ Auth::user()->name }}
    </h1>

    <div class="row g-3 mt-5 px-5">
            <div class="col-md-4">
                <div class="card text-dark bg-success shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">👥 Patients abonnés</h5>
                        <p class="card-text fs-4">{{ $patient->nom ?? 0 }}</p>
                    </div>
                </div>
            </div>


        <div class="col-md-4">
            <div class="card text-dark bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📅 Rendez-vous en attente</h5>
                    <p class="card-text fs-4">{{ $pendingRdvCount ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-dark bg-info shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">🩺 Consultations effectuées</h5>
                    <p class="card-text fs-4">{{ $consultationCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>


    <hr class="my-4">

    <h4 class="mb-3 text-dark px-5">📢 Dernières notifications</h4>
    <ul class="list-group px-5">
        @forelse($notifications ?? [] as $notif)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $notif->message }}
                <span class="badge bg-secondary">{{ $notif->created_at->format('d/m/Y') }}</span>
            </li>
        @empty
            <li class="list-group-item px-5 bg-info">Aucune notification pour le moment.</li>
        @endforelse
    </ul>
@endsection
