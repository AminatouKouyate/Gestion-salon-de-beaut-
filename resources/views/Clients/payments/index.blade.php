{{--
    Vue : Liste des paiements du client
    Description : Historique complet des paiements avec statistiques (total, montant payé, en attente), tableau responsive avec vue desktop et cartes mobiles, actions (voir, facture, télécharger PDF, payer).
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid" style="max-width:1400px;margin:0 auto;padding:0 24px;">

        {{-- PAGE HEADER --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-credit-card"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mes Paiements</h2>
                    <p class="beauty-page-subtitle">Historique de vos paiements</p>
                </div>
            </div>
        </div>

        @include('partials.success')
        @include('partials.error')

        {{-- QUICK STATS --}}
        @php
            $totalPayments = $payments->total();
            $totalPaid = $payments->getCollection()->whereIn('status', ['paid', 'completed'])->sum('amount');
            $pendingCount = $payments->getCollection()->whereNotIn('status', ['paid', 'completed'])->count();
        @endphp
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="pay-stat-card">
                    <div class="pay-stat-icon pay-stat-icon-rose"><i class="fa fa-credit-card"></i></div>
                    <div class="pay-stat-info">
                        <h3>{{ $totalPayments }}</h3>
                        <p>Total paiements</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="pay-stat-card">
                    <div class="pay-stat-icon pay-stat-icon-green"><i class="fa fa-check-circle"></i></div>
                    <div class="pay-stat-info">
                        <h3>{{ number_format($totalPaid, 0, ',', ' ') }} <small class="pay-stat-currency">FCFA</small></h3>
                        <p>Montant total payé</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="pay-stat-card">
                    <div class="pay-stat-icon pay-stat-icon-gold"><i class="fa fa-clock-o"></i></div>
                    <div class="pay-stat-info">
                        <h3>{{ $pendingCount }}</h3>
                        <p>Paiements en attente</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- PAYMENTS LIST --}}
        @if($payments->isEmpty())
            <div class="pay-empty-state">
                <div class="pay-empty-icon">💳</div>
                <h4>Aucun paiement enregistré</h4>
                <p>Vous n'avez pas encore effectué de paiement.<br>Prenez rendez-vous pour commencer !</p>
                <a href="{{ route('client.appointments.create') }}" class="btn-beauty-primary">
                    <i class="fa fa-calendar-plus-o mr-2"></i>Prendre un rendez-vous
                </a>
            </div>
        @else
            {{-- DESKTOP TABLE --}}
            <div class="pay-table-card">
                <div class="pay-table-header">
                    <h4><i class="fa fa-list-ul"></i> Historique des paiements</h4>
                </div>
                <div class="pay-table-wrap">
                    <table class="pay-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Service</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>
                                    <div class="pay-date-cell">
                                        <div class="pay-date-badge">
                                            <span class="pay-date-day">{{ $payment->created_at->format('d') }}</span>
                                            <span class="pay-date-month">{{ $payment->created_at->translatedFormat('M') }}</span>
                                        </div>
                                        <span class="pay-date-year">{{ $payment->created_at->format('Y') }}</span>
                                    </div>
                                </td>
                                <td><span class="pay-service-name">{{ $payment->appointment->service->name ?? '—' }}</span></td>
                                <td>
                                    <span class="pay-amount">{{ number_format($payment->amount, 0, ',', ' ') }}<span class="pay-currency">FCFA</span></span>
                                </td>
                                <td>
                                    <span class="pay-method-badge">
                                        @switch($payment->method)
                                            @case('stripe')
                                                <i class="fa fa-cc-stripe text-primary"></i> Stripe
                                                @break
                                            @case('paypal')
                                                <i class="fa fa-paypal text-info"></i> PayPal
                                                @break
                                            @case('cash')
                                                <i class="fa fa-money text-success"></i> Espèces
                                                @break
                                            @case('salon')
                                                <i class="fa fa-building text-secondary"></i> Salon
                                                @break
                                        @endswitch
                                    </span>
                                </td>
                                <td>{!! $payment->status_badge !!}</td>
                                <td>
                                    <div class="pay-actions">
                                        <a href="{{ route('client.payments.show', $payment) }}" class="pay-action-btn pay-action-view" title="Voir détails">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if(in_array($payment->status, ['paid', 'completed']))
                                            <a href="{{ route('client.payments.invoice', $payment) }}" class="pay-action-btn pay-action-invoice" title="Voir facture">
                                                <i class="fa fa-file-text"></i>
                                            </a>
                                            <a href="{{ route('client.payments.invoice.download', $payment) }}" class="pay-action-btn pay-action-download" title="Télécharger PDF">
                                                <i class="fa fa-download"></i>
                                            </a>
                                        @else
                                            @if($payment->appointment)
                                                <a href="{{ route('client.payments.create', ['appointment' => $payment->appointment->id]) }}" class="pay-action-btn pay-action-pay" title="Payer maintenant">
                                                    <i class="fa fa-credit-card"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- MOBILE CARDS --}}
            <div class="pay-cards-mobile">
                @foreach($payments as $payment)
                    @php
                        $isPaid = in_array($payment->status, ['paid', 'completed']);
                        $accentClass = $isPaid ? 'pay-accent-green' : 'pay-accent-gold';
                    @endphp
                    <div class="pay-card {{ $accentClass }}">
                        <div class="pay-card-inner">
                            {{-- Date badge --}}
                            <div class="pay-card-date-badge">
                                <span class="pay-date-day">{{ $payment->created_at->format('d') }}</span>
                                <span class="pay-date-month">{{ $payment->created_at->translatedFormat('M') }}</span>
                            </div>

                            {{-- Details --}}
                            <div class="pay-card-details">
                                <h5 class="pay-card-service">{{ $payment->appointment->service->name ?? '—' }}</h5>
                                <div class="pay-card-meta">
                                    <span>
                                        @switch($payment->method)
                                            @case('stripe')
                                                <i class="fa fa-cc-stripe"></i> Stripe
                                                @break
                                            @case('paypal')
                                                <i class="fa fa-paypal"></i> PayPal
                                                @break
                                            @case('cash')
                                                <i class="fa fa-money"></i> Espèces
                                                @break
                                            @case('salon')
                                                <i class="fa fa-building"></i> Salon
                                                @break
                                        @endswitch
                                    </span>
                                    <span><i class="fa fa-calendar"></i> {{ $payment->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="pay-card-price">
                                    {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                                </div>
                            </div>

                            {{-- Status & Actions --}}
                            <div class="pay-card-right">
                                <div class="pay-card-status">{!! $payment->status_badge !!}</div>
                                <div class="pay-card-actions">
                                    <a href="{{ route('client.payments.show', $payment) }}" class="pay-action-btn pay-action-view" title="Voir détails">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if($isPaid)
                                        <a href="{{ route('client.payments.invoice', $payment) }}" class="pay-action-btn pay-action-invoice" title="Voir facture">
                                            <i class="fa fa-file-text"></i>
                                        </a>
                                        <a href="{{ route('client.payments.invoice.download', $payment) }}" class="pay-action-btn pay-action-download" title="Télécharger PDF">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    @else
                                        @if($payment->appointment)
                                            <a href="{{ route('client.payments.create', ['appointment' => $payment->appointment->id]) }}" class="pay-action-btn pay-action-pay" title="Payer maintenant">
                                                <i class="fa fa-credit-card"></i>
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pay-pagination">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>

<style>
/* ─── STAT CARDS ─── */
.pay-stat-card {
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
.pay-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
}
.pay-stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}
.pay-stat-icon-rose { background: var(--primary-soft); color: var(--primary); }
.pay-stat-icon-green { background: rgba(40,167,69,0.1); color: #28a745; }
.pay-stat-icon-gold { background: var(--primary-soft); color: var(--accent); }
.pay-stat-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    margin: 0;
    color: var(--dark);
    line-height: 1;
}
.pay-stat-info p {
    font-size: 13px;
    color: #8E8E8E;
    margin: 4px 0 0;
}
.pay-stat-currency {
    font-size: 13px;
    font-weight: 400;
    color: #8E8E8E;
    font-family: 'Poppins', sans-serif;
}

/* ─── TABLE CARD WRAPPER ─── */
.pay-table-card {
    background: white;
    border-radius: 18px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    overflow: hidden;
}
.pay-table-header {
    padding: 20px 24px;
    border-bottom: 1px solid rgba(0,0,0,0.06);
}
.pay-table-header h4 {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}
.pay-table-header h4 i {
    margin-right: 10px;
    opacity: 0.5;
}

/* ─── TABLE ─── */
.pay-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.pay-table thead th {
    padding: 14px 20px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #8E8E8E;
    font-weight: 600;
    border-bottom: 2px solid rgba(0,0,0,0.05);
    white-space: nowrap;
}
.pay-table tbody tr {
    transition: all 0.25s ease;
}
.pay-table tbody tr:hover {
    background: var(--primary-soft);
}
.pay-table tbody td {
    padding: 18px 20px;
    vertical-align: middle;
    border-bottom: 1px solid rgba(0,0,0,0.04);
}
.pay-table tbody tr:last-child td {
    border-bottom: none;
}

/* ─── DATE CELL (table) ─── */
.pay-date-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}
.pay-date-badge {
    width: 52px;
    min-width: 52px;
    height: 56px;
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
.pay-date-day {
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
}
.pay-date-month {
    font-size: 11px;
    text-transform: uppercase;
    opacity: 0.85;
    font-weight: 600;
}
.pay-date-year {
    font-size: 13px;
    color: #8E8E8E;
}

/* ─── TABLE CELLS ─── */
.pay-service-name {
    font-family: 'Playfair Display', serif;
    font-weight: 600;
    color: var(--dark);
    font-size: 15px;
}
.pay-amount {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--dark);
}
.pay-currency {
    font-size: 12px;
    font-weight: 400;
    color: #8E8E8E;
    margin-left: 3px;
    font-family: 'Poppins', sans-serif;
}
.pay-method-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 500;
    background: rgba(0,0,0,0.04);
}
.pay-method-badge i {
    font-size: 16px;
}

