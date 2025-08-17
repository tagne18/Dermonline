@extends('layouts.medecin')

@section('title', 'Nouvelle prescription')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📝 Nouvelle ordonnance</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('medecin.ordonnances.store') }}" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf
        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre de l'ordonnance <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="titre" name="titre" required value="{{ old('titre') }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description / Détails</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="date_prescription" class="form-label">Date de prescription <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="date_prescription" name="date_prescription" required value="{{ old('date_prescription', date('Y-m-d')) }}">
        </div>
        <div class="mb-3">
            <label for="fichier" class="form-label">Fichier (PDF, JPG, PNG, max 4 Mo)</label>
            <input type="file" class="form-control" id="fichier" name="fichier" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Envoyer l'ordonnance au patient</button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary ms-2">Annuler</a>
        </div>
    </form>
</div>
@endsection
