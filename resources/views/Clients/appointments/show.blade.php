{{--
    Vue : Détails d'un rendez-vous client
    Description : Affiche les informations complètes d'un rendez-vous : service, date, heure, prix, spécialiste, notes, informations de paiement associé et actions disponibles (modifier, annuler, payer).
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid" style="max-width:1400px;margin:0 auto;padding:0 24px;">

        {{-- PAGE HEADER --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-eye"></i></div>
                <div>
                    <h2 class="beauty-page-title">Détails du rendez-vous</h2>
                    <p class="beauty-page-subtitle">Informations complètes de votre rendez-vous</p>
                </div>
            </div>
            <a href="{{ route('client.appointments.index') }}" class="btn-beauty-outline">
                <i class="fa fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        @include('partials.success')
        @include('partials.error')

        <div class="row">
            <div class="col-lg-8">
                {{-- Main Info Card --}}
                <div class="beauty-card appt-show-card">
                    {{-- Header gradient --}}
                    <div class="appt-show-gradient">
                        <div class="appt-show-gradient-inner">
                            <div>
                                <h4 class="appt-show-service-name">{{ $appointment->service->name ?? '?' }}</h4>
                                <p class="appt-show-service-desc">{{ $appointment->service->description ?? '' }}</p>
                            </div>
                            <div class="appt-show-status">{!! $appointment->status_badge !!}</div>
                        </div>
                    </div>

                    <div class="appt-show-details">
                        <div class="row">
                            <div class="col-sm-6 col-md-3 mb-4">
                                <div class="appt-detail-item">
                                    <div class="appt-detail-icon"><i class="fa fa-calendar"></i></div>
                                    <span class="appt-detail-label">Date</span>
                                    <strong class="appt-detail-value">{{ $appointment->date->translatedFormat('d M Y') }}</strong>
                                    <small class="appt-detail-sub">{{ $appointment->date->translatedFormat('l') }}</small>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-4">
                                <div class="appt-detail-item">
                                    <div class="appt-detail-icon"><i class="fa fa-clock-o"></i></div>
                                    <span class="appt-detail-label">Heure</span>
                                    <strong class="appt-detail-value appt-detail-value-lg">{{ $appointment->time }}</strong>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-4">
                                <div class="appt-detail-item">
                                    <div class="appt-detail-icon"><i class="fa fa-money"></i></div>
                                    <span class="appt-detail-label">Prix</span>
                                    <strong class="appt-detail-value appt-detail-value-primary">{{ number_format($appointment->service->getCurrentPrice(), 0, ',', ' ') }} FCFA</strong>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-4">
                                <div class="appt-detail-item">
                                    <div class="appt-detail-icon"><i class="fa fa-user"></i></div>
                                    <span class="appt-detail-label">Spécialiste</span>
                                    <strong class="appt-detail-value">{{ $appointment->employee->name ?? 'Non assigné' }}</strong>
                                </div>
                            </div>
                        </div>

                        @if($appointment->notes)
                        <div class="appt-notes-box">
                            <h6 class="appt-notes-title">
                                <i class="fa fa-sticky-note mr-2"></i>Notes
                            </h6>
                            <p class="appt-notes-text">{{ $appointment->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Payment Card --}}
                @if($appointment->payment)
                <div class="beauty-card appt-show-card appt-payment-card">
                    <div class="appt-payment-gradient">
                        <i class="fa fa-credit-card appt-payment-gradient-icon"></i>
                        <h5 class="appt-payment-gradient-title">Paiement</h5>
                    </div>
                    <div class="appt-payment-body">
                        <div class="row align-items-center">
                            <div class="col-md-4 mb-2">
                                <span class="appt-payment-label">Montant</span>
                                <strong class="appt-payment-amount">{{ number_format($appointment->payment->amount, 0, ',', ' ') }} FCFA</strong>
                            </div>
                            <div class="col-md-4 mb-2">
                                <span class="appt-payment-label">Méthode</span>
                                <strong class="appt-payment-method">{{ ucfirst($appointment->payment->method) }}</strong>
                            </div>
                            <div class="col-md-4 mb-2">
                                <span class="appt-payment-label">Statut</span>
                                <div class="appt-payment-status">{!! $appointment->payment->status_badge !!}</div>
                            </div>
                        </div>
                        @if(in_array($appointment->payment->status, ['paid', 'completed']))
                        <a href="{{ route('client.payments.invoice', $appointment->payment) }}" class="appt-btn appt-btn-invoice">
                            <i class="fa fa-download mr-2"></i>Télécharger la facture
                        </a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                {{-- Actions --}}
                <div class="beauty-card appt-show-card appt-actions-card">
                    <div class="appt-actions-body">
                        <h5 class="appt-actions-title"><i class="fa fa-bolt mr-2 appt-actions-icon"></i>Actions</h5>
                        @if(!in_array($appointment->status->value, ['completed', 'canceled']))
                            <a href="{{ route('client.appointments.edit', $appointment) }}" class="appt-btn appt-btn-edit">
                                <i class="fa fa-edit mr-2"></i>Modifier
                            </a>
                            <form action="{{ route('client.appointments.cancel', $appointment) }}" method="POST" class="confirm-delete" data-confirm-message="Êtes-vous sûr de vouloir annuler ce rendez-vous ?">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="appt-btn appt-btn-cancel">
                                    <i class="fa fa-times mr-2"></i>Annuler le rendez-vous
                                </button>
                            </form>
                        @endif

                        @if($appointment->status->value == 'completed' && !$appointment->payment)
                            <a href="{{ route('client.payments.create', ['appointment' => $appointment->id]) }}" class="appt-btn appt-btn-pay">
                                <i class="fa fa-credit-card mr-2"></i>Payer maintenant
                            </a>
                        @endif

                        @if(in_array($appointment->status->value, ['completed', 'canceled']))
                            <div class="appt-completed-state">
                                <i class="fa fa-check-circle appt-completed-icon"></i>
                                <p class="appt-completed-text">Ce rendez-vous est terminé</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Help Card --}}
                <div class="beauty-card appt-show-card appt-help-card">
                    <div class="appt-help-body">
                        <div class="appt-help-icon-wrap">
                            <i class="fa fa-phone appt-help-icon"></i>
                        </div>
                        <h5 class="appt-help-title">Besoin d'aide ?</h5>
                        <p class="appt-help-subtitle">Contactez-nous</p>
                        <p class="appt-help-phone">+223 XX XX XX XX</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ─── APPOINTMENT SHOW: CARD BASE ─── */
.appt-show-card {
    margin-bottom: 20px;
}

/* ─── GRADIENT HEADER ─── */
.appt-show-gradient {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    padding: 28px 30px;
    color: white;
}
.appt-show-gradient-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.appt-show-service-name {
    font-family: 'Playfair Display', serif;
    margin: 0 0 4px;
    color: white;
    font-size: 22px;
    font-weight: 700;
}
.appt-show-service-desc {
    margin: 0;
    opacity: 0.8;
    font-size: 14px;
    color: rgba(255, 255, 255, 0.85);
}
.appt-show-status {
    flex-shrink: 0;
}

/* ─── DETAIL ITEMS ─── */
.appt-show-details {
    padding: 30px;
}
.appt-detail-item {
    text-align: center;
    padding: 16px 12px;
    border-radius: 14px;
    transition: all 0.3s;
}
.appt-detail-item:hover {
    background: var(--primary-soft);
    transform: translateY(-2px);
}
.appt-detail-icon {
    width: 50px;
    height: 50px;
    border-radius: 14px;
    background: var(--primary-soft);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    color: var(--primary);
    font-size: 19px;
    transition: all 0.3s;
}
.appt-detail-item:hover .appt-detail-icon {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}
.appt-detail-label {
    display: block;
    font-size: 11px;
    color: #8E8E8E;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    font-weight: 600;
    margin-bottom: 6px;
}
.appt-detail-value {
    display: block;
    font-size: 16px;
    color: var(--dark);
    font-family: 'Poppins', sans-serif;
}
.appt-detail-value-lg {
    font-size: 22px;
}
.appt-detail-value-primary {
    color: var(--primary);
}
.appt-detail-sub {
    display: block;
    font-size: 12px;
    color: #8E8E8E;
    margin-top: 2px;
}

/* ─── NOTES BOX ─── */
.appt-notes-box {
    background: var(--primary-soft);
    border-radius: 14px;
    padding: 20px 22px;
    margin-top: 8px;
}
.appt-notes-title {
    margin: 0 0 8px;
    font-size: 12px;
    color: var(--primary);
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}
.appt-notes-text {
    margin: 0;
    font-size: 14px;
    color: var(--dark);
    line-height: 1.6;
}

/* ─── PAYMENT CARD ─── */
.appt-payment-gradient {
    background: linear-gradient(135deg, #10b981, #059669);
    padding: 20px 30px;
    color: white;
    display: flex;
    align-items: center;
    gap: 12px;
}
.appt-payment-gradient-icon {
    font-size: 20px;
}
.appt-payment-gradient-title {
    margin: 0;
    font-family: 'Playfair Display', serif;
    color: white;
    font-size: 18px;
}
.appt-payment-body {
    padding: 26px 30px;
}
.appt-payment-label {
    display: block;
    font-size: 12px;
    color: #8E8E8E;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-bottom: 4px;
}
.appt-payment-amount {
    display: block;
    font-size: 20px;
    color: var(--primary);
    font-family: 'Playfair Display', serif;
}
.appt-payment-method {
    display: block;
    font-size: 15px;
    color: var(--dark);
}
.appt-payment-status {
    margin-top: 2px;
}

/* ─── PILL BUTTONS ─── */
.appt-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: 13px 24px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none !important;
    margin-bottom: 10px;
    font-family: 'Poppins', sans-serif;
    letter-spacing: 0.3px;
}
.appt-btn:hover {
    transform: translateY(-2px);
}
.appt-btn:last-child {
    margin-bottom: 0;
}
.appt-btn-edit {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
}
.appt-btn-edit:hover {
    box-shadow: 0 6px 22px rgba(245, 158, 11, 0.4);
    color: white;
}
.appt-btn-cancel {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}
.appt-btn-cancel:hover {
    box-shadow: 0 6px 22px rgba(239, 68, 68, 0.4);
    color: white;
}
.appt-btn-pay {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}
.appt-btn-pay:hover {
    box-shadow: 0 6px 22px rgba(16, 185, 129, 0.4);
    color: white;
}
.appt-btn-invoice {
    display: inline-flex;
    width: auto;
    margin-top: 16px;
    background: var(--primary-soft);
    color: var(--primary);
    padding: 11px 24px;
    border-radius: 50px;
    box-shadow: none;
}
.appt-btn-invoice:hover {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.15);
}

/* ─── ACTIONS CARD ─── */
.appt-actions-body {
    padding: 26px;
}
.appt-actions-title {
    font-family: 'Playfair Display', serif;
    margin: 0 0 20px;
    color: var(--dark);
    font-size: 18px;
}
.appt-actions-icon {
    color: var(--primary);
}

/* ─── COMPLETED STATE ─── */
.appt-completed-state {
    text-align: center;
    padding: 24px 0;
}
.appt-completed-icon {
    font-size: 32px;
    display: block;
    margin-bottom: 10px;
    color: var(--primary);
    opacity: 0.3;
}
.appt-completed-text {
    margin: 0;
    font-size: 13px;
    color: #8E8E8E;
}

/* ─── HELP CARD ─── */
.appt-help-body {
    padding: 30px 26px;
    text-align: center;
}
.appt-help-icon-wrap {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    background: var(--primary-soft);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    transition: all 0.3s;
}
.appt-help-icon-wrap:hover {
    background: linear-gradient(135deg, var(--primary), var(--dark));
}
.appt-help-icon-wrap:hover .appt-help-icon {
    color: white;
}
.appt-help-icon {
    color: var(--primary);
    font-size: 22px;
    transition: color 0.3s;
}
.appt-help-title {
    font-family: 'Playfair Display', serif;
    color: var(--dark);
    margin-bottom: 6px;
    font-size: 18px;
}
.appt-help-subtitle {
    color: #8E8E8E;
    font-size: 13px;
    margin-bottom: 12px;
}
.appt-help-phone {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary);
    margin: 0;
    font-family: 'Poppins', sans-serif;
}

