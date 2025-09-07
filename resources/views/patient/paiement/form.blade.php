@extends('layouts.patient')

@section('title', 'Paiement Abonnement')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Paiement de l'abonnement {{ ucfirst($pack) }}</h4>
                </div>
                <div class="card-body">
                    @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    
                    <form method="POST" action="{{ route('patient.paiement.process') }}" id="paymentForm">
                        @csrf
                        
                        <!-- Champs cachés pour le pack et le montant -->
                        <input type="hidden" name="pack" value="{{ $pack }}">
                        <input type="hidden" name="amount" value="{{ $montant }}">
                        <input type="hidden" name="medecin_id" value="{{ $medecins->first()->id ?? '' }}">
                        
                        <div class="mb-4 p-3 bg-light rounded">
                            <h5 class="mb-3">Détails de l'abonnement</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Pack :</span>
                                <span class="fw-bold">{{ ucfirst($pack) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Montant :</span>
                                <span class="fw-bold">{{ number_format($montant, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Paiement par Mobile Money</h5>
                                    
                                    <div class="mb-4">
                                        <label for="phone" class="form-label">Numéro de téléphone <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-phone"></i> +237
                                            </span>
                                            <input type="tel" 
                                                   class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                                   id="phone" 
                                                   name="phone" 
                                                   required
                                                   placeholder="677123456"
                                                   value="{{ old('phone', auth()->user()->phone ?? '') }}">
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Entrez votre numéro Orange Money ou MTN Mobile Money</small>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Opérateur <span class="text-danger">*</span></label>
                                        <div class="d-grid gap-3">
                                            <div class="form-check p-3 border rounded @error('operator') border-danger @enderror">
                                                <input class="form-check-input" type="radio" name="operator" id="orange" value="orange" {{ old('operator') == 'orange' ? 'checked' : 'checked' }}>
                                                <label class="form-check-label d-flex align-items-center" for="orange">
                                                    <i class="bi bi-phone me-2 text-warning" style="font-size: 1.5rem;"></i>
                                                    <div>
                                                        <div class="fw-bold">Orange Money</div>
                                                        <small class="text-muted">Paiement via votre compte Orange Money</small>
                                                    </div>
                                                </label>
                                            </div>
                                            <div class="form-check p-3 border rounded @error('operator') border-danger @enderror">
                                                <input class="form-check-input" type="radio" name="operator" id="mtn" value="mtn" {{ old('operator') == 'mtn' ? 'checked' : '' }}>
                                                <label class="form-check-label d-flex align-items-center" for="mtn">
                                                    <i class="bi bi-phone me-2 text-warning" style="font-size: 1.5rem;"></i>
                                                    <div>
                                                        <div class="fw-bold">MTN Mobile Money</div>
                                                        <small class="text-muted">Paiement via votre compte MTN Mobile Money</small>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        @error('operator')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Vous recevrez une demande de confirmation sur votre téléphone pour valider le paiement.
                                    </div>

                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg py-3">
                                            <i class="bi bi-credit-card me-2"></i> Payer {{ number_format($montant, 0, ',', ' ') }} FCFA
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="countryCode" value="+237">

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const phoneInput = document.getElementById('phone');
                            const form = document.getElementById('paymentForm');
                            
                            // Formater le numéro de téléphone
                            phoneInput.addEventListener('input', function(e) {
                                // Supprimer tout sauf les chiffres
                                let value = e.target.value.replace(/\D/g, '');
                                
                                // Limiter à 9 chiffres
                                if (value.length > 9) {
                                    value = value.substring(0, 9);
                                }
                                
                                // Mettre à jour la valeur sans formattage
                                e.target.value = value;
                                
                                // Validation visuelle
                                if (value.length === 9 && /^[23679]\d{8}$/.test(value)) {
                                    e.target.classList.remove('is-invalid');
                                } else if (value.length > 0) {
                                    e.target.classList.add('is-invalid');
                                } else {
                                    e.target.classList.remove('is-invalid');
                                }
                            });
                            
                            // Validation au submit
                            form.addEventListener('submit', function(e) {
                                const value = phoneInput.value.replace(/\D/g, '');
                                if (!/^[23679]\d{8}$/.test(value)) {
                                    e.preventDefault();
                                    phoneInput.focus();
                                    
                                    // Afficher un message d'erreur personnalisé
                                    const errorDiv = document.createElement('div');
                                    errorDiv.className = 'alert alert-danger mt-3';
                                    errorDiv.innerHTML = 'Veuillez entrer un numéro camerounais valide (9 chiffres commençant par 2, 3, 6, 7 ou 9)';
                                    
                                    // Supprimer les anciens messages d'erreur
                                    const oldAlert = form.querySelector('.alert-danger');
                                    if (oldAlert) oldAlert.remove();
                                    
                                    // Insérer le message d'erreur après le formulaire
                                    form.parentNode.insertBefore(errorDiv, form.nextSibling);
                                    
                                    // Défiler jusqu'au message d'erreur
                                    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                } else {
                                    // Désactiver le bouton pour éviter les doubles soumissions
                                    const submitBtn = form.querySelector('button[type="submit"]');
                                    if (submitBtn) {
                                        submitBtn.disabled = true;
                                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Traitement en cours...';
                                    }
                                }
                            });
                        });
                        </script>
                        
                        <div class="mb-3">
                            <label for="operator" class="form-label">Opérateur <span class="text-danger">*</span></label>
                            <select class="form-select" id="operator" name="operator" required>
                                <option value="" selected disabled>Choisissez votre opérateur</option>
                                <option value="orange" {{ old('operator') == 'orange' ? 'selected' : '' }}>Orange Money</option>
                                <option value="mtn" {{ old('operator') == 'mtn' ? 'selected' : '' }}>MTN Mobile Money</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="spinner"></span>
                                <span id="buttonText">Payer maintenant</span>
                            </button>
                        </div>
                    </form>
                    
                    <div class="alert alert-info mt-4">
                        <h5 class="alert-heading">Instructions de paiement</h5>
                        <ul class="mb-0">
                            <li>Assurez-vous d'avoir suffisamment de crédit sur votre compte mobile money</li>
                            <li>Vous recevrez une demande de confirmation sur votre téléphone</li>
                            <li>Validez la transaction avec votre code secret</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('buttonText');
    
    if (form) {
        form.addEventListener('submit', function() {
            // Désactiver le bouton et afficher le spinner
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            buttonText.textContent = 'Traitement en cours...';
        });
    }
    
    // Formater le numéro de téléphone au format 6XX XXX XXX
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 9) {
                value = value.substring(0, 9);
            }
            
            // Formater avec des espaces pour une meilleure lisibilité
            if (value.length > 0) {
                value = value.match(new RegExp('.{1,3}', 'g')).join(' ');
            }
            
            e.target.value = value;
        });
    }
});
</script>
@endpush
@endsection 