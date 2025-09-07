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

    <!-- Suggestions de questions -->
    <div class="px-2 pb-2">
        <div id="iaSuggestions" class="d-flex flex-wrap gap-2"></div>
    </div>
    <div class="card-footer p-2">
        <form id="iaForm" class="d-flex gap-2">
            <input type="text" id="iaInput" class="form-control form-control-sm border-0" placeholder="Message pour l'IA..." required autocomplete="off">
            <button class="btn btn-success btn-sm" type="submit">➡️</button>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
const iaBtn = document.getElementById('iaBtn');
const iaBox = document.getElementById('iaChatBox');
const iaForm = document.getElementById('iaForm');
const iaInput = document.getElementById('iaInput');
const iaMessages = document.getElementById('iaMessages');
const iaSuggestions = document.getElementById('iaSuggestions');

// Suggestions courantes
const suggestions = [
    "Quels sont les symptômes de l'eczéma ?",
    "Comment prévenir l'acné ?",
    //"Quels sont les risques des MST ?",
    //"Comment prendre soin de ma peau ?",
    //"Que faire en cas de démangeaisons ?"
];

// Affichage suggestions sous forme de boutons
function renderSuggestions() {
    iaSuggestions.innerHTML = '';
    suggestions.forEach(q => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-secondary btn-sm';
        btn.textContent = q;
        btn.onclick = () => {
            iaInput.value = q;
            iaInput.focus();
        };
        iaSuggestions.appendChild(btn);
    });
}
renderSuggestions();

// Historique du chat (sessionStorage)
function saveHistory() {
    sessionStorage.setItem('iaChatHistory', iaMessages.innerHTML);
}
function loadHistory() {
    const hist = sessionStorage.getItem('iaChatHistory');
    if(hist) iaMessages.innerHTML = hist;
}
loadHistory();

iaBtn.addEventListener('click', () => {
    iaBox.style.display = (iaBox.style.display === 'none' || iaBox.style.display === '') ? 'flex' : 'none';
});

iaForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const question = iaInput.value.trim();
    if (!question) return;

    appendIAMessage('Vous', question);
    iaInput.value = '';
    saveHistory();

    const thinking = appendIAMessage('IA', '<span class="spinner-border spinner-border-sm text-warning me-2"></span> <i>DE-IA réfléchit...</i>');

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
            const iaReply = formatIAResponse(data.response ?? '❌ Erreur de réponse IA.');
            appendIAMessage('IA', iaReply);
        } else {
            appendIAMessage('IA', '❌ Erreur: ' + (data.response || 'Erreur inconnue'));
        }
        saveHistory();
    } catch (error) {
        iaMessages.removeChild(thinking);
        appendIAMessage('IA', '❌ Erreur de communication avec l\'IA.');
        saveHistory();
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
                ${escapeHtml(message)}
            </div>
        `;
    } else {
        div.classList.add('text-start');
        div.innerHTML = `
            <div class="d-inline-block bg-light text-dark p-2 rounded shadow-sm ia-response">
                ${message}
            </div>
        `;
    }
    iaMessages.appendChild(div);
    iaMessages.scrollTop = iaMessages.scrollHeight;
    return div;
}

// Formatage simple (paragraphes, listes, emojis)
function formatIAResponse(text) {
    if (!text) return '';
    // Paragraphes
    let html = text.replace(/\n\n/g, '</p><p>').replace(/\n/g, '<br>');
    // Puces
    html = html.replace(/\u2022/g, '<br>•');
    // Mise en gras avertissement
    html = html.replace(/(⚠️ IMPORTANT[\s\S]*)/, '<strong class="text-danger">$1</strong>');
    return '<p>' + html + '</p>';
}
// Sécurité XSS
function escapeHtml(str) {
    return str.replace(/[&<>"]/g, function(c) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];
    });
}
</script>
<?php $__env->stopPush(); ?>

<!-- DE-IA Assistant Dermatologique - Visible uniquement pour les patients -->
<?php if(auth()->guard()->check()): ?>
    <?php if(auth()->user()->role === 'patient'): ?>
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
        width: 450px;
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
        <div id="deiaMessages" class="card-body overflow-auto" style="height: 220px;">
            <div class="alert alert-info">
                <strong>DE-IA :</strong> Bonjour ! Je suis DE-IA, votre assistant spécialisé en dermatologie. 
                Posez-moi vos questions sur les problèmes de peau, mais n'oubliez pas que je suis là pour vous aider, 
                pas pour remplacer un diagnostic médical.
            </div>
        </div>
        <!-- Suggestions de questions -->
        <div class="px-2 pb-2">
            <div id="deiaSuggestions" class="d-flex flex-wrap gap-2"></div>
        </div>
        <!-- Input -->
        <div class="card-footer">
            <form id="deiaForm" class="d-flex gap-2">
                <input type="text" id="deiaInput" class="form-control" placeholder="Décrivez votre problème de peau..." required autocomplete="off">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane"></i>
                </button>
                <button type="button" id="deiaUploadBtn" class="btn btn-secondary" title="Envoyer une photo" style="padding:0 12px;">
                    📷
                </button>
                <input type="file" id="deiaFileInput" accept="image/*" style="display:none;">
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

    // Suggestions courantes DE-IA
   const deiaSuggestions = [
        "Quels sont les symptômes de l'eczéma ?",
        "Comment prévenir l'acné ?",
        "Quels sont les risques des MST ?",
        "Comment prendre soin de ma peau ?",
        "Que faire en cas de démangeaisons ?"
    ];
    
    function renderDeiaSuggestions() {
        const sug = document.getElementById('deiaSuggestions');
        sug.innerHTML = '';
        deiaSuggestions.forEach(q => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-outline-secondary btn-sm';
            btn.textContent = q;
            btn.onclick = () => {
                document.getElementById('deiaInput').value = q;
                document.getElementById('deiaInput').focus();
            };
            sug.appendChild(btn);
        });
    }
    renderDeiaSuggestions();

    // Historique du chat (sessionStorage)
    function saveDeiaHistory() {
        sessionStorage.setItem('deiaChatHistory', document.getElementById('deiaMessages').innerHTML);
    }
    function loadDeiaHistory() {
        const hist = sessionStorage.getItem('deiaChatHistory');
        if(hist) document.getElementById('deiaMessages').innerHTML = hist;
    }
    loadDeiaHistory();

    // Gestion du formulaire DE-IA
    document.getElementById('deiaForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const input = document.getElementById('deiaInput');
        const message = input.value.trim();
        
        if (!message) return;
        // Masquer suggestions
        document.getElementById('deiaSuggestions').style.display = 'none';
        // Afficher le message utilisateur
        addDeiaMessage('user', message);
        input.value = '';
        saveDeiaHistory();
        
        // Afficher l'indicateur de chargement
        addDeiaLoadingMessage();
        
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
            removeDeiaLoadingMessage();
            
            if (data.success) {
                addDeiaMessage('ai', formatDeiaResponse(data.response));
            } else {
                addDeiaMessage('ai', 'Désolé, je rencontre des difficultés. Veuillez consulter un dermatologue.');
            }
            saveDeiaHistory();
        })
        .catch(error => {
            removeDeiaLoadingMessage();
            addDeiaMessage('ai', 'Erreur de connexion. Veuillez consulter un dermatologue.');
            saveDeiaHistory();
        });
    });

    // Gestion du bouton upload image
    document.getElementById('deiaUploadBtn').addEventListener('click', function() {
        document.getElementById('deiaFileInput').click();
    });
    document.getElementById('deiaFileInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        // Masquer suggestions
        document.getElementById('deiaSuggestions').style.display = 'none';
        addDeiaMessage('user', 'Image envoyée. Analyse en cours...');
        addDeiaLoadingMessage();
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        fetch('/ia/gemini-image', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            removeDeiaLoadingMessage();
            if (data.success) {
                addDeiaMessage('ai', formatDeiaResponse(data.response));
            } else {
                addDeiaMessage('ai', 'Erreur lors de l\'analyse de l\'image.');
            }
            saveDeiaHistory();
        })
        .catch(() => {
            removeDeiaLoadingMessage();
            addDeiaMessage('ai', 'Erreur technique lors de l\'analyse de l\'image.');
            saveDeiaHistory();
        });
    });

    function addDeiaMessage(type, content) {
        const messagesContainer = document.getElementById('deiaMessages');
        const messageDiv = document.createElement('div');
        
        if (type === 'user') {
            messageDiv.className = 'alert alert-primary text-end';
            messageDiv.innerHTML = `<strong>Vous :</strong> ${escapeHtml(content)}`;
        } else {
            messageDiv.className = 'alert alert-light';
            messageDiv.innerHTML = `<strong>DE-IA :</strong> ${content}`;
        }
        
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function addDeiaLoadingMessage() {
        const messagesContainer = document.getElementById('deiaMessages');
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'deiaLoadingMessage';
        loadingDiv.className = 'alert alert-light';
        loadingDiv.innerHTML = '<strong>DE-IA :</strong> <span class="spinner-border spinner-border-sm text-primary me-2"></span> Je réfléchis...';
        
        messagesContainer.appendChild(loadingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function removeDeiaLoadingMessage() {
        const loadingMessage = document.getElementById('deiaLoadingMessage');
        if (loadingMessage) {
            loadingMessage.remove();
        }
    }

    // Formatage IA (paragraphes, listes, avertissement)
    function formatDeiaResponse(text) {
        if (!text) return '';
        let html = text.replace(/\n\n/g, '</p><p>').replace(/\n/g, '<br>');
        html = html.replace(/\u2022/g, '<br>•');
        html = html.replace(/(⚠️ IMPORTANT[\s\S]*)/, '<strong class="text-danger">$1</strong>');
        return '<p>' + html + '</p>';
    }
    // Sécurité XSS
    function escapeHtml(str) {
        return str.replace(/[&<>"]/g, function(c) {
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];
        });
    }
    </script>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH /home/tagne/Desktop/DSL/dermonline/resources/views/components/chat/ia.blade.php ENDPATH**/ ?>