/* ─── OUTLINE BUTTON ─── */
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
.dark-theme .appt-detail-value,
.dark-theme .appt-notes-text,
.dark-theme .appt-payment-method,
.dark-theme .appt-actions-title,
.dark-theme .appt-help-title {
    color: #E8E8E8;
}
.dark-theme .appt-detail-icon {
    background: rgba(255, 255, 255, 0.08);
}
.dark-theme .appt-detail-item:hover {
    background: rgba(255, 255, 255, 0.04);
}
.dark-theme .appt-detail-item:hover .appt-detail-icon {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white;
}
.dark-theme .appt-detail-label,
.dark-theme .appt-detail-sub,
.dark-theme .appt-payment-label,
.dark-theme .appt-help-subtitle,
.dark-theme .appt-completed-text {
    color: #9E9E9E;
}
.dark-theme .appt-detail-value-primary {
    color: var(--primary-light);
}
.dark-theme .appt-notes-box {
    background: rgba(255, 255, 255, 0.06);
}
.dark-theme .appt-notes-title {
    color: var(--primary-light);
}
.dark-theme .appt-notes-text {
    color: #D0D0D0;
}
.dark-theme .appt-payment-amount {
    color: var(--primary-light);
}
.dark-theme .appt-help-icon-wrap {
    background: rgba(255, 255, 255, 0.08);
}
.dark-theme .appt-help-phone {
    color: var(--primary-light);
}
.dark-theme .appt-completed-icon {
    color: var(--primary-light);
}
.dark-theme .appt-btn-invoice {
    background: rgba(255, 255, 255, 0.08);
    color: var(--primary-light);
}
.dark-theme .appt-btn-invoice:hover {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white;
}
.dark-theme .appt-show-service-name {
    color: white;
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
    .appt-show-gradient {
        padding: 22px 20px;
    }
    .appt-show-service-name {
        font-size: 18px;
    }
    .appt-show-details {
        padding: 20px;
    }
    .appt-payment-body {
        padding: 20px;
    }
    .appt-actions-body {
        padding: 20px;
    }
    .appt-help-body {
        padding: 24px 20px;
    }
    .appt-show-gradient-inner {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endsection
