@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Debug info -->
    <div class="alert alert-info">
        <strong>Debug :</strong> Page dashboard patient chargée. 
        Appointments: {{ $appointments->count() ?? 0 }}, 
        Consultations: {{ $consultations->count() ?? 0 }}, 
        Notifications: {{ $notifications->count() ?? 0 }}
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Tableau de bord Patient</h2>
        
        <!-- Contenu du dashboard -->
        <div class="row">
            <!-- Statistiques -->
            <div class="col-md-4 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Mes Rendez-vous</h5>
                        <h2 class="display-4">{{ $appointments->count() ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Consultations</h5>
                        <h2 class="display-4">{{ $consultations->count() ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h5 class="card-title">Notifications</h5>
                        <h2 class="display-4">{{ $notifications->count() ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="mt-4">
            <h3 class="h4 mb-3">Actions rapides</h3>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <a href="{{ route('patient.appointments.index') }}" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-calendar-plus fa-2x mb-2"></i><br>
                        Prendre RDV
                    </a>
                </div>
                
                <div class="col-md-3 mb-3">
                    <a href="{{ route('patient.consultations.index') }}" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-stethoscope fa-2x mb-2"></i><br>
                        Mes Consultations
                    </a>
                </div>
                
                <div class="col-md-3 mb-3">
                    <a href="{{ route('patient.messages.index') }}" class="btn btn-info btn-lg w-100">
                        <i class="fas fa-envelope fa-2x mb-2"></i><br>
                        Messages
                    </a>
                </div>
                
                <div class="col-md-3 mb-3">
                    <a href="{{ route('patient.abonnements.index') }}" class="btn btn-warning btn-lg w-100">
                        <i class="fas fa-credit-card fa-2x mb-2"></i><br>
                        Abonnements
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DE-IA Assistant Dermatologique -->
<div id="deiaBtn" style="position: fixed; bottom: 30px; right: 30px; z-index: 9999;">
    <button onclick="toggleDeIA()" class="btn btn-warning rounded-circle shadow-lg" 
            style="width: 70px; height: 70px; font-size: 28px; background: linear-gradient(45deg, #007bff, #6f42c1); border: none;">
        🤖
    </button>
</div>

<!-- Interface DE-IA -->
<div id="deiaChatBox" class="card shadow-lg" style="
    position: fixed;
    bottom: 120px;
    right: 30px;
    width: 350px;
    height: 450px;
    display: none;
    flex-direction: column;
    z-index: 9999;
">
    <!-- Header -->
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <span class="h4 mb-0 me-2">🤖</span>
            <div>
                <h5 class="mb-0">DE-IA</h5>
                <small>Assistant Dermatologique</small>
            </div>
        </div>
        <button onclick="toggleDeIA()" class="btn-close btn-close-white"></button>
    </div>
    
    <!-- Messages -->
    <div id="deiaMessages" class="card-body overflow-auto" style="height: 300px;">
        <div class="alert alert-info">
            <strong>DE-IA :</strong> Bonjour ! Je suis DE-IA, votre assistant spécialisé en dermatologie. 
            Posez-moi vos questions sur les problèmes de peau, mais n'oubliez pas que je suis là pour vous aider, 
            pas pour remplacer un diagnostic médical.
        </div>
    </div>
    
    <!-- Input -->
    <div class="card-footer">
        <form id="deiaForm" class="d-flex gap-2">
            <input type="text" id="deiaInput" class="form-control" placeholder="Décrivez votre problème de peau..." required>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script>
// Debug: Vérifier que le script se charge
console.log('DE-IA Script chargé');

function toggleDeIA() {
    console.log('Toggle DE-IA appelé');
    const chatBox = document.getElementById('deiaChatBox');
    if (chatBox.style.display === 'none' || chatBox.style.display === '') {
        chatBox.style.display = 'flex';
        console.log('DE-IA affiché');
    } else {
        chatBox.style.display = 'none';
        console.log('DE-IA masqué');
    }
}

// Gestion du formulaire DE-IA
document.getElementById('deiaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const input = document.getElementById('deiaInput');
    const message = input.value.trim();
    
    if (!message) return;
    
    // Afficher le message utilisateur
    addMessage('user', message);
    input.value = '';
    
    // Afficher l'indicateur de chargement
    addLoadingMessage();
    
    // Envoyer à l'API
    fetch('/ia/gemini', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        // Supprimer le message de chargement
        removeLoadingMessage();
        
        if (data.success) {
            addMessage('ai', data.response);
        } else {
            addMessage('ai', 'Désolé, je rencontre des difficultés. Veuillez consulter un dermatologue.');
        }
    })
    .catch(error => {
        removeLoadingMessage();
        addMessage('ai', 'Erreur de connexion. Veuillez consulter un dermatologue.');
    });
});

function addMessage(type, content) {
    const messagesContainer = document.getElementById('deiaMessages');
    const messageDiv = document.createElement('div');
    
    if (type === 'user') {
        messageDiv.className = 'alert alert-primary text-end';
        messageDiv.innerHTML = `<strong>Vous :</strong> ${content}`;
    } else {
        messageDiv.className = 'alert alert-light';
        messageDiv.innerHTML = `<strong>DE-IA :</strong> ${content}`;
    }
    
    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function addLoadingMessage() {
    const messagesContainer = document.getElementById('deiaMessages');
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'loadingMessage';
    loadingDiv.className = 'alert alert-light';
    loadingDiv.innerHTML = '<strong>DE-IA :</strong> <i class="fas fa-spinner fa-spin"></i> Je réfléchis...';
    
    messagesContainer.appendChild(loadingDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

function removeLoadingMessage() {
    const loadingMessage = document.getElementById('loadingMessage');
    if (loadingMessage) {
        loadingMessage.remove();
    }
}

// Vérifier que le bouton est bien présent
document.addEventListener('DOMContentLoaded', function() {
    const deiaBtn = document.getElementById('deiaBtn');
    if (deiaBtn) {
        console.log('Bouton DE-IA trouvé');
    } else {
        console.log('Bouton DE-IA NON trouvé');
    }
});
</script>

@endsection

