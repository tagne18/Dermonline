@extends('layouts.app')

@section('title', 'Paiement Abonnement')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Paiement de l'abonnement</h2>
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('patient.paiement.process') }}">
        @csrf
        <div class="mb-3">
            <label for="phone" class="form-label">Numéro de téléphone (Orange/MTN)</label>
            <input type="text" class="form-control" id="phone" name="phone" required placeholder="Ex: 671234567">
        </div>
        <div class="mb-3">
            <label for="operator" class="form-label">Opérateur</label>
            <select class="form-select" id="operator" name="operator" required>
                <option value="orange">Orange Money</option>
                <option value="mtn">MTN Mobile Money</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Montant de l'abonnement (XAF)</label>
            <input type="number" class="form-control" id="amount" name="amount" required min="100" value="1000">
        </div>
        <button type="submit" class="btn btn-primary">Payer</button>
    </form>
</div>
@endsection 