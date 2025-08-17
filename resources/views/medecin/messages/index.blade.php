@extends('layouts.medecin')

@section('content')
    <h1>Messages de la communauté</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('medecin.messages.store') }}" class="mb-4">
        @csrf
        <div class="mb-3">
            <textarea name="contenu" class="form-control" placeholder="Votre message..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>

    <ul class="list-group">
        @forelse($messages as $message)
            <li class="list-group-item">
                <strong>{{ $message->user->name ?? 'Utilisateur' }} :</strong>
                {{ $message->contenu }}
                <br>
                <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
            </li>
        @empty
            <li class="list-group-item">Aucun message pour le moment.</li>
        @endforelse
    </ul>
@endsection
