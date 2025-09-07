@extends('layouts.patient')

@section('title', 'Vérification du paiement')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Vérification du paiement</h4>
                </div>
                <div class="card-body text-center">
                    @if(session('info'))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ session('info') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="my-4">
                        <div class="spinner-border text-primary mb-3" role="status" id="verificationSpinner">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <h5 class="mb-3">Vérification du statut de votre paiement</h5>
                        <p class="text-muted mb-4">
                            Veuillez patienter pendant que nous vérifions le statut de votre transaction.
                        </p>
                        
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Référence :</span>
                                    <span class="fw-bold">{{ session('noupia_reference') ?? 'N/A' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Montant :</span>
                                    <span class="fw-bold">{{ number_format(session('noupia_amount', 0), 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Statut :</span>
                                    <span class="badge bg-warning text-dark" id="paymentStatus">En attente de vérification...</span>
                                </div>
                            </div>
                        </div>
                        
                        <form method="POST" action="{{ route('patient.paiement.verify') }}" id="verifyForm">
                            @csrf
                            <button type="submit" class="btn btn-primary" id="verifyButton">
                                <i class="fas fa-sync-alt me-2"></i>
                                <span id="buttonText">Vérifier à nouveau</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" id="buttonSpinner"></span>
                            </button>
                        </form>
                        
                        <div class="mt-4">
                            <a href="{{ route('patient.paiement.form') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Revenir au formulaire de paiement
                            </a>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-4 text-start">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Informations importantes</h6>
                        <ul class="mb-0">
                            <li>Le traitement peut prendre quelques minutes selon votre opérateur mobile</li>
                            <li>Si le paiement est réussi mais que le statut ne se met pas à jour, veuillez rafraîchir la page</li>
                            <li>En cas de problème, contactez notre support avec votre référence de transaction</li>
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
    const verifyForm = document.getElementById('verifyForm');
    const verifyButton = document.getElementById('verifyButton');
    const buttonText = document.getElementById('buttonText');
    const buttonSpinner = document.getElementById('buttonSpinner');
    const verificationSpinner = document.getElementById('verificationSpinner');
    const paymentStatus = document.getElementById('paymentStatus');
    
    // Auto-vérification au chargement de la page
    setTimeout(() => {
        verifyForm.requestSubmit();
    }, 2000);
    
    // Gestion de la soumission du formulaire
    if (verifyForm) {
        verifyForm.addEventListener('submit', function(e) {
            // Empêcher la soumission multiple
            if (verifyButton.disabled) {
                e.preventDefault();
                return false;
            }
            
            // Mettre à jour l'UI
            verifyButton.disabled = true;
            buttonSpinner.classList.remove('d-none');
            buttonText.textContent = 'Vérification en cours...';
            verificationSpinner.classList.remove('d-none');
            
            return true;
        });
    }
    
    // Mettre à jour le statut si une erreur est affichée
    @if(session('error'))
        if (paymentStatus) {
            paymentStatus.className = 'badge bg-danger';
            paymentStatus.textContent = 'Échec de la vérification';
            verificationSpinner.classList.add('d-none');
        }
    @endif
    
    // Vérification périodique du statut
    let checkCount = 0;
    const maxChecks = 10; // Nombre maximum de vérifications
    
    function checkPaymentStatus() {
        if (checkCount >= maxChecks) {
            if (paymentStatus) {
                paymentStatus.className = 'badge bg-secondary';
                paymentStatus.textContent = 'Délai dépassé';
                verificationSpinner.classList.add('d-none');
            }
            return;
        }
        
        checkCount++;
        
        // Simuler une vérification périodique
        setTimeout(() => {
            // Ici, vous pourriez faire un appel AJAX pour vérifier le statut
            // Pour l'instant, on se contente d'une simulation
            if (checkCount === maxChecks) {
                if (paymentStatus) {
                    paymentStatus.className = 'badge bg-secondary';
                    paymentStatus.textContent = 'Délai dépassé';
                    verificationSpinner.classList.add('d-none');
                }
            }
        }, 3000); // Vérifier toutes les 3 secondes
    }
    
    // Démarrer la vérification périodique
    checkPaymentStatus();
});
</script>
@endpush

<style>
.badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}

.card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    border-bottom: none;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}

.alert {
    border-left: 4px solid;
}
</style>
@endsection 