<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Détail de la consultation
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-lg p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-indigo-700">Consultation du {{ \Carbon\Carbon::parse($consultation->date)->format('d/m/Y') }}</h3>
                    <a href="{{ route('patient.consultations.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        ← Retour
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">Informations générales</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium">Médecin :</span>
                                    <span class="text-gray-600">{{ $consultation->medecin->name ?? 'Non renseigné' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Spécialité :</span>
                                    <span class="text-gray-600">{{ $consultation->medecin->specialite ?? 'Non renseignée' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Type :</span>
                                    <span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $consultation->type ?? 'Non défini')) }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Statut :</span>
                                    @if($consultation->statut === 'valide')
                                        <span class="text-green-600 font-semibold">Validée</span>
                                    @elseif($consultation->statut === 'refuse')
                                        <span class="text-red-600 font-semibold">Refusée</span>
                                    @else
                                        <span class="text-yellow-600 font-semibold">En attente</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-gray-800 mb-2">Détails de la consultation</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium">Motif :</span>
                                    <span class="text-gray-600">{{ $consultation->motif ?? 'Non renseigné' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium">Description :</span>
                                    <p class="text-gray-600 mt-1">{{ $consultation->description ?? 'Aucune description fournie' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @if($consultation->photos)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-3">Images associées</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach(json_decode($consultation->photos, true) ?? [] as $photo)
                                        <img src="{{ Storage::url($photo) }}" 
                                             alt="Photo de consultation" 
                                             class="w-full h-32 object-cover rounded shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                                             onclick="window.open('{{ Storage::url($photo) }}', '_blank')">
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($consultation->created_at)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-800 mb-2">Informations temporelles</h4>
                                <div class="space-y-2">
                                    <div>
                                        <span class="font-medium">Créée le :</span>
                                        <span class="text-gray-600">{{ $consultation->created_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                    @if($consultation->updated_at && $consultation->updated_at != $consultation->created_at)
                                        <div>
                                            <span class="font-medium">Modifiée le :</span>
                                            <span class="text-gray-600">{{ $consultation->updated_at->format('d/m/Y à H:i') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($consultation->type === 'en_ligne' && $consultation->statut === 'valide')
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h4 class="font-semibold text-blue-800 mb-2">Consultation en ligne</h4>
                        <p class="text-blue-700 mb-3">Cette consultation peut être effectuée en ligne. Cliquez sur le bouton ci-dessous pour commencer.</p>
                        <a href="{{ route('patient.consultation.enligne', $consultation->id) }}" 
                           class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                            Démarrer la consultation en ligne
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 