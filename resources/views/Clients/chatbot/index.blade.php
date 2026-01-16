@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card chat-card">
                    <div class="card-header chat-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title text-white mb-0">
                            <i class="fa fa-robot mr-2"></i>Assistant IA
                        </h4>
                        <a href="{{ route('client.chatbot.history') }}" class="btn btn-light btn-sm">
                            <i class="fa fa-history mr-1"></i>Historique
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div id="chat-container" class="chat-container" style="height: 500px; overflow-y: auto; padding: 20px;">
                            <div id="chat-messages">
                                <div class="message bot-message">
                                    <div class="message-content">
                                        <div class="bubble">
                                            Bonjour {{ $client->name ?? 'cher client' }} ! 👋<br>
                                            Bienvenue au salon. Comment puis-je vous aider ?
                                            @if($client)
                                            <br><br>🎖️ Votre niveau fidélité : <strong>{{ $client->getLoyaltyLevel() }}</strong> ({{ $client->loyalty_points ?? 0 }} points)
                                            @endif
                                        </div>
                                    </div>
                                    <div class="suggestions mt-2">
                                        <button class="btn btn-outline-primary btn-sm suggestion-btn" data-message="Voir les services">Services</button>
                                        <button class="btn btn-outline-primary btn-sm suggestion-btn" data-message="Voir les promotions">Promotions 🔥</button>
                                        <button class="btn btn-outline-primary btn-sm suggestion-btn" data-message="Prendre rendez-vous">Rendez-vous</button>
                                        <button class="btn btn-outline-primary btn-sm suggestion-btn" data-message="Mes points fidélité">Fidélité</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="chat-input-area p-3 border-top">
                            <form id="chat-form" class="d-flex">
                                @csrf
                                <input type="text" id="message-input" class="form-control mr-2"
                                       placeholder="Écrivez votre message..." autocomplete="off">
                                <button type="submit" class="btn btn-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title">💡 Questions fréquentes</h5>
                                <div class="d-flex flex-wrap">
                                    <button class="btn btn-outline-secondary btn-sm m-1 quick-action" data-message="Quels sont vos services ?">
                                        <i class="fa fa-list mr-1"></i> Services
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm m-1 quick-action" data-message="Quelles promotions avez-vous ?">
                                        <i class="fa fa-percent mr-1"></i> Promos
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm m-1 quick-action" data-message="Je veux prendre un rendez-vous">
                                        <i class="fa fa-calendar mr-1"></i> RDV
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm m-1 quick-action" data-message="Quels sont vos horaires ?">
                                        <i class="fa fa-clock-o mr-1"></i> Horaires
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm m-1 quick-action" data-message="Où êtes-vous situé ?">
                                        <i class="fa fa-map-marker mr-1"></i> Adresse
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm m-1 quick-action" data-message="Mes points fidélité">
                                        <i class="fa fa-star mr-1"></i> Fidélité
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title">📋 Mon compte</h5>
                                <div class="d-flex flex-wrap">
                                    <button class="btn btn-outline-info btn-sm m-1 quick-action" data-message="Mes rendez-vous">
                                        <i class="fa fa-calendar-check-o mr-1"></i> Mes RDV
                                    </button>
                                    <button class="btn btn-outline-info btn-sm m-1 quick-action" data-message="Mon historique">
                                        <i class="fa fa-history mr-1"></i> Historique
                                    </button>
                                    <button class="btn btn-outline-info btn-sm m-1 quick-action" data-message="Mes factures">
                                        <i class="fa fa-file-text-o mr-1"></i> Factures
                                    </button>
                                    <button class="btn btn-outline-info btn-sm m-1 quick-action" data-message="Mon profil">
                                        <i class="fa fa-user mr-1"></i> Profil
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-container {
    display: flex;
    flex-direction: column;
}

