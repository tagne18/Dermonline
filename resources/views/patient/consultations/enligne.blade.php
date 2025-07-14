@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white shadow rounded p-6 max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-4 text-center">Consultation en ligne</h2>
        <div class="mb-4">
            <p class="font-semibold">Médecin : <span class="font-normal">{{ $appointment->medecin->name ?? '-' }}</span></p>
            <p class="font-semibold">Spécialité : <span class="font-normal">{{ $appointment->specialite ?? '-' }}</span></p>
            <p class="font-semibold">Date/Heure : <span class="font-normal">{{ $appointment->date }} {{ $appointment->heure }}</span></p>
        </div>
        <div class="border rounded p-4 bg-gray-50 text-center mb-4">
            <p class="mb-2">La visio sera disponible ici.</p>
            <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded">Fonctionnalité à venir</span>
        </div>
        <a href="{{ route('patient.consultations.index') }}" class="text-blue-600 hover:underline">Retour à mes consultations</a>
    </div>
</div>
@endsection 