@extends('layouts.admin')

@section('content')
    <h1>Liste des consultations</h1>
    @if(isset($consultations) && $consultations->count())
        <ul>
            @foreach($consultations as $consultation)
                <li>
                    Consultation #{{ $consultation->id }} - 
                    Patient : {{ $consultation->patient->name ?? 'N/A' }} - 
                    Médecin : {{ $consultation->medecin->name ?? 'N/A' }}
                </li>
            @endforeach
        </ul>
    @else
        <p>Aucune consultation trouvée.</p>
    @endif
@endsection 