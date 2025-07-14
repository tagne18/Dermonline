@extends('layouts.app')

@section('title', 'Vérification du paiement')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Vérification du paiement</h2>
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form method="POST" action="{{ route('patient.paiement.verify') }}">
        @csrf
        <button type="submit" class="btn btn-success">Vérifier le paiement</button>
    </form>
    <a href="{{ route('patient.paiement.form') }}" class="btn btn-link mt-3">Revenir au paiement</a>
</div>
@endsection 