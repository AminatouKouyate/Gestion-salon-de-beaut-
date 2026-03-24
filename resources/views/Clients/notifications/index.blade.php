{{--
    Vue : Notifications du client
    Description : Liste paginée des notifications du client avec icônes par type (rappel, confirmation, paiement, fidélité, promotion), marquage comme lu individuel ou global.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-bell"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mes Notifications</h2>
                    <p class="beauty-page-subtitle">Vos notifications récentes</p>
                </div>
            </div>
            @if($notifications->where('read', false)->count() > 0)
            <form action="{{ route('client.notifications.markAllRead') }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="beauty-btn-primary"><i class="fa fa-check-double mr-2"></i>Tout marquer comme lu</button>
            </form>
            @endif
        </div>

        @include('partials.success')

        <div class="row">
            <div class="col-12">
                @if($notifications->isEmpty())
                    <div class="card" style="border:none;border-radius:18px;box-shadow:0 2px 16px rgba(0,0,0,0.06);">
                        <div class="card-body">
                            <div class="beauty-empty">
                                <i class="fa fa-bell-slash"></i>
                                <h5>Aucune notification</h5>
                                <p>Vous n'avez pas encore de notifications</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="notif-list">
                        @foreach($notifications as $notification)
                        <div class="notif-card {{ $notification->read ? '' : 'notif-unread' }}">
                            <div class="notif-card-icon">
                                @switch($notification->type)
                                    @case('appointment_reminder')
                                        <i class="fa fa-calendar" style="color:#F59E0B;"></i>
                                        @break
                                    @case('appointment_confirmed')
                                        <i class="fa fa-check-circle" style="color:#10B981;"></i>
                                        @break
                                    @case('payment_confirmed')
                                        <i class="fa fa-credit-card" style="color:#3B82F6;"></i>
                                        @break
                                    @case('loyalty_points')
                                        <i class="fa fa-star" style="color:#F59E0B;"></i>
                                        @break
                                    @case('promotion')
                                        <i class="fa fa-gift" style="color:#EF4444;"></i>
                                        @break
                                    @default
                                        <i class="fa fa-bell" style="color:var(--primary);"></i>
                                @endswitch
                            </div>
                            <div class="notif-card-body">
                                <div class="notif-card-top">
                                    <h6>{{ $notification->title }}
                                        @if(!$notification->read)
                                            <span class="notif-badge-new">Nouveau</span>
                                        @endif
                                    </h6>
                                    <span class="notif-time">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p>{{ $notification->message }}</p>
                            </div>
                            @if(!$notification->read)
                            <form action="{{ route('client.notifications.markRead', $notification) }}" method="POST" class="notif-card-action">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="Marquer comme lu">
                                    <i class="fa fa-check"></i>
                                </button>
                            </form>
                            @endif
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

<style>
.notif-list { display: flex; flex-direction: column; gap: 10px; }

.notif-card {
    display: flex; align-items: flex-start; gap: 16px;
    background: white; border-radius: 16px; padding: 20px 24px;
    border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    transition: all 0.25s;
}
.notif-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,0.07); transform: translateY(-1px); }
.notif-card.notif-unread {
    background: linear-gradient(135deg, var(--primary-soft), rgba(255,255,255,0.9));
    border-left: 4px solid var(--primary);
}

.notif-card-icon {
    width: 46px; height: 46px; border-radius: 14px; flex-shrink: 0;
    background: rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: center;
    font-size: 20px;
}
.notif-card.notif-unread .notif-card-icon { background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }

.notif-card-body { flex: 1; min-width: 0; }
.notif-card-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 4px; }
.notif-card-body h6 { margin: 0; font-size: 15px; font-weight: 600; color: var(--dark); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.notif-card-body p { margin: 0; font-size: 14px; color: #6B7280; line-height: 1.5; }
.notif-time { font-size: 12px; color: #8E8E8E; white-space: nowrap; flex-shrink: 0; margin-top: 2px; }

.notif-badge-new {
    display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;
    background: linear-gradient(135deg, var(--primary), var(--dark)); color: white;
}

.notif-card-action { flex-shrink: 0; }
.notif-card-action button {
    width: 36px; height: 36px; border-radius: 10px; border: 2px solid rgba(0,0,0,0.08);
    background: white; color: var(--primary); font-size: 14px; cursor: pointer;
    display: flex; align-items: center; justify-content: center; transition: all 0.25s;
}
.notif-card-action button:hover {
    background: linear-gradient(135deg, #10b981, #059669); border-color: #10b981;
    color: white; transform: scale(1.1);
}

.dark-theme .notif-card { background: #252540; border-color: #333355; }
.dark-theme .notif-card.notif-unread { background: linear-gradient(135deg, #2a2555, #252540); border-left-color: var(--primary); }
.dark-theme .notif-card-body h6 { color: #E8E8E8; }
.dark-theme .notif-card-body p { color: #aaa; }
.dark-theme .notif-card-icon { background: #1e1e38; }
.dark-theme .notif-card-action button { background: #2a2a4a; border-color: #444; }

@media (max-width: 575px) {
    .notif-card { padding: 16px; gap: 12px; }
    .notif-card-top { flex-direction: column; gap: 4px; }
}
</style>
@endsection
