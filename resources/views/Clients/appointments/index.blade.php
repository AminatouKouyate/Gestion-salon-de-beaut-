{{--
    Vue : Liste des rendez-vous du client
    Description : Page principale des rendez-vous : statistiques rapides (à venir, en attente, terminés), cartes de rendez-vous avec actions (voir, modifier, annuler, payer) et lien vers l'historique.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid" style="max-width:1400px;margin:0 auto;padding:0 24px;">

        {{-- PAGE HEADER --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-calendar"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mes Rendez-vous</h2>
                    <p class="beauty-page-subtitle">Gérez vos rendez-vous au salon</p>
                </div>
            </div>
            <a href="{{ route('client.appointments.create') }}" class="btn-beauty-primary">
                <i class="fa fa-plus mr-2"></i>Prendre RDV
            </a>
        </div>

        @include('partials.success')
        @include('partials.error')

        {{-- QUICK STATS --}}
        @php
            $countUpcoming = $appointments->filter(fn($a) => in_array($a->status->value ?? $a->status, ['pending', 'confirmed']))->count();
            $countPending = $appointments->filter(fn($a) => ($a->status->value ?? $a->status) === 'pending')->count();
            $countCompleted = $appointments->filter(fn($a) => ($a->status->value ?? $a->status) === 'completed')->count();
        @endphp
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="rdv-stat-card">
                    <div class="rdv-stat-icon rdv-stat-icon-gold"><i class="fa fa-calendar-check-o"></i></div>
                    <div class="rdv-stat-info">
                        <h3>{{ $countUpcoming }}</h3>
                        <p>À venir</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="rdv-stat-card">
                    <div class="rdv-stat-icon rdv-stat-icon-rose"><i class="fa fa-clock-o"></i></div>
                    <div class="rdv-stat-info">
                        <h3>{{ $countPending }}</h3>
                        <p>En attente</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="rdv-stat-card">
                    <div class="rdv-stat-icon rdv-stat-icon-plum"><i class="fa fa-check-circle"></i></div>
                    <div class="rdv-stat-info">
                        <h3>{{ $countCompleted }}</h3>
                        <p>Terminés</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- APPOINTMENT CARDS --}}
        @if($appointments->isEmpty())
            <div class="rdv-empty-state">
                <div class="rdv-empty-icon"></div>
                <h4>Aucun rendez-vous à venir</h4>
                <p>Vous n'avez pas encore de rendez-vous planifié.<br>Offrez-vous un moment de beauté !</p>
                <a href="{{ route('client.appointments.create') }}" class="btn-beauty-primary">
                    <i class="fa fa-calendar-plus-o mr-2"></i>Prendre un rendez-vous
                </a>
            </div>
        @else
            <div class="rdv-cards-grid">
                @foreach($appointments as $appointment)
                    @php
                        $status = $appointment->status->value ?? $appointment->status;
                        $isPending = in_array($status, ['pending', 'confirmed']);
                        $isCompleted = $status === 'completed';
                        $hasPayment = $appointment->payment !== null;

                        $accentClass = match($status) {
                            'pending' => 'rdv-accent-gold',
                            'confirmed' => 'rdv-accent-green',
                            'canceled' => 'rdv-accent-red',
                            'completed' => 'rdv-accent-plum',
                            default => 'rdv-accent-gold',
                        };
                    @endphp
                    <div class="rdv-card {{ $accentClass }}">
                        <div class="rdv-card-inner">
                            {{-- Date badge --}}
                            <div class="rdv-date-badge">
                                <span class="rdv-date-day">{{ $appointment->date->format('d') }}</span>
                                <span class="rdv-date-month">{{ $appointment->date->translatedFormat('M') }}</span>
                            </div>

                            {{-- Details --}}
                            <div class="rdv-card-details">
                                <h5 class="rdv-service-name">{{ $appointment->service->name ?? '—' }}</h5>
                                <div class="rdv-card-meta">
                                    <span><i class="fa fa-clock-o"></i> {{ $appointment->time }}</span>
                                    <span><i class="fa fa-user"></i> {{ $appointment->employee->name ?? 'Non assigné' }}</span>
                                </div>
                                <div class="rdv-card-price">
                                    {{ number_format($appointment->service->price ?? 0, 0, ',', ' ') }} FCFA
                                </div>
                            </div>

                            {{-- Status & Actions --}}
                            <div class="rdv-card-right">
                                <div class="rdv-card-status">{!! $appointment->status_badge !!}</div>
                                <div class="rdv-card-actions">
                                    <a href="{{ route('client.appointments.show', $appointment) }}" class="rdv-action-btn rdv-action-view" title="Voir">
                                        <i class="fa fa-eye"></i>
                                    </a>

                                    @if($isPending)
                                        <a href="{{ route('client.appointments.edit', $appointment) }}" class="rdv-action-btn rdv-action-edit" title="Modifier">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('client.appointments.cancel', $appointment) }}" method="POST" class="d-inline confirm-delete" data-confirm-message="Êtes-vous sûr de vouloir annuler ce rendez-vous ?">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rdv-action-btn rdv-action-cancel" title="Annuler">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($isCompleted && !$hasPayment)
                                        <a href="{{ route('client.payments.create', ['appointment' => $appointment->id]) }}" class="rdv-action-btn rdv-action-pay" title="Payer">
                                            <i class="fa fa-credit-card"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="rdv-pagination">
                {{ $appointments->links() }}
            </div>
        @endif

        {{-- HISTORY LINK --}}
        <div class="rdv-history-link">
            <a href="{{ route('client.appointments.history') }}" class="btn-beauty-outline">
                <i class="fa fa-history mr-2"></i>Voir l'historique complet
            </a>
        </div>
    </div>
