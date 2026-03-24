{{--
    Vue : Messagerie employé
    Description : Liste des messages échangés entre l'employé et l'administration du salon.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-envelope"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mes Messages</h2>
                    <p class="beauty-page-subtitle">Messages à l'administration</p>
                </div>
            </div>
            <a href="{{ route('employee.messages.create') }}" class="beauty-btn-primary"><i class="fa fa-plus mr-2"></i>Nouveau message</a>
        </div>

        @include('partials.success')

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Sujet</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                        <tr>
                            <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ Str::limit($message->subject, 50) }}</td>
                            <td>
                                @if($message->status === 'pending')
                                    <span class="badge badge-warning">En attente</span>
                                @elseif($message->status === 'answered')
                                    <span class="badge badge-success">Répondu</span>
                                @else
                                    <span class="badge badge-secondary">Fermé</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('employee.messages.show', $message) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">Aucun message envoyé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($messages->hasPages())
                <div class="card-footer">
                    {{ $messages->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
