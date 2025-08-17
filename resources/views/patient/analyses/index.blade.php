@extends('layouts.app')
@section('content')
<div class="container py-4">
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
    <h2 class="mb-4">Mes analyses d’images dermatologiques</h2>
    @if($analyses->isEmpty())
        <div class="alert alert-info">Aucune analyse disponible.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Image</th>
                    <th>Résultat IA</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analyses as $analysis)
                <tr>
                    <td>{{ $analysis->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <img src="{{ asset('storage/'.$analysis->image_path) }}" alt="Image analysée" style="max-width:90px; max-height:90px; border-radius:6px;">
                    </td>
                    <td style="white-space:pre-line; max-width:400px;">
                        {{ $analysis->result }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
