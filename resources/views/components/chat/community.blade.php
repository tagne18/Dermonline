<!-- Community Chat -->
<div id="communityBtn" style="position: fixed; bottom: 30px; right: 120px; z-index: 9999;">
    <button onclick="toggleCommunityChat()" class="btn btn-info rounded-circle shadow-lg" 
            style="width: 70px; height: 70px; font-size: 28px; background: linear-gradient(45deg, #17a2b8, #6610f2); border: none;">
        💬
    </button>
</div>

<!-- Community Chat Interface -->
<div id="communityChatBox" class="card shadow-lg" style="
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
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <span class="h4 mb-0 me-2">💬</span>
            <div>
                <h5 class="mb-0">Communauté</h5>
                <small>Échangez avec tout le monde</small>
            </div>
        </div>
        <button onclick="toggleCommunityChat()" class="btn-close btn-close-white"></button>
    </div>
    
    <!-- Messages -->
    <div id="communityMessages" class="card-body overflow-auto" style="height: 300px;">
        <!-- Messages will be loaded here -->
    </div>
    
    <!-- Input -->
    <div class="card-footer">
        <form id="communityForm" class="d-flex gap-2">
            <input type="text" id="communityInput" class="form-control" placeholder="Votre message..." required>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script>
function toggleCommunityChat() {
    const chatBox = document.getElementById('communityChatBox');
    chatBox.style.display = (chatBox.style.display === 'none' || chatBox.style.display === '') ? 'flex' : 'none';
}

// Handle community chat form submission
document.getElementById('communityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const input = document.getElementById('communityInput');
    const message = input.value.trim();
    
    if (!message) return;

    // Send to the API
    fetch("{{ route('medecin.messages.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            // We can add the new message to the UI here, or reload messages
            loadCommunityMessages(); 
        } else {
            alert('Erreur lors de l\'envoi du message.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur de connexion.');
    });
});

function loadCommunityMessages() {
    fetch("{{ route('medecin.messages.index') }}")
        .then(response => response.json())
        .then(messages => {
            const messagesContainer = document.getElementById('communityMessages');
            messagesContainer.innerHTML = ''; // Clear existing messages
            messages.forEach(msg => {
                const messageDiv = document.createElement('div');
                const isCurrentUser = msg.user === "{{ auth()->user()->name }}";
                
                messageDiv.className = isCurrentUser ? 'alert alert-primary text-end' : 'alert alert-secondary';
                
                let content = `<strong>${isCurrentUser ? 'Vous' : msg.user}:</strong> ${msg.content}`;
                
                if (msg.created_at) {
                    content += `<br><small class="text-muted">${msg.created_at}</small>`;
                }

                messageDiv.innerHTML = content;
                messagesContainer.appendChild(messageDiv);
            });
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });
}

// Load messages when chat is opened
document.getElementById('communityBtn').addEventListener('click', loadCommunityMessages);

// Periodically refresh messages
setInterval(loadCommunityMessages, 5000); // every 5 seconds

</script>
