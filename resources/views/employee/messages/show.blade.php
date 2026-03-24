{{--
    Vue : Détails d'un message employé
    Description : Affiche le contenu complet d'un message avec l'historique de la conversation.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-envelope-open"></i></div>
                <div>
                    <h2 class="beauty-page-title">Détails du Message</h2>
                    <p class="beauty-page-subtitle">Consultation du message</p>
                </div>
            </div>
            <a href="{{ route('employee.messages.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $message->subject }}</h5>
                        @if($message->status === 'pending')
                            <span class="badge badge-warning">En attente de réponse</span>
                        @elseif($message->status === 'answered')
                            <span class="badge badge-success">Répondu</span>
                        @else
                            <span class="badge badge-secondary">Fermé</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <small class="text-muted">Envoyé le {{ $message->created_at->format('d/m/Y à H:i') }}</small>
                        </div>

                        <div class="p-3 bg-light rounded mb-4">
                            <h6 class="text-muted mb-2"><i class="fa fa-user mr-2"></i>Votre message</h6>
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>
                        </div>

                        @if($message->admin_response)
                        <div class="p-3 bg-success-light rounded border-left border-success" style="border-left-width: 4px !important;">
                            <h6 class="text-success mb-2">
                                <i class="fa fa-reply mr-2"></i>Réponse de l'administration
                            </h6>
                            <small class="text-muted d-block mb-2">
                                Répondu le {{ $message->responded_at->format('d/m/Y à H:i') }}
                            </small>
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $message->admin_response }}</p>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fa fa-clock-o mr-2"></i>Votre message est en attente de réponse de l'administration.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fa fa-envelope fa-3x text-primary mb-3"></i>
                        <h5>Besoin d'aide ?</h5>
                        <p class="text-muted">L'administration répondra à votre message dans les plus brefs délais.</p>
                        <a href="{{ route('employee.messages.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus mr-2"></i>Nouveau message
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-success-light {
    background-color: rgba(40, 167, 69, 0.1);
}
</style>
@endsection
