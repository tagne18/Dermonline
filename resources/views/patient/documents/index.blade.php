<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 ">
            {{ __('Vos Documents Médicaux') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white   sm:rounded-lg p-6 md:p-8">

                {{-- Section d'en-tête de la page --}}
                <div class="mb-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Votre Espace Documentaire Sécurisé</h3>
                    <p class="text-gray-600">Consultez, téléchargez et gérez vos ordonnances, résultats d'analyse et autres documents médicaux en toute confidentialité.</p>
                </div>

                {{--
                    NOTE POUR LE DÉVELOPPEMENT FUTUR :
                    Pour faire fonctionner cette section, vous devrez :
                    1. Récupérer les documents de l'utilisateur dans le `DocumentController`.
                       Exemple: $documents = auth()->user()->documents;
                    2. Passer la variable `$documents` à cette vue.
                    3. Décommenter la boucle `@forelse` ci-dessous pour afficher les documents.
                --}}

                {{-- Grille des documents --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    {{-- Exemple de document - À utiliser dans la boucle --}}
                    {{-- @forelse ($documents as $document) --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="flex items-center mb-3">
                            <span class="p-2 bg-blue-100 text-blue-600 rounded-full mr-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Ordonnance du 15/07/2024</h4>
                                <p class="text-sm text-gray-500">Délivrée par Dr. Dupont</p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm mb-4">
                            Concerne le traitement pour l'eczéma. Valable jusqu'au 15/01/2025.
                        </p>
                        <div class="flex justify-end gap-2">
                            <a href="#" class="inline-block px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg shadow-sm hover:bg-indigo-700 transition">
                                Télécharger
                            </a>
                            <a href="#" class="inline-block px-4 py-2 bg-gray-200 text-gray-800 font-semibold text-sm rounded-lg hover:bg-gray-300 transition">
                                Détails
                            </a>
                        </div>
                    </div>
                    {{-- @empty --}}
                    {{-- Message si aucun document n'est disponible --}}
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-10 px-6 bg-gray-50 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun document disponible pour le moment</h3>
                        <p class="mt-1 text-sm text-gray-500">Vos documents médicaux apparaîtront ici dès qu'ils seront ajoutés par votre médecin.</p>
                        <div class="mt-6">
                            <a href="{{ route('patient.appointments.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Gérer mes rendez-vous
                            </a>
                        </div>
                    </div>
                    {{-- @endforelse --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
