@extends('layouts.patient')

@section('title', 'Vérification du paiement abonnement')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Vérification du paiement</h4>
                </div>
                <div class="card-body">
                    @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <ul class="list-group mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Médecin référent</span>
                            <span class="fw-bold">
                                @if(isset($medecin_id))
                                    {{ optional(\App\Models\User::find($medecin_id))->name ?? '-' }}
                                @else
                                    -
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Numéro de téléphone</span>
                            <span class="fw-bold">{{ $phone ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Montant</span>
                            <span class="fw-bold text-success">{{ number_format($amount, 0, ',', ' ') }} FCFA</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Référence</span>
                            <span class="fw-bold">{{ $reference ?? '-' }}</span>
                        </li>
                    </ul>
                    <form method="POST" action="{{ route('patient.abonnements.verifyPayment') }}">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            Vérifier le paiement
                        </button>
                    </form>
                    <a href="{{ route('patient.abonnements.index') }}" class="btn btn-link mt-3">Retour à la liste des abonnements</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
