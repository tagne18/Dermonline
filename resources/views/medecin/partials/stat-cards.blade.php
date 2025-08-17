<!-- Cartes de statistiques améliorées -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Carte Patients -->
    <div class="card-hover-effect rounded-xl p-6 bg-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Patients</p>
                <div class="mt-2">
                    <h3 class="text-3xl font-bold text-gray-900 counter">{{ number_format($patient->nom ?? 0, 0, ',', ' ') }}</h3>
                    <div class="mt-2 flex items-center">
                        <span class="text-green-500 text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                            </svg>
                            +12% ce mois
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-icon-wrapper bg-gradient-primary">
                <i class="fas fa-users text-white"></i>
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('medecin.abonnements.patients') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                Voir tous les patients
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Carte Consultations -->
    <div class="card-hover-effect rounded-xl p-6 bg-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Consultations</p>
                <div class="mt-2">
                    <h3 class="text-3xl font-bold text-gray-900 counter">{{ number_format($consultationCount, 0, ',', ' ') }}</h3>
                    <div class="mt-2">
                        @if($consultationMonthCount > 0)
                            <span class="text-green-500 text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                </svg>
                                {{ $consultationMonthCount }} ce mois
                            </span>
                        @else
                            <span class="text-gray-500 text-sm">Aucune consultation ce mois</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-icon-wrapper bg-gradient-success">
                <i class="fas fa-stethoscope text-white"></i>
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('medecin.consultations.index') }}" class="text-sm font-medium text-green-600 hover:text-green-700">
                Voir les consultations
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Carte Rendez-vous en attente -->
    <div class="card-hover-effect rounded-xl p-6 bg-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">RDV en attente</p>
                <div class="mt-2">
                    <h3 class="text-3xl font-bold text-gray-900 counter">{{ $pendingRdvCount }}</h3>
                    <div class="mt-2">
                        @if($pendingRdvCount > 0)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>À confirmer
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-icon-wrapper bg-gradient-warning">
                <i class="far fa-calendar-check text-white"></i>
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('medecin.consultations.index') }}" class="text-sm font-medium text-yellow-600 hover:text-yellow-700">
                Gérer les consultations
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Carte Revenus -->
    <div class="card-hover-effect rounded-xl p-6 bg-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Revenus</p>
                <div class="mt-2">
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($monthlyRevenue, 0, ',', ' ') }} <span class="text-lg">FCFA</span></h3>
                    <div class="mt-2">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-chart-pie mr-1"></i>
                            <span>Mensuel</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-icon-wrapper bg-gradient-info">
                <i class="fas fa-coins text-white"></i>
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('medecin.abonnements.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                Voir les abonnements
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>
