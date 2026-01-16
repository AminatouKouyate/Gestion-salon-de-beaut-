@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Mes Notifications</h4>
                    <p class="text-muted">Messages et notifications de l'administrateur</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste de mes notifications</h4>
                        @if($notifications->where('is_read', false)->count() > 0)
                            <form action="{{ route('employee.notifications.markAllAsRead') }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-info btn-sm">
                                    <i class="fa fa-check-double"></i> Tout marquer comme lu
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($notifications->isEmpty())
                            <div class="text-center py-4">
                                <i class="fa fa-bell-o fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune notification trouvée</p>
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