</div>

<style>
/* ─── STAT CARDS ─── */
.rdv-stat-card {
    background: white;
    border-radius: 18px;
    padding: 22px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    transition: all 0.3s;
}
.rdv-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}
.rdv-stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.rdv-stat-icon-gold { background: var(--primary-soft); color: var(--accent); }
.rdv-stat-icon-rose { background: var(--primary-soft); color: var(--primary); }
.rdv-stat-icon-plum { background: var(--primary-soft); color: var(--dark); }
.rdv-stat-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    margin: 0;
    color: var(--dark);
    line-height: 1;
}
.rdv-stat-info p {
    font-size: 13px;
    color: #8E8E8E;
    margin: 4px 0 0;
}

/* ─── APPOINTMENT CARDS GRID ─── */
.rdv-cards-grid {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.rdv-card {
    background: white;
    border-radius: 18px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    overflow: hidden;
    transition: all 0.3s ease;
    border-left: 5px solid transparent;
}
.rdv-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
}

/* Accent colors */
.rdv-accent-gold  { border-left-color: var(--accent); }
.rdv-accent-green { border-left-color: #28a745; }
.rdv-accent-red   { border-left-color: #E74C5F; }
.rdv-accent-plum  { border-left-color: var(--dark); }

.rdv-card-inner {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 24px;
}

/* ─── DATE BADGE ─── */
.rdv-date-badge {
    width: 60px;
    min-width: 60px;
    height: 66px;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
    flex-shrink: 0;
}
.rdv-date-day {
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
}
.rdv-date-month {
    font-size: 12px;
    text-transform: uppercase;
    opacity: 0.85;
    font-weight: 600;
}

/* ─── CARD DETAILS ─── */
.rdv-card-details {
    flex: 1;
    min-width: 0;
}
.rdv-service-name {
    font-family: 'Playfair Display', serif;
    font-size: 17px;
    font-weight: 600;
    color: var(--dark);
    margin: 0 0 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.rdv-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    font-size: 13px;
    color: #8E8E8E;
    margin-bottom: 6px;
}
.rdv-card-meta i {
    margin-right: 4px;
    color: var(--primary);
}
.rdv-card-price {
    font-size: 15px;
    font-weight: 700;
    color: var(--primary);
}

/* ─── RIGHT SECTION ─── */
.rdv-card-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
    flex-shrink: 0;
}
.rdv-card-status {
    white-space: nowrap;
}

/* ─── ACTION BUTTONS ─── */
.rdv-card-actions {
    display: flex;
    gap: 8px;
}
.rdv-action-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.25s;
    text-decoration: none !important;
}
.rdv-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.rdv-action-view {
    background: #E8F4FD;
    color: #3498db;
}
.rdv-action-view:hover { background: #3498db; color: white; }
.rdv-action-edit {
    background: #FFF3D6;
    color: var(--accent);
}
.rdv-action-edit:hover { background: var(--accent); color: white; }
.rdv-action-cancel {
    background: #FFE8EC;
    color: #E74C5F;
}
.rdv-action-cancel:hover { background: #E74C5F; color: white; }
.rdv-action-pay {
    background: #E8F5E9;
    color: #28a745;
}
.rdv-action-pay:hover { background: #28a745; color: white; }

/* ─── EMPTY STATE ─── */
.rdv-empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 18px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.rdv-empty-icon {
    font-size: 72px;
    margin-bottom: 16px;
    line-height: 1;
}
.rdv-empty-state h4 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    color: var(--dark);
    margin-bottom: 8px;
}
.rdv-empty-state p {
    font-size: 15px;
    color: #8E8E8E;
    margin-bottom: 24px;
    line-height: 1.6;
}

/* ─── PAGINATION ─── */
.rdv-pagination {
    margin-top: 24px;
    display: flex;
    justify-content: center;
}

/* ─── HISTORY LINK ─── */
.rdv-history-link {
    margin-top: 24px;
    padding-bottom: 24px;
    text-align: center;
}

/* ─── PRIMARY BUTTON (local fallback) ─── */
.btn-beauty-primary {
    display: inline-flex;
    align-items: center;
    padding: 12px 28px;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white;
    border: none;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.3s;
    cursor: pointer;
    box-shadow: 0 4px 18px rgba(0,0,0,0.15);
}
.btn-beauty-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(0,0,0,0.2);
    color: white;
}

/* ─── OUTLINE BUTTON (local fallback) ─── */
.btn-beauty-outline {
    display: inline-flex;
    align-items: center;
    padding: 12px 28px;
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
    border-radius: 14px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.3s;
}
.btn-beauty-outline:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

/* ─── DARK MODE ─── */
.dark-theme .rdv-stat-card,
.dark-theme .rdv-card,
.dark-theme .rdv-empty-state {
    background: #252540;
    border-color: #333355;
}
.dark-theme .rdv-stat-info h3,
.dark-theme .rdv-service-name,
.dark-theme .rdv-empty-state h4 {
    color: #E8E8E8;
}
.dark-theme .rdv-stat-info p,
.dark-theme .rdv-empty-state p {
    color: #aaa;
}
.dark-theme .rdv-stat-icon-gold,
.dark-theme .rdv-stat-icon-rose,
.dark-theme .rdv-stat-icon-plum {
    background: rgba(255,255,255,0.08);
}
.dark-theme .rdv-card-price {
    color: var(--primary-light);
}
.dark-theme .rdv-card-meta {
    color: #aaa;
}
.dark-theme .rdv-action-view {
    background: rgba(52,152,219,0.15);
    color: #5dade2;
}
.dark-theme .rdv-action-edit {
    background: rgba(212,175,55,0.15);
    color: #f0d060;
}
.dark-theme .rdv-action-cancel {
    background: rgba(231,76,95,0.15);
    color: #f08090;
}
.dark-theme .rdv-action-pay {
    background: rgba(40,167,69,0.15);
    color: #5cdb95;
}
.dark-theme .btn-beauty-outline {
    color: var(--primary-light);
    border-color: var(--primary-light);
}
.dark-theme .btn-beauty-outline:hover {
    background: var(--primary);
    color: white;
}

/* ─── RESPONSIVE ─── */
@media (max-width: 768px) {
    .rdv-card-inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        padding: 18px;
    }
    .rdv-date-badge {
        width: 100%;
        height: auto;
        flex-direction: row;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 12px;
    }
    .rdv-date-day { font-size: 20px; }
    .rdv-date-month { font-size: 13px; }
    .rdv-card-right {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    .rdv-service-name {
        white-space: normal;
    }
}
</style>
@endsection
