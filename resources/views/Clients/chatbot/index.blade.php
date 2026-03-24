{{--
    Vue : Interface du chatbot - Assistant IA
    Description : Interface de conversation en temps réel avec l'assistant IA du salon. Inclut des suggestions rapides, des questions fréquentes, l'envoi de messages via AJAX et l'affichage des réponses avec formatage Markdown.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="chat-card-wrapper">
                    <div class="chat-header-bar">
                        <div class="chat-header-left">
                            <div class="chat-header-avatar">
                                <i class="fa fa-robot"></i>
                                <span class="chat-online-dot"></span>
                            </div>
                            <div>
                                <h4 class="chat-header-title">Assistant IA</h4>
                                <span class="chat-header-status">En ligne</span>
                            </div>
                        </div>
                        <a href="{{ route('client.chatbot.history') }}" class="chat-history-btn">
                            <i class="fa fa-history mr-1"></i>Historique
                        </a>
                    </div>
                    <div id="chat-container" class="chat-container">
                        <div id="chat-messages">
                            <div class="chat-date-separator"><span>Aujourd'hui</span></div>
                            <div class="message bot-message">
                                <div class="message-content">
                                    <div class="bubble">
                                        Bonjour {{ $client->name ?? 'cher client' }} !<br>
                                        Bienvenue au salon. Comment puis-je vous aider ?
                                        @if($client)
                                        <br><br>Votre niveau fidélité : <strong>{{ $client->getLoyaltyLevel() }}</strong> ({{ $client->loyalty_points ?? 0 }} points)
                                        @endif
                                    </div>
                                </div>
                                <div class="suggestions mt-2">
                                    <button class="suggestion-btn" data-message="Voir les services">Services</button>
                                    <button class="suggestion-btn" data-message="Voir les promotions">Promos</button>
                                    <button class="suggestion-btn" data-message="Prendre rendez-vous">Rendez-vous</button>
                                    <button class="suggestion-btn" data-message="Mes points fidélité">Fidélité</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="chat-input-area">
                        <form id="chat-form" class="chat-input-wrap">
                            @csrf
                            <input type="text" id="message-input" class="chat-input"
                                   placeholder="Écrivez un message..." autocomplete="off">
                            <button type="submit" class="chat-send-btn">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="quick-card mt-3">
                            <h5 class="quick-card-title">Questions fréquentes</h5>
                            <div class="d-flex flex-wrap">
                                <button class="quick-action" data-message="Quels sont vos services ?">
                                    <i class="fa fa-list mr-1"></i> Services
                                </button>
                                <button class="quick-action" data-message="Quelles promotions avez-vous ?">
                                    <i class="fa fa-percent mr-1"></i> Promos
                                </button>
                                <button class="quick-action" data-message="Je veux prendre un rendez-vous">
                                    <i class="fa fa-calendar mr-1"></i> RDV
                                </button>
                                <button class="quick-action" data-message="Quels sont vos horaires ?">
                                    <i class="fa fa-clock-o mr-1"></i> Horaires
                                </button>
                                <button class="quick-action" data-message="Où êtes-vous situé ?">
                                    <i class="fa fa-map-marker mr-1"></i> Adresse
                                </button>
                                <button class="quick-action" data-message="Mes points fidélité">
                                    <i class="fa fa-star mr-1"></i> Fidélité
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="quick-card mt-3">
                            <h5 class="quick-card-title">Mon compte</h5>
                            <div class="d-flex flex-wrap">
                                <button class="quick-action" data-message="Mes rendez-vous">
                                    <i class="fa fa-calendar-check-o mr-1"></i> Mes RDV
                                </button>
                                <button class="quick-action" data-message="Mon historique">
                                    <i class="fa fa-history mr-1"></i> Historique
                                </button>
                                <button class="quick-action" data-message="Mes factures">
                                    <i class="fa fa-file-text-o mr-1"></i> Factures
                                </button>
                                <button class="quick-action" data-message="Mon profil">
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

<style>
/* ============ CHAT CARD ============ */
.chat-card-wrapper {
    border-radius: 18px; overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    background: var(--bg);
    display: flex; flex-direction: column;
}

/* ============ HEADER ============ */
.chat-header-bar {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; padding: 16px 20px;
    display: flex; align-items: center; justify-content: space-between;
}
.chat-header-left { display: flex; align-items: center; gap: 12px; }
.chat-header-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    background: rgba(255,255,255,0.2); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; position: relative; color: white;
}
.chat-online-dot {
    position: absolute; bottom: 1px; right: 1px;
    width: 10px; height: 10px; border-radius: 50%;
    background: var(--accent); border: 2px solid var(--dark);
}
.chat-header-title {
    font-family: 'Playfair Display', serif; font-weight: 600; font-size: 18px;
    margin: 0; color: white;
}
.chat-header-status { font-size: 12px; opacity: 0.85; color: white; }
.chat-history-btn {
    background: rgba(255,255,255,0.15); color: white; border: none;
    padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 500;
    text-decoration: none !important; transition: all 0.2s;
}
.chat-history-btn:hover { background: rgba(255,255,255,0.3); color: white; }

/* ============ DATE SEPARATOR ============ */
.chat-date-separator { text-align: center; margin: 8px 0 14px; }
.chat-date-separator span {
    background: var(--primary-soft); color: var(--dark-light); font-size: 11px;
    padding: 4px 14px; border-radius: 10px; font-weight: 500;
}

/* ============ MESSAGES ============ */
.chat-container {
    height: 500px; overflow-y: auto; padding: 20px;
    display: flex; flex-direction: column;
    background: var(--bg);
}

.message {
    margin-bottom: 8px; max-width: 80%;
    animation: chatMsgIn 0.25s ease;
}
.bot-message { align-self: flex-start; }
.user-message { align-self: flex-end; }

@keyframes chatMsgIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}

