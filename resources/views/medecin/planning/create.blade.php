@extends('layouts.medecin')

@section('title', 'Créer un planning')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .form-header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
        }
        
        .form-body {
            padding: 0 2rem 2rem;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #4a5568;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
        }
        
        .time-input-group {
            display: flex;
            gap: 1rem;
        }
        
        .time-input {
            flex: 1;
        }
        
        .type-consultation {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .type-option {
            flex: 1;
            text-align: center;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .type-option:hover {
            border-color: #cbd5e0;
            background-color: #f8fafc;
        }
        
        .type-option.active {
            border-color: #4e73df;
            background-color: rgba(78, 115, 223, 0.05);
        }
        
        .type-option i {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            color: #4e73df;
        }
        
        .type-option.active i {
            color: #224abe;
        }
        
        .type-option .form-check-input {
            display: none;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }
        
        .preview-card {
            border: 1px dashed #cbd5e0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
            background-color: #f8fafc;
        }
        
        .preview-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #4a5568;
        }
        
        .preview-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        
        .preview-item i {
            width: 24px;
            color: #718096;
            margin-right: 0.75rem;
        }
        
        .preview-item span {
            color: #4a5568;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="h2 mb-1 text-gray-800">
                <i class="fas fa-calendar-plus me-2"></i>Créer un planning
            </h1>
            <p class="mb-0 text-muted">Planifiez un nouveau créneau de consultation</p>
        </div>
        <a href="{{ route('medecin.planning.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
        </a>
    </div>

    <div class="form-container">
        <form action="{{ route('medecin.planning.store') }}" method="POST" id="planningForm">
            @csrf
            
            <div class="form-card">
                <div class="form-header">
                    <h2 class="h4 mb-0">Informations du planning</h2>
                </div>
                
                <div class="form-body">
                    <!-- Titre -->
                    <div class="mb-4">
                        <label for="titre" class="form-label">Titre du créneau <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                               id="titre" name="titre" value="{{ old('titre') }}" required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Ex: Consultation du matin, Urgences, etc.</small>
                    </div>
                    
                    <!-- Date de consultation -->
                    <div class="mb-4">
                        <label for="date_consultation" class="form-label">Date de consultation <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            <input type="date" 
                                   class="form-control @error('date_consultation') is-invalid @enderror" 
                                   id="date_consultation" 
                                   name="date_consultation"
                                   value="{{ old('date_consultation') }}" 
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('date_consultation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="form-text text-muted">Sélectionnez une date à partir d'aujourd'hui</small>
                    </div>
                    
                    <!-- Plage horaire -->
                    <div class="mb-4">
                        <label class="form-label">Plage horaire <span class="text-danger">*</span></label>
                        <div class="time-input-group">
                            <div class="time-input">
                                <label for="heure_debut" class="form-label">Heure de début</label>
                                <input type="time" class="form-control @error('heure_debut') is-invalid @enderror" 
                                       id="heure_debut" name="heure_debut" value="{{ old('heure_debut', '09:00') }}" required>
                                @error('heure_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="time-input">
                                <label for="heure_fin" class="form-label">Heure de fin</label>
                                <input type="time" class="form-control @error('heure_fin') is-invalid @enderror" 
                                       id="heure_fin" name="heure_fin" value="{{ old('heure_fin', '17:00') }}" required>
                                @error('heure_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Durée de consultation -->
                    <div class="mb-4">
                        <label for="duree_consultation" class="form-label">Durée de la consultation (en minutes) <span class="text-danger">*</span></label>
                        <select class="form-select @error('duree_consultation') is-invalid @enderror" 
                                id="duree_consultation" name="duree_consultation" required>
                            <option value="15" {{ old('duree_consultation', '30') == 15 ? 'selected' : '' }}>15 minutes</option>
                            <option value="30" {{ old('duree_consultation', '30') == 30 ? 'selected' : '' }}>30 minutes</option>
                            <option value="45" {{ old('duree_consultation', '30') == 45 ? 'selected' : '' }}>45 minutes</option>
                            <option value="60" {{ old('duree_consultation', '30') == 60 ? 'selected' : '' }}>1 heure</option>
                            <option value="90" {{ old('duree_consultation', '30') == 90 ? 'selected' : '' }}>1h30</option>
                            <option value="120" {{ old('duree_consultation', '30') == 120 ? 'selected' : '' }}>2 heures</option>
                        </select>
                        @error('duree_consultation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Type de consultation -->
                    <div class="mb-4">
                        <label class="form-label">Type de consultation <span class="text-danger">*</span></label>
                        <div class="type-consultation">
                            <label class="type-option {{ old('type_consultation', 'presentiel') == 'presentiel' ? 'active' : '' }}">
                                <input type="radio" name="type_consultation" value="presentiel" 
                                       class="form-check-input" {{ old('type_consultation', 'presentiel') == 'presentiel' ? 'checked' : '' }} required>
                                <i class="fas fa-building"></i>
                                <div>Présentiel</div>
                            </label>
                            
                            <label class="type-option {{ old('type_consultation') == 'en_ligne' ? 'active' : '' }}">
                                <input type="radio" name="type_consultation" value="en_ligne" 
                                       class="form-check-input" {{ old('type_consultation') == 'en_ligne' ? 'checked' : '' }}>
                                <i class="fas fa-video"></i>
                                <div>En ligne</div>
                            </label>
                            
                            <label class="type-option {{ old('type_consultation') == 'hybride' ? 'active' : '' }}">
                                <input type="radio" name="type_consultation" value="hybride" 
                                       class="form-check-input" {{ old('type_consultation') == 'hybride' ? 'checked' : '' }}>
                                <i class="fas fa-exchange-alt"></i>
                                <div>Hybride</div>
                            </label>
                        </div>
                        @error('type_consultation')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Prix -->
                    <div class="mb-4">
                        <label for="prix" class="form-label">Prix de la consultation (FCFA) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('prix') is-invalid @enderror" 
                                   id="prix" name="prix" value="{{ old('prix', '5000') }}" min="0" step="100" required>
                            <span class="input-group-text">FCFA</span>
                            @error('prix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label">Description (optionnel)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Vous pouvez ajouter des détails sur ce créneau de consultation.</small>
                    </div>
                    
                    <!-- Aperçu -->
                    <div class="preview-card">
                        <div class="preview-title">
                            <i class="far fa-eye me-2"></i>Aperçu du planning
                        </div>
                        <div class="preview-item">
                            <i class="far fa-calendar-alt"></i>
                            <span id="preview-date">--/--/----</span>
                        </div>
                        <div class="preview-item">
                            <i class="far fa-clock"></i>
                            <span id="preview-time">--:-- - --:--</span>
                            <span class="ms-2" id="preview-duree">(-- min)</span>
                        </div>
                        <div class="preview-item">
                            <i id="preview-type-icon" class="fas fa-question"></i>
                            <span id="preview-type">--</span>
                        </div>
                        <div class="preview-item">
                            <i class="fas fa-tag"></i>
                            <span id="preview-prix">0 FCFA</span>
                        </div>
                    </div>
                    
                    <!-- Bouton de soumission -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-save me-2"></i>Enregistrer le planning
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration de Flatpickr
        const config = {
            locale: 'fr',
            dateFormat: 'Y-m-d',  // Format ISO pour la validation
            altFormat: 'l j F Y', // Format d'affichage complet en français
            altInput: true,
            allowInput: false,    // Désactiver la saisie manuelle
            clickOpens: true,     // Ouvrir au clic sur le champ
            minDate: 'today',
            disable: [
                function(date) {
                    // Désactiver les week-ends (0 = dimanche, 6 = samedi)
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ],
            // Désactiver le mode mobile
            disableMobile: true,
            // Options d'affichage
            nextArrow: '<i class="fas fa-chevron-right"></i>',
            prevArrow: '<i class="fas fa-chevron-left"></i>',
            // Configuration de la locale française
            locale: {
                firstDayOfWeek: 1, // Commencer la semaine par lundi
                weekdays: {
                    shorthand: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                    longhand: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']
                },
                months: {
                    shorthand: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                    longhand: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
                },
                rangeSeparator: ' au ',
                weekAbbreviation: 'Sem',
                scrollTitle: 'Défiler pour augmenter',
                toggleTitle: 'Cliquer pour basculer'
            },
            // Désactiver la saisie clavier
            disableMobile: 'true',
            // Après la sélection
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    updatePreview();
                }
            }
            onClose: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    // Mettre à jour le champ caché avec la date au format Y-m-d
                    document.getElementById('date_consultation').value = instance.formatDate(selectedDates[0], 'Y-m-d');
                    // Mettre à jour l'affichage
                    instance.altInput.value = instance.formatDate(selectedDates[0], instance.config.altFormat);
                }
            },
            // Forcer le format de date
            parseDate: (dateStr, format) => {
                // Convertir la date du format jj/mm/aaaa en objet Date
                const [d, m, y] = dateStr.split('/').map(Number);
                return new Date(y, m - 1, d);
            },
            // Formater la date pour l'affichage
            formatDate: (date, format, locale) => {
                // Retourner la date au format jj/mm/aaaa
                return date.toLocaleDateString('fr-FR');
            },
            // Valider la date sélectionnée
            onChange: function(selectedDates, dateStr, instance) {
                const input = instance.altInput;
                if (input) {
                    // Mettre à jour la valeur du champ caché avec le bon format
                    instance.input.value = instance.formatDate(selectedDates[0], 'Y-m-d');
                    // Mettre à jour l'affichage
                    input.value = instance.formatDate(selectedDates[0], instance.config.altFormat);
                }
                updatePreview();
            }
            // Options d'affichage
            nextArrow: '<i class="fas fa-chevron-right"></i>',
            prevArrow: '<i class="fas fa-chevron-left"></i>',
            // Configuration de la locale française
            locale: {
                firstDayOfWeek: 1, // Commencer la semaine par lundi
                weekdays: {
                    shorthand: ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'],
                    longhand: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']
                },
                months: {
                    shorthand: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
                    longhand: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']
                },
                rangeSeparator: ' au ',
                weekAbbreviation: 'Sem',
                scrollTitle: 'Défiler pour augmenter',
                toggleTitle: 'Cliquer pour basculer'
            },
            // Désactiver la validation du navigateur
        };
        
        // Initialiser le sélecteur de date
        const datePicker = flatpickr("#date_consultation", config);
        
        // Gérer le clic sur l'icône du calendrier
        document.querySelector('.input-group-text').addEventListener('click', function(e) {
            e.preventDefault();
            datePicker.open();
        });
        
        // Désactiver la saisie clavier
        document.getElementById('date_consultation').addEventListener('keydown', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Initialiser la date si elle existe déjà
        @if(old('date_consultation'))
            try {
                const [year, month, day] = '{{ old('date_consultation') }}'.split('-');
                const initialDate = new Date(year, month - 1, day);
                if (!isNaN(initialDate.getTime())) {
                    datePicker.setDate(initialDate);
                }
            } catch (e) {
                console.error('Erreur lors de l\'initialisation de la date:', e);
            }
        @endif
        });
        
        // Mettre à jour l'aperçu
        function updatePreview() {
            // Date
            const dateInput = document.getElementById('date_consultation');
            const previewDate = document.getElementById('preview-date');
            
            if (dateInput && dateInput.value) {
                const date = new Date(dateInput.value);
                
                if (!isNaN(date.getTime())) { // Vérifier si la date est valide
                    const options = { 
                        weekday: 'long', 
                        day: 'numeric', 
                        month: 'long', 
                        year: 'numeric',
                        timeZone: 'UTC' // Éviter les décalages de fuseau horaire
                    };
                    const dateFormatted = date.toLocaleDateString('fr-FR', options);
                    previewDate.textContent = dateFormatted.charAt(0).toUpperCase() + dateFormatted.slice(1);
                    return;
                }
            }
            previewDate.textContent = 'Non défini';
            
            // Heures
            const heureDebut = document.getElementById('heure_debut').value || '--:--';
            const heureFin = document.getElementById('heure_fin').value || '--:--';
            document.getElementById('preview-time').textContent = `${heureDebut} - ${heureFin}`;
            
            // Durée
            const duree = document.getElementById('duree_consultation');
            if (duree) {
                const dureeText = duree.options[duree.selectedIndex].text;
                document.getElementById('preview-duree').textContent = `(${dureeText})`;
            }
            
            // Type de consultation
            const typeConsultation = document.querySelector('input[name="type_consultation"]:checked');
            if (typeConsultation) {
                const typeText = typeConsultation.parentElement.textContent.trim();
                const typeIcon = typeConsultation.value === 'presentiel' ? 'building' : 
                               (typeConsultation.value === 'en_ligne' ? 'video' : 'exchange-alt');
                
                document.getElementById('preview-type').textContent = typeText;
                document.getElementById('preview-type-icon').className = `fas fa-${typeIcon}`;
            }
            
            // Prix
            const prix = document.getElementById('prix').value || '0';
            document.getElementById('preview-prix').textContent = `${parseInt(prix).toLocaleString('fr-FR')} FCFA`;
        }
        
        // Écouter les changements sur les champs
        const formInputs = document.querySelectorAll('#planningForm input, #planningForm select, #planningForm textarea');
        formInputs.forEach(input => {
            input.addEventListener('change', updatePreview);
            input.addEventListener('keyup', updatePreview);
        });
        
        // Gérer le style des options de type de consultation
        const typeOptions = document.querySelectorAll('.type-option');
        typeOptions.forEach(option => {
            option.addEventListener('click', function() {
                typeOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                updatePreview();
            });
        });
        
        // Calculer automatiquement la durée
        const heureDebut = document.getElementById('heure_debut');
        const heureFin = document.getElementById('heure_fin');
        const dureeConsultation = document.getElementById('duree_consultation');
        
        function calculerDuree() {
            if (heureDebut.value && heureFin.value) {
                const [debutHeures, debutMinutes] = heureDebut.value.split(':').map(Number);
                const [finHeures, finMinutes] = heureFin.value.split(':').map(Number);
                
                let debut = new Date(2000, 0, 1, debutHeures, debutMinutes);
                let fin = new Date(2000, 0, 1, finHeures, finMinutes);
                
                // Si l'heure de fin est avant l'heure de début, on suppose que c'est le lendemain
                if (fin <= debut) {
                    fin.setDate(fin.getDate() + 1);
                }
                
                const diffMinutes = Math.round((fin - debut) / (1000 * 60));
                
                if (diffMinutes > 0) {
                    // Trouver l'option la plus proche
                    const options = Array.from(dureeConsultation.options).map(opt => parseInt(opt.value));
                    const dureeLaPlusProche = options.reduce((prev, curr) => {
                        return (Math.abs(curr - diffMinutes) < Math.abs(prev - diffMinutes) ? curr : prev);
                    });
                    
                    dureeConsultation.value = dureeLaPlusProche;
                }
            }
        }
        
        heureDebut.addEventListener('change', calculerDuree);
        heureFin.addEventListener('change', calculerDuree);
        
        // Mettre à jour l'aperçu au chargement
        updatePreview();
    });
    
    // Validation du formulaire
    document.getElementById('planningForm').addEventListener('submit', function(e) {
        const heureDebut = document.getElementById('heure_debut').value;
        const heureFin = document.getElementById('heure_fin').value;
        
        if (heureDebut && heureFin) {
            const [debutHeures, debutMinutes] = heureDebut.split(':').map(Number);
            const [finHeures, finMinutes] = heureFin.split(':').map(Number);
            
            let debut = new Date(2000, 0, 1, debutHeures, debutMinutes);
            let fin = new Date(2000, 0, 1, finHeures, finMinutes);
            
            // Si l'heure de fin est avant l'heure de début, on suppose que c'est le lendemain
            if (fin <= debut) {
                fin.setDate(fin.getDate() + 1);
            }
            
            const diffMinutes = Math.round((fin - debut) / (1000 * 60));
            
            if (diffMinutes < 15) {
                e.preventDefault();
                alert('La durée minimale d\'un créneau doit être d\'au moins 15 minutes.');
                return false;
            }
        }
        
        return true;
    });
</script>
@endpush
