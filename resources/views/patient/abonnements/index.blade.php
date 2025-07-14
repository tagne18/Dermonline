<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vos Abonnements') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Nos Packs Santé</h2>
            <p class="mt-2 text-gray-600">Choisissez la formule adaptée à vos besoins pour un suivi dermatologique de qualité.</p>
        </div>

        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 sm:px-6 lg:px-8">
            <!-- Pack Découverte -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pack Découverte</h3>
                <h4 class="text-3xl font-bold text-indigo-600 mb-4">Gratuit</h4>
                <ul class="text-left space-y-2 text-gray-600 mb-6">
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Consultations de base illimitées</li>
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Accès à votre dossier médical</li>
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Notifications santé par email</li>
                    <li class="flex items-center text-gray-400"><i class="bi bi-x-circle-fill text-gray-400 mr-2"></i>Téléconsultations vidéo</li>
                    <li class="flex items-center text-gray-400"><i class="bi bi-x-circle-fill text-gray-400 mr-2"></i>Support prioritaire</li>
                </ul>
                <a href="#" class="block w-full text-center py-2 text-indigo-600 font-semibold bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors duration-200">S'inscrire</a>
            </div>

            <!-- Pack Premium -->
            <div class="relative bg-white border-2 border-indigo-600 rounded-2xl p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="absolute top-4 right-4">
                    <span class="bg-indigo-100 text-indigo-600 text-xs font-semibold px-2 py-1 rounded-full">Populaire</span>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pack Premium</h3>
                <h4 class="text-3xl font-bold text-indigo-600 mb-4">29€<span class="text-base text-gray-500"> /mois</span></h4>
                <ul class="text-left space-y-2 text-gray-600 mb-6">
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Consultations spécialisées illimitées</li>
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Téléconsultations vidéo incluses</li>
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Accès à tous les documents médicaux</li>
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Support prioritaire 7j/7</li>
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Suivi santé personnalisé</li>
                </ul>
                <a href="#" class="block w-full text-center py-2 text-white font-semibold bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors duration-200">Souscrire</a>
            </div>

            <!-- Pack Expert Santé -->
            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Pack Expert Santé</h3>
                <h4 class="text-3xl font-bold text-indigo-600 mb-4">49€<span class="text-base text-gray-500"> /mois</span></h4>
                <ul class="text-left space-y-2 text-gray-600 mb-6">
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Accès complet à toutes les spécialités</li>
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Support 24/7 avec médecins certifiés</li>
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Suivi patient avancé</li>
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Archivage sécurisé des dossiers</li>
                    <li class="flex items-center"><i class="bi bi-check-circle-fill text-green-500 mr-2"></i>Assistance administrative médicale</li>
                </ul>
                <a href="#" class="block w-full text-center py-2 text-indigo-600 font-semibold bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors duration-200">Souscrire</a>
            </div>
        </div>

        <div class="max-w-3xl mx-auto mt-12 text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">Pourquoi s'abonner ?</h3>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-600">
                <li class="flex items-center gap-2"><i class="bi bi-lightning-charge text-indigo-500"></i>Accès rapide à un dermatologue</li>
                <li class="flex items-center gap-2"><i class="bi bi-shield-check text-indigo-500"></i>Suivi médical sécurisé</li>
                <li class="flex items-center gap-2"><i class="bi bi-gift text-indigo-500"></i>Offres exclusives abonnés</li>
                <li class="flex items-center gap-2"><i class="bi bi-emoji-smile text-indigo-500"></i>Priorité sur les créneaux</li>
            </ul>
        </div>
    </div>

    <style>
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fade-in-up 0.6s ease-out both; }
    </style>
</x-app-layout>