.message-content {
    display: flex; align-items: flex-start; gap: 10px;
}
.user-message .message-content { flex-direction: row-reverse; }

.bubble {
    padding: 10px 14px; border-radius: 14px; font-size: 13.5px;
    line-height: 1.5; white-space: pre-wrap; word-wrap: break-word;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.bot-message .bubble {
    background: white; color: #2D2D2D;
    border-top-left-radius: 4px;
    border-left: 3px solid var(--primary);
}
.user-message .bubble {
    background: var(--primary-soft); color: #2D2D2D;
    border-top-right-radius: 4px;
}

/* ============ SUGGESTIONS ============ */
.suggestions {
    display: flex; flex-wrap: wrap; gap: 6px;
    margin-top: 8px;
}
.suggestion-btn {
    background: white; border: 1.5px solid var(--primary-light); color: var(--dark);
    padding: 7px 16px; border-radius: 20px; font-size: 12px; font-weight: 500;
    cursor: pointer; transition: all 0.25s; white-space: nowrap;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.suggestion-btn:hover {
    background: var(--primary); color: white; border-color: var(--primary);
    transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,0.12);
}

/* ============ INPUT ============ */
.chat-input-area {
    background: white; padding: 12px 16px;
    border-top: 1px solid var(--primary-soft);
}
.chat-input-wrap {
    display: flex; align-items: center; gap: 10px; margin: 0;
}
.chat-input {
    flex: 1; border: 1.5px solid var(--primary-soft); background: var(--bg);
    border-radius: 24px; padding: 10px 18px; font-size: 14px;
    font-family: 'Poppins', sans-serif; outline: none;
    transition: border-color 0.2s;
}
.chat-input:focus { border-color: var(--primary-light); }
.chat-input::placeholder { color: #8E8E8E; }
.chat-send-btn {
    width: 42px; height: 42px; border-radius: 50%; border: none;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; font-size: 16px;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: all 0.25s; flex-shrink: 0;
}
.chat-send-btn:hover { transform: scale(1.08); box-shadow: 0 3px 12px rgba(0,0,0,0.2); }

/* ============ TYPING ============ */
.typing-indicator {
    display: flex; gap: 5px; padding: 10px;
}
.typing-indicator span {
    width: 7px; height: 7px; background: var(--primary-light); border-radius: 50%;
    animation: chatBounce 1.4s ease-in-out infinite;
}
.typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
@keyframes chatBounce {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-5px); }
}

/* ============ QUICK CARDS ============ */
.quick-card {
    background: white; border-radius: 14px; padding: 18px 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
.quick-card-title {
    font-family: 'Playfair Display', serif; font-size: 16px;
    font-weight: 600; color: var(--dark); margin-bottom: 12px;
}
.quick-action {
    background: white; border: 1.5px solid var(--primary-light); color: var(--dark);
    padding: 7px 14px; border-radius: 20px; font-size: 12px; font-weight: 500;
    cursor: pointer; transition: all 0.25s; margin: 3px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.quick-action:hover {
    background: var(--primary); color: white; border-color: var(--primary);
    transform: translateY(-1px);
}

/* ============ DARK MODE ============ */
.dark-theme .chat-card-wrapper { background: #1a1a2e; }
.dark-theme .chat-container { background: #1a1a2e; }
.dark-theme .bot-message .bubble { background: #2a2a3e; color: #E9EDEF; }
.dark-theme .user-message .bubble { background: #2a2a3e; color: #E9EDEF; }
.dark-theme .chat-input-area { background: #222236; }
.dark-theme .chat-input { background: #2a2a3e; color: #E9EDEF; border-color: #444; }
.dark-theme .suggestion-btn { background: #2a2a3e; color: var(--primary-light); border-color: var(--primary-light); }
.dark-theme .suggestion-btn:hover { background: var(--primary); color: white; }
.dark-theme .quick-card { background: #2a2a3e; }
.dark-theme .quick-card-title { color: #E9EDEF; }
.dark-theme .quick-action { background: #2a2a3e; color: var(--primary-light); border-color: var(--primary-light); }
.dark-theme .quick-action:hover { background: var(--primary); color: white; }
.dark-theme .chat-date-separator span { background: #2a2a3e; color: #8E8E8E; }
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
            .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" style="color:var(--primary);text-decoration:underline;">$1</a>')
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
