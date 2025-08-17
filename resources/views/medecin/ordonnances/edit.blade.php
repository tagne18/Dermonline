@extends('layouts.medecin')

@section('content')
<style>
    .edit-prescription-container {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .form-wrapper {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 2.5rem;
        max-width: 800px;
        margin: 0 auto;
        border: 1px solid #e9ecef;
    }
    
    .form-header {
        text-align: center;
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f1f3f4;
        position: relative;
    }
    
    .form-header::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 2px;
        background: linear-gradient(90deg, #ffc107, #fd7e14);
        border-radius: 1px;
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
    }
    
    .form-subtitle {
        color: #6c757d;
        font-size: 1rem;
        margin: 0;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .form-group-modern {
        margin-bottom: 1.8rem;
        position: relative;
    }
    
    .form-label-modern {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.6rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }
    
    .form-control-modern {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.9rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background-color: #fafbfc;
        width: 100%;
    }
    
    .form-control-modern:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.15);
        background-color: white;
        outline: none;
    }
    
    .form-control-modern:hover {
        border-color: #ced4da;
        background-color: white;
    }
    
    .select-modern {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        appearance: none;
    }
    
    .textarea-modern {
        resize: vertical;
        min-height: 120px;
    }
    
    .file-input-container {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .file-input-modern {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    
    .file-input-label {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        padding: 1.2rem;
        border: 2px dashed #ced4da;
        border-radius: 8px;
        background-color: #fafbfc;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .file-input-label:hover {
        border-color: #ffc107;
        background-color: #fff8e1;
        color: #ffc107;
    }
    
    .file-input-label.has-file {
        border-color: #28a745;
        background-color: #f8fff9;
        color: #28a745;
    }
    
    .current-file-card {
        background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
        border: 1px solid #d4edda;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .file-icon-large {
        font-size: 2rem;
        color: #28a745;
    }
    
    .file-details h6 {
        margin: 0;
        color: #155724;
        font-weight: 600;
    }
    
    .file-details p {
        margin: 0.2rem 0 0;
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .view-file-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: auto;
    }
    
    .view-file-btn:hover {
        background: #218838;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }
    
    .file-icon {
        font-size: 1.5rem;
    }
    
    .file-text {
        font-size: 0.9rem;
    }
    
    .file-info {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.5rem;
        text-align: center;
    }
    
    .alert-modern {
        border: none;
        border-radius: 8px;
        padding: 1.2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(220, 53, 69, 0.15);
    }
    
    .alert-danger-modern {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }
    
    .error-list {
        margin: 0;
        padding-left: 1.2rem;
    }
    
    .error-list li {
        margin-bottom: 0.3rem;
        font-weight: 500;
    }
    
    .button-group {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 1px solid #e9ecef;
    }
    
    .btn-modern {
        padding: 0.9rem 2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: 2px solid;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-width: 140px;
    }
    
    .btn-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-modern:hover::before {
        left: 100%;
    }
    
    .btn-warning-modern {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
        border-color: #ffc107;
    }
    
    .btn-warning-modern:hover {
        background: linear-gradient(135deg, #e0a800, #e8590c);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
    }
    
    .btn-secondary-modern {
        background: white;
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-secondary-modern:hover {
        background: #6c757d;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }
    
    .required-mark {
        color: #dc3545;
        margin-left: 0.2rem;
    }
    
    .edit-badge {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        color: #856404;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .edit-prescription-container {
            padding: 1rem 0;
        }
        
        .form-wrapper {
            margin: 0 1rem;
            padding: 1.5rem;
        }
        
        .form-title {
            font-size: 1.6rem;
        }
        
        .button-group {
            flex-direction: column;
            gap: 0.8rem;
        }
        
        .btn-modern {
            width: 100%;
        }
        
        .current-file-card {
            flex-direction: column;
            text-align: center;
        }
        
        .view-file-btn {
            margin-left: 0;
            margin-top: 1rem;
        }
    }
    
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        z-index: 9999;
        justify-content: center;
        align-items: center;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #ffc107;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="edit-prescription-container">
    <div class="form-wrapper">
        <div class="form-header">
            <h1 class="form-title">
                <span>✏️</span>
                Modifier l'ordonnance
                <span class="edit-badge">Édition</span>
            </h1>
            <p class="form-subtitle">Modifiez les informations de cette prescription</p>
        </div>

        @if($errors->any())
            <div class="alert alert-modern alert-danger-modern">
                <strong>⚠️ Erreurs détectées :</strong>
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('medecin.ordonnances.update', $ordonnance) }}" enctype="multipart/form-data" id="editPrescriptionForm">
            @csrf
            @method('PUT')
            
            <div class="form-section">
                <div class="form-group-modern">
                    <label for="patient_id" class="form-label-modern">
                        <span>👤</span>
                        Patient
                        <span class="required-mark">*</span>
                    </label>
                    <select name="patient_id" id="patient_id" class="form-control-modern select-modern" required>
                        <option value="">Sélectionner un patient</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @if($ordonnance->patient_id == $patient->id) selected @endif>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group-modern">
                    <label for="titre" class="form-label-modern">
                        <span>📌</span>
                        Titre
                        <span class="required-mark">*</span>
                    </label>
                    <input type="text" 
                           name="titre" 
                           id="titre" 
                           class="form-control-modern" 
                           value="{{ $ordonnance->titre }}" 
                           placeholder="Ex: Traitement antibiotique, Contrôle tension..."
                           required>
                </div>

                <div class="form-group-modern">
                    <label for="description" class="form-label-modern">
                        <span>📝</span>
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              class="form-control-modern textarea-modern" 
                              placeholder="Détails de l'ordonnance, posologie, instructions pour le patient...">{{ $ordonnance->description }}</textarea>
                </div>

                <div class="form-group-modern">
                    <label for="date_prescription" class="form-label-modern">
                        <span>📅</span>
                        Date de prescription
                        <span class="required-mark">*</span>
                    </label>
                    <input type="date" 
                           name="date_prescription" 
                           id="date_prescription" 
                           class="form-control-modern" 
                           value="{{ $ordonnance->date_prescription }}" 
                           required>
                </div>

                <div class="form-group-modern">
                    <label class="form-label-modern">
                        <span>📎</span>
                        Fichier joint
                    </label>
                    
                    @if($ordonnance->fichier)
                        <div class="current-file-card">
                            <div class="file-icon-large">📄</div>
                            <div class="file-details">
                                <h6>Fichier actuel</h6>
                                <p>{{ basename($ordonnance->fichier) }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $ordonnance->fichier) }}" target="_blank" class="view-file-btn">
                                <span>👁️</span>
                                Voir le fichier
                            </a>
                        </div>
                    @endif
                    
                    <div class="file-input-container">
                        <input type="file" 
                               name="fichier" 
                               id="fichier" 
                               class="file-input-modern" 
                               accept=".pdf,.jpg,.jpeg,.png"
                               onchange="updateFileLabel(this)">
                        <label for="fichier" class="file-input-label" id="fileLabel">
                            <span class="file-icon">📎</span>
                            <div>
                                <div class="file-text">{{ $ordonnance->fichier ? 'Remplacer le fichier' : 'Ajouter un fichier' }}</div>
                                <div class="file-info">PDF, JPG, PNG acceptés (Max 5Mo)</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-modern btn-warning-modern" id="submitBtn">
                    <span>💾</span>
                    Enregistrer les modifications
                </button>
                <a href="{{ route('medecin.ordonnances.index') }}" class="btn btn-modern btn-secondary-modern">
                    <span>↩️</span>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner"></div>
</div>

<script>
// Mise à jour du label du fichier
function updateFileLabel(input) {
    const label = document.getElementById('fileLabel');
    const fileIcon = label.querySelector('.file-icon');
    const fileText = label.querySelector('.file-text');
    const fileInfo = label.querySelector('.file-info');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        label.classList.add('has-file');
        fileIcon.textContent = '✅';
        fileText.textContent = fileName;
        fileInfo.textContent = `Taille: ${fileSize} Mo - Remplacera le fichier actuel`;
    } else {
        label.classList.remove('has-file');
        fileIcon.textContent = '📎';
        fileText.textContent = '{{ $ordonnance->fichier ? "Remplacer le fichier" : "Ajouter un fichier" }}';
        fileInfo.textContent = 'PDF, JPG, PNG acceptés (Max 5Mo)';
    }
}

