{{--
    Vue : Détails d'un message d'employé
    Route : admin.employee-messages.show
    Contrôleur : EmployeeMessageController@show
    Description : Affiche le contenu complet d'un message d'employé avec la réponse
                  de l'administration si elle existe, et un formulaire de réponse.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Message de {{ $message->employee->name ?? 'Employé' }}</h1>
            <a href="{{ route('admin.employee-messages.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        {{-- Section : Messages de succès et d'erreur --}}
        @include('partials.success')
@include('partials.error')

        <div class="row">
            {{-- Section : Contenu du message et réponse --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $message->subject }}</h5>
                        @if($message->status === 'pending')
                            <span class="badge bg-warning">En attente</span>
                        @elseif($message->status === 'answered')
                            <span class="badge bg-success">Répondu</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fa fa-user mr-1"></i>{{ $message->employee->name ?? '�' }} |
                                <i class="fa fa-clock-o mr-1"></i>{{ $message->created_at->format('d/m/Y à H:i') }}
                            </small>
                        </div>

                        <div class="p-3 bg-light rounded mb-4">
                            <h6 class="text-muted mb-2">Message de l'employé</h6>
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>
                        </div>

                        @if($message->admin_response)
                        <div class="p-3 bg-success-light rounded border-left border-success" style="border-left-width: 4px !important;">
                            <h6 class="text-success mb-2">
                                <i class="fa fa-reply mr-2"></i>Votre réponse
                            </h6>
                            <small class="text-muted d-block mb-2">
                                Répondu le {{ $message->responded_at->format('d/m/Y à H:i') }}
                            </small>
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $message->admin_response }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Section : Formulaire de réponse et informations --}}
            <div class="col-lg-4">
                @if($message->status === 'pending')
                {{-- Section : Formulaire de réponse --}}
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Répondre</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.employee-messages.reply', $message) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label">Votre réponse</label>
                                <textarea name="admin_response" class="form-control" rows="6" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block w-100">
                                <i class="fa fa-paper-plane mr-2"></i>Envoyer
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <div class="card {{ $message->status === 'pending' ? 'mt-4' : '' }}">
                    <div class="card-body">
                        <h5><i class="fa fa-info-circle mr-2 text-primary"></i>Informations</h5>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th>Employé</th>
                                <td>{{ $message->employee->name ?? '�' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $message->employee->email ?? '�' }}</td>
                            </tr>
                            <tr>
                                <th>Envoyé le</th>
                                <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @if($message->responded_at)
                            <tr>
                                <th>Répondu le</th>
                                <td>{{ $message->responded_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
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
