@extends('layouts.admin')

@section('title', 'Abonnés Newsletter')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-white">📰 Abonnés à la Newsletter</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-5">
        <form method="POST" action="{{ route('admin.newsletters.send') }}">
            @csrf
            <div class="mb-3">
                <label for="subject" class="form-label text-white">Sujet de l'email</label>
                <input type="text" name="subject" id="subject" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label text-white">Contenu du message</label>
                <textarea name="content" id="content" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer à tous les abonnés</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $subscriber)
                    <tr>
                        <td>{{ $subscriber->id }}</td>
                        <td>{{ $subscriber->email }}</td>
                        <td>{{ $subscriber->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Aucun abonné pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $subscribers->links() }}
    </div>
@endsection 