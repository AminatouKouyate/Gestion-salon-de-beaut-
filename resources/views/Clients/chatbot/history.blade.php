{{--
    Vue : Historique des conversations du chatbot
    Description : Affiche l'historique des échanges entre le client et l'assistant IA, groupés par date avec les messages utilisateur et les réponses du bot.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-history"></i></div>
                <div>
                    <h2 class="beauty-page-title">Historique des conversations</h2>
                    <p class="beauty-page-subtitle">Retrouvez vos échanges avec l'assistant</p>
                </div>
            </div>
            <a href="{{ route('client.chatbot.index') }}" class="beauty-btn-primary"><i class="fa fa-comments mr-2"></i>Nouvelle conversation</a>
        </div>

        <div class="card">
            <div class="card-body">
                @if($conversations->isEmpty())
                    <div class="beauty-empty">
                        <i class="fa fa-comments"></i>
                        <h5>Aucune conversation enregistrée</h5>
                        <p>Vous n'avez pas encore échangé avec l'assistant</p>
                        <a href="{{ route('client.chatbot.index') }}" class="btn btn-primary">
                            Démarrer une conversation
                        </a>
                    </div>
                @else
                    <div class="chat-history">
                        @foreach($conversations as $date => $messages)
                            <div class="date-separator text-center my-4">
                                <span class="badge badge-secondary">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                            </div>
                            @foreach($messages as $message)
                                <div class="message {{ $message->is_user_message ? 'user-message' : 'bot-message' }} mb-3">
                                    <div class="message-content d-flex {{ $message->is_user_message ? 'flex-row-reverse' : '' }} align-items-start" style="gap: 10px;">
                                        <div class="avatar {{ $message->is_user_message ? 'bg-secondary' : 'bg-primary' }} text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink: 0;">
                                            <i class="fa {{ $message->is_user_message ? 'fa-user' : 'fa-robot' }}"></i>
                                        </div>
                                        <div class="bubble p-3 rounded {{ $message->is_user_message ? 'bg-primary text-white' : 'bg-light border' }}" style="max-width: 70%;">
                                            {{ $message->is_user_message ? $message->message : $message->response }}
                                            <small class="d-block mt-1 {{ $message->is_user_message ? 'text-white-50' : 'text-muted' }}">
                                                {{ $message->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $conversations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