/* ─── ACTION BUTTONS (shared desktop + mobile) ─── */
.pay-actions,
.pay-card-actions {
    display: flex;
    gap: 8px;
}
.pay-action-btn {
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
.pay-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.pay-action-view {
    background: #E8F4FD;
    color: #3498db;
}
.pay-action-view:hover { background: #3498db; color: white; }
.pay-action-invoice {
    background: #F3E8FF;
    color: #7c3aed;
}
.pay-action-invoice:hover { background: #7c3aed; color: white; }
.pay-action-download {
    background: var(--primary-soft);
    color: var(--primary);
}
.pay-action-download:hover { background: var(--primary); color: white; }
.pay-action-pay {
    background: #E8F5E9;
    color: #28a745;
}
.pay-action-pay:hover { background: #28a745; color: white; }

/* ─── MOBILE CARDS ─── */
.pay-cards-mobile {
    display: none;
    flex-direction: column;
    gap: 16px;
}
.pay-card {
    background: white;
    border-radius: 18px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    overflow: hidden;
    transition: all 0.3s ease;
    border-left: 5px solid transparent;
}
.pay-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
}

/* Accent colors */
.pay-accent-green { border-left-color: #28a745; }
.pay-accent-gold  { border-left-color: var(--accent); }

.pay-card-inner {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px 24px;
}
.pay-card-date-badge {
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
.pay-card-details {
    flex: 1;
    min-width: 0;
}
.pay-card-service {
    font-family: 'Playfair Display', serif;
    font-size: 17px;
    font-weight: 600;
    color: var(--dark);
    margin: 0 0 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.pay-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    font-size: 13px;
    color: #8E8E8E;
    margin-bottom: 6px;
}
.pay-card-meta i {
    margin-right: 4px;
    color: var(--primary);
}
.pay-card-price {
    font-size: 15px;
    font-weight: 700;
    color: var(--primary);
}
.pay-card-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
    flex-shrink: 0;
}
.pay-card-status {
    white-space: nowrap;
}

/* ─── EMPTY STATE ─── */
.pay-empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 18px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.pay-empty-icon {
    font-size: 72px;
    margin-bottom: 16px;
    line-height: 1;
}
.pay-empty-state h4 {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    color: var(--dark);
    margin-bottom: 8px;
}
.pay-empty-state p {
    font-size: 15px;
    color: #8E8E8E;
    margin-bottom: 24px;
    line-height: 1.6;
    max-width: 380px;
    margin-left: auto;
    margin-right: auto;
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

/* ─── PAGINATION ─── */
.pay-pagination {
    margin-top: 24px;
    display: flex;
    justify-content: center;
}

/* ─── RESPONSIVE ─── */
@media (max-width: 767px) {
    .pay-table-card { display: none; }
    .pay-cards-mobile { display: flex; }

    .pay-card-inner {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
        padding: 18px;
    }
    .pay-card-date-badge {
        width: 100%;
        height: auto;
        flex-direction: row;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 12px;
    }
    .pay-card-date-badge .pay-date-day { font-size: 20px; }
    .pay-card-date-badge .pay-date-month { font-size: 13px; }
    .pay-card-right {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }
    .pay-card-service {
        white-space: normal;
    }
}

/* ─── DARK MODE ─── */
.dark-theme .pay-stat-card,
.dark-theme .pay-table-card,
.dark-theme .pay-card,
.dark-theme .pay-empty-state {
    background: #252540;
    border-color: #333355;
}
.dark-theme .pay-stat-info h3,
.dark-theme .pay-service-name,
.dark-theme .pay-card-service,
.dark-theme .pay-empty-state h4 {
    color: #E8E8E8;
}
.dark-theme .pay-stat-info p,
.dark-theme .pay-stat-currency,
.dark-theme .pay-empty-state p {
    color: #aaa;
}
.dark-theme .pay-stat-icon-rose,
.dark-theme .pay-stat-icon-green,
.dark-theme .pay-stat-icon-gold {
    background: rgba(255,255,255,0.08);
}
.dark-theme .pay-table-header {
    border-bottom-color: #333355;
}
.dark-theme .pay-table-header h4 {
    color: #E8E8E8;
}
.dark-theme .pay-table thead th {
    color: #9E9E9E;
    border-bottom-color: #333355;
}
.dark-theme .pay-table tbody tr:hover {
    background: #2a2040;
}
.dark-theme .pay-table tbody td {
    border-bottom-color: #333355;
    color: #ccc;
}
.dark-theme .pay-amount,
.dark-theme .pay-card-price {
    color: var(--primary-light);
}
.dark-theme .pay-currency,
.dark-theme .pay-date-year {
    color: #9E9E9E;
}
.dark-theme .pay-method-badge {
    background: rgba(255,255,255,0.06);
    color: #ccc;
}
.dark-theme .pay-card-meta {
    color: #aaa;
}
.dark-theme .pay-action-view {
    background: rgba(52,152,219,0.15);
    color: #5dade2;
}
.dark-theme .pay-action-view:hover { background: #3498db; color: white; }
.dark-theme .pay-action-invoice {
    background: rgba(124,58,237,0.15);
    color: #c4b5fd;
}
.dark-theme .pay-action-invoice:hover { background: #7c3aed; color: white; }
.dark-theme .pay-action-download {
    background: rgba(255,255,255,0.08);
    color: var(--primary-light);
}
.dark-theme .pay-action-download:hover { background: var(--primary); color: white; }
.dark-theme .pay-action-pay {
    background: rgba(40,167,69,0.15);
    color: #5cdb95;
}
.dark-theme .pay-action-pay:hover { background: #28a745; color: white; }
</style>
@endsection