.message {
    margin-bottom: 15px;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.message-content {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

.user-message .message-content {
    flex-direction: row-reverse;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    display: none; /* Masquer l'avatar pour le style WhatsApp */
}

.user-message .avatar {
    background: #6c757d !important;
}

.bubble {
    max-width: 70%;
    padding: 8px 12px;
    border-radius: 7.5px;
    line-height: 1.5;
    white-space: pre-wrap;
    position: relative;
    font-size: 14.2px;
    box-shadow: 0 1px 0.5px rgba(0,0,0,0.13);
}

.bot-message .bubble {
    background: #FFFFFF;
    color: #111b21;
    border-radius: 0 7.5px 7.5px 7.5px;
    border: none;
}

.user-message .bubble {
    background: #D9FDD3;
    color: #111b21;
    border-radius: 7.5px 0 7.5px 7.5px;
}

.suggestions {
    margin-left: 50px;
}

.suggestion-btn {
    margin: 2px;
    border-radius: 20px;
    font-size: 12px;
}

.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 10px;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #6c757d;
    border-radius: 50%;
    animation: bounce 1.4s ease-in-out infinite;
}

.typing-indicator span:nth-child(1) { animation-delay: 0s; }
.typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

@keyframes bounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-4px); }
}

.action-btn {
    margin: 5px 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const chatMessages = document.getElementById('chat-messages');
    const chatContainer = document.getElementById('chat-container');

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function addMessage(text, isUser = false) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${isUser ? 'user-message' : 'bot-message'}`;

        const icon = isUser ? 'fa-user' : 'fa-robot';
        const bgClass = isUser ? '' : 'bg-primary text-white';

        messageDiv.innerHTML = `
            <div class="message-content">
                <div class="bubble">${text}</div>
            </div>
        `;

        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    function addSuggestions(suggestions) {
        if (!suggestions || suggestions.length === 0) return;

        const suggestionsDiv = document.createElement('div');
        suggestionsDiv.className = 'suggestions mt-2';
        suggestionsDiv.style.marginLeft = '50px';

        suggestions.forEach(suggestion => {
            const btn = document.createElement('button');
            btn.className = 'btn btn-outline-primary btn-sm suggestion-btn';
            btn.textContent = suggestion;
            btn.dataset.message = suggestion;
            suggestionsDiv.appendChild(btn);
        });

        chatMessages.appendChild(suggestionsDiv);
        scrollToBottom();
    }

    function addActions(actions) {
        if (!actions || actions.length === 0) return;

        const actionsDiv = document.createElement('div');
        actionsDiv.className = 'actions mt-2';
        actionsDiv.style.marginLeft = '50px';

        actions.forEach(action => {
            const btn = document.createElement('a');
            btn.href = action.url;
            btn.className = `btn btn-sm action-btn ${action.type === 'danger' ? 'btn-danger' : 'btn-primary'}`;
            btn.textContent = action.label;
            actionsDiv.appendChild(btn);
        });

        chatMessages.appendChild(actionsDiv);
        scrollToBottom();
    }

    function showTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.id = 'typing-indicator';
        typingDiv.className = 'message bot-message';
        typingDiv.innerHTML = `
            <div class="message-content">
                <div class="bubble">
                    <div class="typing-indicator">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        `;
        chatMessages.appendChild(typingDiv);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) indicator.remove();
    }

    function formatMessage(text) {
        return text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/~~(.*?)~~/g, '<del>$1</del>')
            .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-primary">$1</a>')
            .replace(/\n/g, '<br>');
    }

    async function sendMessage(message) {
        if (!message.trim()) return;

        addMessage(message, true);
        messageInput.value = '';

        showTypingIndicator();

        try {
            const response = await fetch('{{ route("client.chatbot.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();

            removeTypingIndicator();
            addMessage(formatMessage(data.reply));

            if (data.suggestions) {
                addSuggestions(data.suggestions);
            }

            if (data.actions) {
                addActions(data.actions);
            }
        } catch (error) {
            removeTypingIndicator();
            addMessage('Désolé, une erreur est survenue. Veuillez réessayer.');
        }
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage(messageInput.value);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('suggestion-btn') || e.target.classList.contains('quick-action')) {
            const message = e.target.dataset.message;
            if (message) sendMessage(message);
        }
    });

    messageInput.focus();
});
</script>
@endsection
