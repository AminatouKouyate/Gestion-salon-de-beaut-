@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Mes Notifications</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                @if($notifications->where('read', false)->count() > 0)
                <form action="{{ route('client.notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa fa-check-double mr-2"></i>Tout marquer comme lu
                    </button>
                </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if($notifications->isEmpty())
                            <div class="text-center py-5">
                                <i class="fa fa-bell-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune notification</p>
                            </div>
                        @else
                            <div class="list-group">
                                @foreach($notifications as $notification)
                                <div class="list-group-item {{ $notification->read ? '' : 'bg-light border-primary' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                @switch($notification->type)
                                                    @case('appointment_reminder')
                                                        <i class="fa fa-calendar text-warning mr-2"></i>
                                                        @break
                                                    @case('appointment_confirmed')
                                                        <i class="fa fa-check-circle text-success mr-2"></i>
                                                        @break
                                                    @case('payment_confirmed')
                                                        <i class="fa fa-credit-card text-info mr-2"></i>
                                                        @break
                                                    @case('loyalty_points')
                                                        <i class="fa fa-star text-warning mr-2"></i>
                                                        @break
                                                    @case('promotion')
                                                        <i class="fa fa-gift text-danger mr-2"></i>
                                                        @break
                                                    @default
                                                        <i class="fa fa-bell text-primary mr-2"></i>
                                                @endswitch
                                                <h6 class="mb-0">{{ $notification->title }}</h6>
                                                @if(!$notification->read)
                                                    <span class="badge badge-primary ml-2">Nouveau</span>
                                                @endif
                                            </div>
                                            <p class="mb-1">{{ $notification->message }}</p>
                                            <small class="text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        @if(!$notification->read)
                                        <form action="{{ route('client.notifications.markRead', $notification) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Marquer comme lu">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="mt-3">
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
