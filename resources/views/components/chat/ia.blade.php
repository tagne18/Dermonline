<!-- Bouton flottant IA -->
<div style="position: fixed; bottom: 100px; right: 20px; z-index: 1000;">
    <button id="iaBtn" class="btn btn-warning rounded-circle shadow d-flex justify-content-center align-items-center"
        style="width: 60px; height: 60px; font-size: 24px;">
        🤖
    </button>
</div>

<!-- Interface de l'IA -->
<div id="iaChatBox" class="card shadow-lg" style="
    position: fixed;
    bottom: 170px;
    right: 20px;
    width: 320px;
    height: 450px;
    display: none;
    flex-direction: column;
    z-index: 1050;
">
    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center p-2">
        <span>🤖 Assistant IA</span>
        <button class="btn-close btn-sm" onclick="document.getElementById('iaChatBox').style.display='none'"></button>
    </div>

    <!-- Zone de messages IA -->
    <div id="iaMessages" class="card-body overflow-auto" style="flex: 1; height: 250px; font-size: 14px;">
        <div class="text-muted text-center">Posez-moi une question...</div>
    </div>

    <div class="card-footer p-2">
        <form id="iaForm" class="d-flex gap-2">
            <input type="text" id="iaInput" class="form-control form-control-sm border-0" placeholder="Message pour l'IA..." required>
            <button class="btn btn-success btn-sm" type="submit">➡️</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const iaBtn = document.getElementById('iaBtn');
    const iaBox = document.getElementById('iaChatBox');
    const iaForm = document.getElementById('iaForm');
    const iaInput = document.getElementById('iaInput');
    const iaMessages = document.getElementById('iaMessages');

    iaBtn.addEventListener('click', () => {
        iaBox.style.display = (iaBox.style.display === 'none' || iaBox.style.display === '') ? 'flex' : 'none';
    });

    iaForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const question = iaInput.value.trim();
        if (!question) return;

        appendIAMessage('Vous', question);
        iaInput.value = '';

        const thinking = appendIAMessage('IA', '<i>Réflexion en cours...</i>');

        try {
            const response = await fetch("/api/ia/gemini", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ message: question })
            });

            const data = await response.json();
            iaMessages.removeChild(thinking);

            if (data.success) {
                const iaReply = data.response ?? '❌ Erreur de réponse IA.';
                appendIAMessage('IA', iaReply);
            } else {
                appendIAMessage('IA', '❌ Erreur: ' + (data.response || 'Erreur inconnue'));
            }
        } catch (error) {
            iaMessages.removeChild(thinking);
            appendIAMessage('IA', '❌ Erreur de communication avec l'IA.');
            console.error(error);
        }
    });

    function appendIAMessage(sender, message) {
        const div = document.createElement('div');
        div.className = 'mb-2';

        if (sender === 'Vous') {
            div.classList.add('text-end');
            div.innerHTML = `
                <div class="d-inline-block bg-primary text-white p-2 rounded shadow-sm">
                    ${message}
                </div>
            `;
        } else {
            div.classList.add('text-start');
            div.innerHTML = `
                <div class="d-inline-block bg-light text-dark p-2 rounded shadow-sm">
                    ${message}
                </div>
            `;
        }

        iaMessages.appendChild(div);
        iaMessages.scrollTop = iaMessages.scrollHeight;
        return div;
    }
</script>
@endpush

<!-- DE-IA Assistant Dermatologique - Visible uniquement pour les patients -->
@auth
    @if(auth()->user()->role === 'patient')
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
    function toggleDeIA() {
        const chatBox = document.getElementById('deiaChatBox');
        if (chatBox.style.display === 'none' || chatBox.style.display === '') {
            chatBox.style.display = 'flex';
        } else {
            chatBox.style.display = 'none';
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
    </script>
    @endif
@endauth