// Animation de soumission
document.getElementById('editPrescriptionForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    submitBtn.innerHTML = '<span>⏳</span> Mise à jour en cours...';
    submitBtn.disabled = true;
    loadingOverlay.style.display = 'flex';
});

// Validation côté client
document.getElementById('fichier').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const maxSize = 5 * 1024 * 1024; // 5Mo
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        
        if (file.size > maxSize) {
            alert('Le fichier est trop volumineux. Taille maximum: 5Mo');
            e.target.value = '';
            updateFileLabel(e.target);
            return;
        }
        
        if (!allowedTypes.includes(file.type)) {
            alert('Type de fichier non autorisé. Utilisez: PDF, JPG, PNG');
            e.target.value = '';
            updateFileLabel(e.target);
            return;
        }
    }
});

// Animation au focus des champs
document.querySelectorAll('.form-control-modern').forEach(function(element) {
    element.addEventListener('focus', function() {
        this.parentNode.classList.add('focused');
    });
    
    element.addEventListener('blur', function() {
        this.parentNode.classList.remove('focused');
    });
});

// Message de confirmation avant annulation si des modifications ont été faites
let formChanged = false;
document.querySelectorAll('#editPrescriptionForm input, #editPrescriptionForm textarea, #editPrescriptionForm select').forEach(function(element) {
    element.addEventListener('change', function() {
        formChanged = true;
    });
});

document.querySelector('.btn-secondary-modern').addEventListener('click', function(e) {
    if (formChanged) {
        if (!confirm('Voulez-vous vraiment quitter sans enregistrer les modifications ?')) {
            e.preventDefault();
        }
    }
});
</script>

@endsection