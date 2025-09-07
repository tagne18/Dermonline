<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <i class="bi bi-calendar-check me-2"></i>{{ __('Prendre un rendez-vous') }}
        </h2>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="bi bi-clock me-1"></i>Consultation en ligne
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Informations importantes -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="bi bi-info-circle text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Consultation dermatologique en ligne</h3>
                        <div class="grid md:grid-cols-2 gap-4 text-sm text-blue-800">
                            <div class="flex items-center">
                                <i class="bi bi-camera-video me-2"></i>
                                <span>Consultation par vidéo sécurisée</span>
                            </div>
                            <div class="flex items-center">
                                <i class="bi bi-clock me-2"></i>
                                <span>Durée : 15-30 minutes</span>
                            </div>
                            <div class="flex items-center">
                                <i class="bi bi-shield-check me-2"></i>
                                <span>Confidentialité garantie</span>
                            </div>
                            <div class="flex items-center">
                                <i class="bi bi-credit-card me-2"></i>
                                <span>Paiement sécurisé en ligne</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Formulaire principal -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-xl border border-gray-100">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                            <h3 class="text-lg font-semibold text-white">
                                <i class="bi bi-calendar-plus me-2"></i>Nouveau rendez-vous
                </h3>
                            <p class="text-indigo-100 text-sm mt-1">Remplissez les informations ci-dessous</p>
                        </div>

                        <div class="p-6">
                <form method="POST" action="{{ route('patient.appointments.store') }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                                <!-- Informations personnelles -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                        <i class="bi bi-person-circle me-2 text-indigo-600"></i>
                                        Informations personnelles
                                    </h4>
                                    
                                    <div class="grid md:grid-cols-2 gap-4">
                    <div>
                                            <label for="patient_name" class="block text-sm font-medium text-danger mb-2">
                                                Nom complet *
                        </label>
                        <input type="text" name="patient_name" id="patient_name" required
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                   placeholder="Votre nom complet"
                                                   value="{{ auth()->user()->name ?? '' }}">
                                        </div>
                                        
                                        <div>
                                            <label for="patient_phone" class="block text-sm font-medium text-danger mb-2">
                                                Téléphone *
                                            </label>
                                            <input type="tel" name="patient_phone" id="patient_phone" required
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                   placeholder="+237 XXX XXX XXX"
                                                   value="{{ auth()->user()->phone ??  ''  }}">
                                                   
                                        </div>
                                    </div>
                                </div>

                                <!-- Choix du dermatologue -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                        <i class="bi bi-person-badge me-2 text-indigo-600"></i>
                                        Sélection du dermatologue
                                    </h4>
                                    
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="specialty" class="block text-sm font-medium text-danger mb-2">
                                                Spécialité *
                                            </label>
                                            <select name="specialty" id="specialty" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                                <option value="" disabled selected>Choisir une spécialité</option>
                                                @foreach($specialites as $specialite)
                                                    <option value="{{ $specialite }}">{{ $specialite }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label for="doctor" class="block text-sm font-medium text-danger mb-2">
                                                Dermatologue *
                                            </label>
                                            <select name="doctor" id="doctor" required
                                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                                <option value="" disabled selected>Sélectionnez un dermatologue</option>
                                                @foreach($medecins as $medecin)
                                                    <option value="{{ $medecin->id }}" data-specialite="{{ $medecin->specialite ?? 'Dermatologie' }}">
                                                        Dr. {{ $medecin->name }} - {{ $medecin->specialite ?? 'Dermatologie' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date et heure -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                        <i class="bi bi-calendar-event me-2 text-indigo-600"></i>
                                        Date et heure de consultation
                                    </h4>
                                    
                                    <div class="grid md:grid-cols-2 gap-4">
                    <div>
                                            <label for="appointment_date" class="block text-sm font-medium text-danger mb-2">
                                                Date souhaitée *
                        </label>
                        <input type="date" name="appointment_date" id="appointment_date" required
                               min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                        </div>
                                        
                                        <div>
                                            <label for="appointment_time" class="block text-sm font-medium text-danger mb-2">
                                                Heure souhaitée *
                                            </label>
                                            <div class="grid grid-cols-2 gap-2">
                                                <select name="appointment_time" id="appointment_time" required
                                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                                    <option value="" disabled selected>Heure prédéfinie</option>
                                                    <option value="08:00">08:00</option>
                                                    <option value="09:00">09:00</option>
                                                    <option value="10:00">10:00</option>
                                                    <option value="11:00">11:00</option>
                                                    <option value="14:00">14:00</option>
                                                    <option value="15:00">15:00</option>
                                                    <option value="16:00">16:00</option>
                                                    <option value="17:00">17:00</option>
                                                    <option value="custom">Heure personnalisée</option>
                                                </select>
                                                <input type="time" name="custom_time" id="custom_time" 
                                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors hidden"
                                                       placeholder="Heure personnalisée">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Motif de consultation -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                        <i class="bi bi-chat-text me-2 text-indigo-600"></i>
                                        Motif de consultation
                                    </h4>
                                    
                                    <div>
                                        <label for="consultation_reason" class="block text-sm font-medium text-danger mb-2">
                                            Raison principale *
                                        </label>
                                        <select name="consultation_reason" id="consultation_reason" required
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors mb-4">
                                            <option value="" disabled selected>Sélectionner le motif</option>
                                            <option value="acne">Problème d'acné</option>
                                            <option value="rash">Éruption cutanée</option>
                                            <option value="mole">Grain de beauté suspect</option>
                                            <option value="itching">Démangeaisons</option>
                                            <option value="hair_loss">Perte de cheveux</option>
                                            <option value="nail_problem">Problème d'ongles</option>
                                            <option value="follow_up">Suivi de traitement</option>
                                            <option value="other">Autre</option>
                                        </select>
                                        
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Description détaillée
                                        </label>
                                        <textarea name="description" id="description" rows="4"
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                                  placeholder="Décrivez vos symptômes, leur durée, les facteurs aggravants..."></textarea>
                                    </div>
                                </div>

                                <!-- Photos (optionnel) -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                        <i class="bi bi-camera me-2 text-indigo-600"></i>
                                        Photos des lésions (optionnel)
                                    </h4>
                                    
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                        <i class="bi bi-cloud-upload text-3xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-600 mb-2">Glissez-déposez vos photos ici ou cliquez pour sélectionner</p>
                                        <input type="file" name="photos[]" multiple accept="image/*" class="hidden" id="photo-upload">
                                        <label for="photo-upload" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 cursor-pointer transition-colors">
                                            <i class="bi bi-plus me-2"></i>Sélectionner des photos
                                        </label>
                                       
                                    </div>
                                </div>

                                <!-- Type de rendez-vous -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <h4 class="font-medium text-gray-900 mb-4 flex items-center">
                                        <i class="bi bi-globe me-2 text-indigo-600"></i>
                                        Type de rendez-vous
                                    </h4>
                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <input type="radio" id="type_en_ligne" name="type" value="en_ligne" required>
                                            <label for="type_en_ligne" class="ms-2">En ligne (visio)</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="type_presentiel" name="type" value="presentiel" required>
                                            <label for="type_presentiel" class="ms-2">Présentiel (cabinet)</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Boutons d'action -->
                                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                                    <a href="{{ route('patient.dashboard') }}" 
                                       class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-primary transition-colors">
                                        <i class="bi bi-arrow-left me-2"></i>Retour
                                    </a>
                                    
                                    <button type="submit"
                                            class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 bg-success text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-300 transition-all">
                                        <i class="bi bi-calendar-check me-2"></i>Confirmer le rendez-vous
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar avec informations -->
                <div class="lg:col-span-1">
                    <!-- Informations sur la consultation -->
                    <div class="bg-white shadow-xl rounded-xl border border-gray-100 mb-6">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-4 py-3 rounded-t-xl">
                            <h3 class="text-white font-semibold flex items-center">
                                <i class="bi bi-info-circle me-2"></i>Informations importantes
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <i class="bi bi-clock text-green-600 mt-1 me-3"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Durée</h4>
                                        <p class="text-sm text-gray-600">15-30 minutes selon la complexité</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <i class="bi bi-currency-dollar text-green-600 mt-1 me-3"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Tarif</h4>
                                        <p class="text-sm text-gray-600">15 000 - 25 000 FCFA</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <i class="bi bi-shield-check text-green-600 mt-1 me-3"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Confidentialité</h4>
                                        <p class="text-sm text-gray-600">Données médicales protégées</p>
                                    </div>
                    </div>

                                <div class="flex items-start">
                                    <i class="bi bi-camera-video text-green-600 mt-1 me-3"></i>
                    <div>
                                        <h4 class="font-medium text-gray-900">Plateforme</h4>
                                        <p class="text-sm text-gray-600">Zoom sécurisé ou Teams</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conseils pour la consultation -->
                    <div class="bg-white shadow-xl rounded-xl border border-gray-100 mb-6">
                        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-4 py-3 rounded-t-xl">
                            <h3 class="text-white font-semibold flex items-center">
                                <i class="bi bi-lightbulb me-2"></i>Conseils
                            </h3>
                        </div>
                        <div class="p-4">
                            <ul class="space-y-3 text-sm text-gray-700">
                                <li class="flex items-start">
                                    <i class="bi bi-check-circle text-blue-600 mt-0.5 me-2"></i>
                                    <span>Préparez un éclairage suffisant</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="bi bi-check-circle text-blue-600 mt-0.5 me-2"></i>
                                    <span>Assurez-vous d'une connexion stable</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="bi bi-check-circle text-blue-600 mt-0.5 me-2"></i>
                                    <span>Préparez vos antécédents médicaux</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="bi bi-check-circle text-blue-600 mt-0.5 me-2"></i>
                                    <span>Notez vos questions à l'avance</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Contact d'urgence -->
                    <div class="bg-white shadow-xl rounded-xl border border-gray-100">
                        <div class="bg-gradient-to-r from-red-600 to-pink-600 px-4 py-3 rounded-t-xl">
                            <h3 class="text-white font-semibold flex items-center">
                                <i class="bi bi-exclamation-triangle me-2"></i>Urgence
                            </h3>
                        </div>
                        <div class="p-4">
                            <p class="text-sm text-gray-700 mb-3">
                                En cas d'urgence dermatologique :
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm">
                                    <i class="bi bi-telephone text-red-600 me-2"></i>
                                    <span class="text-gray-700">+237 686913777</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="bi bi-envelope text-red-600 me-2"></i>
                                    <span class="text-gray-700">urgence@dermonline.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center transition-all duration-300 bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div class="relative w-full max-w-md px-6 py-8 mx-4 transition-all duration-300 transform -translate-y-10 bg-white rounded-xl shadow-2xl sm:mx-6 md:mx-0">
                <!-- Close button -->
                <button onclick="closeModal()" class="absolute top-4 right-4 p-1 rounded-full hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500" aria-label="Fermer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <!-- Success icon -->
                <div class="flex justify-center mb-6">
                    <div class="flex items-center justify-center w-20 h-20 rounded-full bg-green-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="text-center">
                    <h3 id="modal-title" class="mb-3 text-2xl font-bold text-gray-900">Rendez-vous confirmé !</h3>
                    <p class="mb-6 text-gray-600">{{ session('success') }}</p>
                    
                    <div class="mt-8">
                        <button onclick="closeModal()" class="w-full px-6 py-3 text-sm font-medium text-white transition-colors duration-200 transform bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Parfait, merci !
                        </button>
                        
                        <p class="mt-3 text-xs text-gray-500">
                            Un email de confirmation vous a été envoyé
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Show modal with animation
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('successModal');
                setTimeout(() => {
                    modal.classList.remove('opacity-0', 'pointer-events-none');
                    modal.classList.add('opacity-100', 'pointer-events-auto');
                    modal.querySelector('div').classList.remove('-translate-y-10');
                    modal.querySelector('div').classList.add('translate-y-0');
                }, 100);
                
                // Trap focus inside modal
                const focusableElements = 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])';
                const focusableContent = modal.querySelectorAll(focusableElements);
                const firstFocusableElement = focusableContent[0];
                const lastFocusableElement = focusableContent[focusableContent.length - 1];
                
                firstFocusableElement.focus();
                
                modal.addEventListener('keydown', function(e) {
                    let isTabPressed = e.key === 'Tab' || e.keyCode === 9;
                    
                    if (!isTabPressed) {
                        if (e.key === 'Escape') {
                            closeModal();
                        }
                        return;
                    }
                    
                    if (e.shiftKey) {
                        if (document.activeElement === firstFocusableElement) {
                            lastFocusableElement.focus();
                            e.preventDefault();
                        }
                    } else {
                        if (document.activeElement === lastFocusableElement) {
                            firstFocusableElement.focus();
                            e.preventDefault();
                        }
                    }
                });
            });
            
            function closeModal() {
                const modal = document.getElementById('successModal');
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.querySelector('div').classList.remove('translate-y-0');
                modal.querySelector('div').classList.add('-translate-y-10');
                
                // Remove modal from DOM after animation
                setTimeout(() => {
                    modal.remove();
                }, 300);
            }
            
            // Close when clicking outside modal content
            document.getElementById('successModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        </script>
    @endif

    <script>
        // Gestion du drag & drop pour les photos
        const dropZone = document.querySelector('.border-dashed');
        const fileInput = document.getElementById('photo-upload');

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
            const files = e.dataTransfer.files;
            fileInput.files = files;
        });

        // Validation en temps réel
        const form = document.querySelector('form');
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            field.addEventListener('blur', () => {
                if (!field.value) {
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const specialtySelect = document.getElementById('specialty');
            const doctorSelect = document.getElementById('doctor');
            const allOptions = Array.from(doctorSelect.options);
            specialtySelect.addEventListener('change', function() {
                const value = this.value;
                doctorSelect.innerHTML = '<option value="" disabled selected>Sélectionnez un dermatologue</option>';
                allOptions.forEach(option => {
                    if (option.dataset && option.dataset.specialite === value) {
                        doctorSelect.appendChild(option.cloneNode(true));
                    }
                });
            });
        });

        // Gestion de l'heure personnalisée
        document.getElementById('appointment_time').addEventListener('change', function() {
            const customTimeInput = document.getElementById('custom_time');
            if (this.value === 'custom') {
                customTimeInput.classList.remove('hidden');
                customTimeInput.required = true;
            } else {
                customTimeInput.classList.add('hidden');
                customTimeInput.required = false;
            }
        });

        // Filtrage des médecins par spécialité
        document.getElementById('specialty').addEventListener('change', function() {
            const selectedSpecialty = this.value;
            const doctorSelect = document.getElementById('doctor');
            const options = doctorSelect.querySelectorAll('option');
            
            options.forEach(option => {
                if (option.value === '') return; // Garder l'option par défaut
                
                if (selectedSpecialty === '' || option.dataset.specialite === selectedSpecialty) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Réinitialiser la sélection du médecin
            doctorSelect.value = '';
        });

        // Validation du formulaire
        document.querySelector('form').addEventListener('submit', function(e) {
            const appointmentTime = document.getElementById('appointment_time');
            const customTime = document.getElementById('custom_time');
            
            if (appointmentTime.value === 'custom' && !customTime.value) {
                e.preventDefault();
                alert('Veuillez sélectionner une heure personnalisée.');
                return false;
            }
        });
    </script>
</x-app-layout>
