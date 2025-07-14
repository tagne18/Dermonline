<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prise de Rendez-vous - Consultation Dermatologie</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 1.2em;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            padding: 40px;
        }

        .form-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .info-section {
            background: linear-gradient(135deg, #e3f2fd 0%, #f1f8e9 100%);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .section-title {
            font-size: 1.5em;
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s, transform 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
        }

        .form-group textarea {
            height: 100px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            padding: 8px;
            background: white;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .checkbox-item:hover {
            background: #f0f8ff;
        }

        .checkbox-item input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }

        .btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 20px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(52, 152, 219, 0.4);
        }

        .info-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .info-card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .info-card ul {
            list-style: none;
            padding-left: 0;
        }

        .info-card li {
            padding: 5px 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .info-card li:last-child {
            border-bottom: none;
        }

        .info-card li:before {
            content: "✓";
            color: #27ae60;
            font-weight: bold;
            margin-right: 10px;
        }

        .price-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .price-header {
            background: #3498db;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: 600;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 20px;
            border-bottom: 1px solid #ecf0f1;
        }

        .price-row:last-child {
            border-bottom: none;
        }

        .alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #f39c12;
        }

        .alert-important {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-left: 4px solid #e74c3c;
        }

        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .checkbox-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏥 Consultation Dermatologie</h1>
            <p>Prenez rendez-vous pour votre consultation dermatologique</p>
        </div>

        <div class="content">
            <!-- Section Formulaire -->
            <div class="form-section">
                <h2 class="section-title">Formulaire de Rendez-vous</h2>
                
                <form id="appointmentForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">Prénom *</label>
                            <input type="text" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group">
                            <label for="lastName">Nom *</label>
                            <input type="text" id="lastName" name="lastName" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="birthDate">Date de naissance *</label>
                            <input type="date" id="birthDate" name="birthDate" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Sexe *</label>
                            <select id="gender" name="gender" required>
                                <option value="">Sélectionnez</option>
                                <option value="homme">Homme</option>
                                <option value="femme">Femme</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Téléphone *</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Adresse complète</label>
                        <input type="text" id="address" name="address">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="appointmentDate">Date souhaitée *</label>
                            <input type="date" id="appointmentDate" name="appointmentDate" required>
                        </div>
                        <div class="form-group">
                            <label for="appointmentTime">Heure souhaitée *</label>
                            <select id="appointmentTime" name="appointmentTime" required>
                                <option value="">Sélectionnez</option>
                                <option value="08:00">08:00</option>
                                <option value="08:30">08:30</option>
                                <option value="09:00">09:00</option>
                                <option value="09:30">09:30</option>
                                <option value="10:00">10:00</option>
                                <option value="10:30">10:30</option>
                                <option value="11:00">11:00</option>
                                <option value="11:30">11:30</option>
                                <option value="14:00">14:00</option>
                                <option value="14:30">14:30</option>
                                <option value="15:00">15:00</option>
                                <option value="15:30">15:30</option>
                                <option value="16:00">16:00</option>
                                <option value="16:30">16:30</option>
                                <option value="17:00">17:00</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="consultationType">Type de consultation *</label>
                        <select id="consultationType" name="consultationType" required>
                            <option value="">Sélectionnez</option>
                            <option value="premiere">Première consultation</option>
                            <option value="controle">Consultation de contrôle</option>
                            <option value="urgence">Consultation d'urgence</option>
                            <option value="esthetique">Consultation esthétique</option>
                            <option value="chirurgie">Pré-consultation chirurgicale</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="reason">Motif de consultation *</label>
                        <textarea id="reason" name="reason" placeholder="Décrivez brièvement le motif de votre consultation..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Symptômes présents :</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="acne" name="symptoms[]" value="acné">
                                <label for="acne">Acné</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="eczema" name="symptoms[]" value="eczéma">
                                <label for="eczema">Eczéma</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="psoriasis" name="symptoms[]" value="psoriasis">
                                <label for="psoriasis">Psoriasis</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="grain" name="symptoms[]" value="grain de beauté">
                                <label for="grain">Grain de beauté</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="rash" name="symptoms[]" value="éruption cutanée">
                                <label for="rash">Éruption cutanée</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="itching" name="symptoms[]" value="démangeaisons">
                                <label for="itching">Démangeaisons</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="allergies">Allergies connues</label>
                        <textarea id="allergies" name="allergies" placeholder="Mentionnez toutes vos allergies (médicaments, aliments, etc.)"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="medications">Médicaments actuels</label>
                        <textarea id="medications" name="medications" placeholder="Listez tous les médicaments que vous prenez actuellement"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="insurance">Assurance maladie</label>
                        <input type="text" id="insurance" name="insurance" placeholder="Nom de votre assurance">
                    </div>

                    <button type="submit" class="btn">Prendre Rendez-vous</button>
                </form>
            </div>

            <!-- Section Informations -->
            <div class="info-section">
                <h2 class="section-title">Informations Importantes</h2>

                <div class="info-card">
                    <h3>📋 Avant votre rendez-vous</h3>
                    <ul>
                        <li>Préparez la liste de vos médicaments</li>
                        <li>Notez vos antécédents familiaux</li>
                        <li>Photographiez les lésions si nécessaire</li>
                        <li>Évitez le maquillage sur les zones concernées</li>
                        <li>Portez des vêtements faciles à enlever</li>
                    </ul>
                </div>

                <div class="info-card">
                    <h3>🏥 Documents à apporter</h3>
                    <ul>
                        <li>Carte d'identité ou passeport</li>
                        <li>Carte vitale et mutuelle</li>
                        <li>Ordonnances en cours</li>
                        <li>Résultats d'examens précédents</li>
                        <li>Carnet de vaccination</li>
                    </ul>
                </div>

                <div class="alert">
                    <strong>⚠️ Important :</strong> Arrivez 15 minutes avant votre rendez-vous pour les formalités administratives.
                </div>

                <div class="price-table">
                    <div class="price-header">Tarifs Consultations</div>
                    <div class="price-row">
                        <span>Consultation standard</span>
                        <strong>80 €</strong>
                    </div>
                    <div class="price-row">
                        <span>Première consultation</span>
                        <strong>90 €</strong>
                    </div>
                    <div class="price-row">
                        <span>Consultation d'urgence</span>
                        <strong>120 €</strong>
                    </div>
                    <div class="price-row">
                        <span>Consultation esthétique</span>
                        <strong>100 €</strong>
                    </div>
                </div>

                <div class="alert alert-important">
                    <strong>🚨 Annulation :</strong> Merci de prévenir 24h à l'avance en cas d'annulation pour éviter toute facturation.
                </div>

                <div class="info-card">
                    <h3>📞 Contact</h3>
                    <ul>
                        <li>Téléphone : 01 23 45 67 89</li>
                        <li>Email : contact@dermato.fr</li>
                        <li>Adresse : 123 Avenue de la Santé, 75000 Paris</li>
                        <li>Horaires : Lun-Ven 8h-18h, Sam 8h-13h</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Définir la date minimale à aujourd'hui
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('appointmentDate').setAttribute('min', today);

        // Gestion du formulaire
        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Animation de soumission
            const btn = this.querySelector('.btn');
            const originalText = btn.textContent;
            btn.textContent = 'Traitement en cours...';
            btn.disabled = true;
            
            // Simulation d'envoi
            setTimeout(() => {
                alert('Votre demande de rendez-vous a été envoyée avec succès ! Vous recevrez une confirmation par email dans les plus brefs délais.');
                btn.textContent = originalText;
                btn.disabled = false;
                this.reset();
            }, 2000);
        });

        // Validation en temps réel
        const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.borderColor = '#e74c3c';
                } else {
                    this.style.borderColor = '#27ae60';
                }
            });
        });
    </script>
</body>
</html>