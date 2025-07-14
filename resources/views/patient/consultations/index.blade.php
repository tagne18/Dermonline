<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mes consultations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Notifications</h3>
                @if(isset($notifications) && $notifications->count())
                    <ul class="mb-6 space-y-2">
                        @foreach($notifications as $notification)
                            <li class="p-3 bg-gray-100 rounded-lg">
                                <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong>
                                <p class="text-gray-700">{{ $notification->data['message'] ?? $notification->data['status'] ?? 'Notification reçue' }}</p>
                                <span class="text-xs text-gray-500">{{ $notification->created_at ? $notification->created_at->diffForHumans() : '' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mb-6 text-gray-500">Aucune notification récente.</p>
                @endif

                <h3 class="text-lg font-bold mb-4">Mes rendez-vous à venir</h3>
                @if(isset($appointments) && $appointments->where('statut', '!=', 'refuse')->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Médecin</th>
                                    <th class="px-4 py-2 text-left">Date/Heure</th>
                                    <th class="px-4 py-2 text-left">Type</th>
                                    <th class="px-4 py-2 text-left">Statut</th>
                                    <th class="px-4 py-2 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $appointment)
                                    @if($appointment->statut !== 'refuse')
                                    <tr class="border-b">
                                        <td class="px-4 py-2">
                                            @php $medecin = $appointment->medecin ?? \App\Models\User::find($appointment->medecin_id); @endphp
                                            <div class="font-semibold">{{ $medecin->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $medecin->specialite ?? 'Non définie' }}</div>
                                        </td>
                                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} à {{ $appointment->heure }}</td>
                                        <td class="px-4 py-2 capitalize">{{ str_replace('_', ' ', $appointment->type) }}</td>
                                        <td class="px-4 py-2 capitalize">
                                            @if($appointment->statut === 'valide')
                                                <span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Validé</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">En attente</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">
                                            @if($appointment->type === 'en_ligne' && $appointment->statut === 'valide')
                                                @php
                                                    $rdvDateTime = \Carbon\Carbon::parse($appointment->date . ' ' . $appointment->heure);
                                                    $now = \Carbon\Carbon::now();
                                                @endphp
                                                @if($now->greaterThanOrEqualTo($rdvDateTime))
                                                    <a href="{{ route('patient.consultation.enligne', $appointment) }}" 
                                                       class="bg-blue-600 text-success px-3 py-1 rounded hover:bg-blue-700">
                                                        Démarrer ma consultation
                                                    </a>
                                                @else
                                                    <button disabled 
                                                            class="bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed">
                                                        Consultation non disponible
                                                    </button>
                                                    <div id="countdown-{{ $appointment->id }}" class="text-sm text-gray-500 mt-1"></div>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            function startCountdown_{{ $appointment->id }}() {
                                                                var rdvTime = new Date("{{ $rdvDateTime ? $rdvDateTime->format('Y-m-d H:i:s') : '' }}").getTime();
                                                                var x = setInterval(function() {
                                                                    var now = new Date().getTime();
                                                                    var distance = rdvTime - now;
                                                                    if (distance < 0) {
                                                                        clearInterval(x);
                                                                        location.reload(); // Recharger pour afficher le bouton actif
                                                                    } else {
                                                                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                                                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                                                        document.getElementById('countdown-{{ $appointment->id }}').innerHTML = 
                                                                            'Disponible dans ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
                                                                    }
                                                                }, 1000);
                                                            }
                                                            startCountdown_{{ $appointment->id }}();
                                                        });
                                                    </script>
                                                @endif
                                            @elseif($appointment->type === 'presentiel' && $appointment->statut === 'valide')
                                                <span class="text-blue-700">Présentez-vous à la clinique à l'heure prévue</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                            <a href="{{ route('patient.consultations.show', $appointment->id) }}" class="ml-2 bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">
                                                Détail
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">Aucun rendez-vous à venir.</p>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countdownElements = document.querySelectorAll('.countdown-timer');

            countdownElements.forEach(element => {
                const rdvTime = new Date(element.dataset.datetime).getTime();

                const interval = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = rdvTime - now;

                    if (distance < 0) {
                        clearInterval(interval);
                        // Au lieu de recharger, on pourrait juste changer le style du bouton,
                        // mais pour l'instant, on garde la logique de rechargement.
                        location.reload(); 
                    } else {
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        element.innerHTML = 'Disponible dans ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
                    }
                }, 1000);
            });
        });
    </script>
    @endpush
</x-app-layout>
