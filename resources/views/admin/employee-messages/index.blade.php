{{--
    Vue : Liste des messages des employés
    Route : admin.employee-messages.index
    Contrôleur : EmployeeMessageController@index
    Description : Affiche tous les messages envoyés par les employés avec filtrage
                  par statut (en attente, répondus) et possibilité de répondre via modale.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Messages des employés</h1>
        </div>

        {{-- Section : Messages de succès et d'erreur --}}
        @include('partials.success')
@include('partials.error')

        {{-- Section : Compteurs de statut --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">En attente</h5>
                        <h2>{{ $pendingCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Répondus</h5>
                        <h2>{{ $answeredCount }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section : Tableau des données avec filtres --}}
        <div class="card">
            <div class="card-header">
                {{-- Filtres par statut --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.employee-messages.index') }}" class="btn btn-sm {{ !request('status') || request('status') === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Tous
                    </a>
                    <a href="{{ route('admin.employee-messages.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">
                        En attente
                    </a>
                    <a href="{{ route('admin.employee-messages.index', ['status' => 'answered']) }}" class="btn btn-sm {{ request('status') === 'answered' ? 'btn-success' : 'btn-outline-success' }}">
                        Répondus
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Employé</th>
                            <th>Sujet</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                        <tr>
                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $message->employee->name ?? '�' }}</td>
                            <td>{{ Str::limit($message->subject, 40) }}</td>
                            <td>
                                @if($message->status === 'pending')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($message->status === 'answered')
                                    <span class="badge bg-success">Répondu</span>
                                @else
                                    <span class="badge bg-secondary">Fermé</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.employee-messages.show', $message) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @if($message->status === 'pending')
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#replyModal{{ $message->id }}">
                                        <i class="fa fa-reply"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>

                        {{-- Section : Modale de réponse au message --}}
                        <div class="modal fade" id="replyModal{{ $message->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <form action="{{ route('admin.employee-messages.reply', $message) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Répondre au message</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>De :</strong> {{ $message->employee->name ?? '�' }}</p>
                                            <p><strong>Sujet :</strong> {{ $message->subject }}</p>
                                            <div class="p-3 bg-light rounded mb-3">
                                                <strong>Message :</strong>
                                                <p class="mb-0">{{ $message->message }}</p>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Votre réponse <span class="text-danger">*</span></label>
                                                <textarea name="admin_response" class="form-control" rows="5" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-success">Envoyer la réponse</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun message.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Section : Pagination --}}
            @if($messages->hasPages())
                <div class="card-footer">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
