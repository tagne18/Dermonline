
@section('content')
<div class="container">
    <h1 class="mb-4">Liste des abonnements</h1>
    @if($abonnements->isEmpty())
        <div class="alert alert-info">Aucun abonnement trouvé.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Date de création</th>
                </tr>
            </thead>
            <tbody>
                @foreach($abonnements as $abonnement)
                    <tr>
                        <td>{{ $abonnement->id }}</td>
                        <td>{{ $abonnement->nom ?? '-' }}</td>
                        <td>{{ $abonnement->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
