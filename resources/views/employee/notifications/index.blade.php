{{--
    Vue : Notifications de l'employé
    Description : Liste des notifications de l'employé avec marquage comme lu.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-bell"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mes Notifications</h2>
                    <p class="beauty-page-subtitle">Messages et notifications de l'administrateur</p>
                </div>
            </div>
            @if($notifications->where('is_read', false)->count() > 0)
                <form action="{{ route('employee.notifications.markAllAsRead') }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="beauty-btn-primary"><i class="fa fa-check-double mr-2"></i>Tout marquer comme lu</button>
                </form>
            @endif
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste de mes notifications</h4>
                    </div>
                    <div class="card-body">
                        @if($notifications->isEmpty())
                            <div class="beauty-empty">
                                <i class="fa fa-bell-o"></i>
                                <h5>Aucune notification</h5>
                                <p>Aucune notification trouvée</p>
                            </div>
                        @else
                            <div class="list-group">
                                @foreach($notifications as $notification)
                                <div class="list-group-item {{ $notification->is_read ? '' : 'list-group-item-info' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            {{ $notification->title }}
                                            @if(!$notification->is_read)
                                                <span class="badge badge-danger">Nouveau</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-2">{{ $notification->message }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            Reçu le {{ $notification->created_at->format('d/m/Y à H:i') }}
                                        </small>
                                        @if(!$notification->is_read)
                                            <form action="{{ route('employee.notifications.markAsRead', $notification) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-check"></i> Marquer comme lu
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $notifications->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
