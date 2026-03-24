{{--
    Vue : Détails d'un paiement client
    Description : Affiche les informations complètes d'un paiement : montant, méthode, date, service, spécialiste, transaction ID, rendez-vous associé et actions (payer, télécharger facture).
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-credit-card"></i></div>
                <div>
                    <h2 class="beauty-page-title">Détail du paiement</h2>
                    <p class="beauty-page-subtitle">Informations complètes de votre paiement</p>
                </div>
            </div>
            <a href="{{ route('client.payments.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,0.06);">
                    <div class="card-body" style="padding:0;">
                        <div style="background:linear-gradient(135deg,var(--primary),var(--dark));padding:24px 28px;color:white;">
                            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                                <div>
                                    <p style="margin:0 0 4px;opacity:0.8;font-size:13px;text-transform:uppercase;letter-spacing:1px;">Montant</p>
                                    <h3 style="font-family:'Playfair Display',serif;margin:0;color:white;font-size:32px;">{{ number_format($payment->amount, 0, ',', ' ') }} <span style="font-size:16px;opacity:0.8;">FCFA</span></h3>
                                </div>
                                <div>{!! $payment->status_badge !!}</div>
                            </div>
                        </div>

                        <div style="padding:28px;">
                            <div class="row">
                                <div class="col-sm-6 col-md-3 mb-4">
                                    <div class="detail-item">
                                        <div class="detail-icon"><i class="fa fa-credit-card"></i></div>
                                        <span class="detail-label">Méthode</span>
                                        <strong class="detail-value">
                                            @switch($payment->method)
                                                @case('stripe') Carte bancaire @break
                                                @case('paypal') PayPal @break
                                                @case('cash') Espèces @break
                                                @case('salon') Au salon @break
                                                @case('orange_money') Orange Money @break
                                                @case('wave') Wave @break
                                                @default {{ ucfirst($payment->method) }}
                                            @endswitch
                                        </strong>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-4">
                                    <div class="detail-item">
                                        <div class="detail-icon"><i class="fa fa-calendar"></i></div>
                                        <span class="detail-label">Date</span>
                                        <strong class="detail-value">{{ $payment->created_at->format('d/m/Y') }}</strong>
                                        <small style="color:#8E8E8E;">{{ $payment->created_at->format('H:i') }}</small>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-4">
                                    <div class="detail-item">
                                        <div class="detail-icon"><i class="fa fa-scissors"></i></div>
                                        <span class="detail-label">Service</span>
                                        <strong class="detail-value">{{ $payment->appointment->service->name ?? '—' }}</strong>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-4">
                                    <div class="detail-item">
                                        <div class="detail-icon"><i class="fa fa-user"></i></div>
                                        <span class="detail-label">Spécialiste</span>
                                        <strong class="detail-value">{{ $payment->appointment->employee->name ?? '—' }}</strong>
                                    </div>
                                </div>
                            </div>

                            @if($payment->transaction_id)
                            <div style="background:var(--primary-soft);border-radius:14px;padding:14px 18px;margin-top:8px;">
                                <span style="font-size:12px;color:var(--primary);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Transaction ID</span>
                                <p style="margin:4px 0 0;font-family:monospace;font-size:14px;color:var(--dark);">{{ $payment->transaction_id }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mt-3" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,0.06);">
                    <div class="card-body" style="padding:0;">
                        <div style="background:white;padding:18px 28px;border-bottom:1px solid var(--primary-soft);display:flex;align-items:center;gap:12px;">
                            <i class="fa fa-calendar" style="color:var(--primary);font-size:18px;"></i>
                            <h5 style="margin:0;font-family:'Playfair Display',serif;color:var(--dark);font-size:17px;">Rendez-vous associé</h5>
                        </div>
                        <div style="padding:24px 28px;">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <span style="font-size:12px;color:#8E8E8E;text-transform:uppercase;letter-spacing:0.5px;">Service</span><br>
                                    <strong style="font-size:15px;color:var(--dark);">{{ $payment->appointment->service->name ?? '—' }}</strong>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span style="font-size:12px;color:#8E8E8E;text-transform:uppercase;letter-spacing:0.5px;">Date</span><br>
                                    <strong style="font-size:15px;color:var(--dark);">{{ $payment->appointment->date->format('d/m/Y') }}</strong>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span style="font-size:12px;color:#8E8E8E;text-transform:uppercase;letter-spacing:0.5px;">Heure</span><br>
                                    <strong style="font-size:15px;color:var(--primary);">{{ $payment->appointment->time }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,0.06);">
                    <div class="card-body" style="padding:24px;">
                        <h5 style="font-family:'Playfair Display',serif;margin:0 0 18px;color:var(--dark);"><i class="fa fa-bolt mr-2" style="color:var(--primary);"></i>Actions</h5>

                        @if(!in_array($payment->status, ['paid', 'completed']))
                            @if($payment->appointment)
                                <form action="{{ route('client.payments.simulate', $payment->appointment) }}" method="POST" class="confirm-delete" data-confirm-message="Confirmer le paiement pour ce rendez-vous ?">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block mb-2" style="border-radius:12px;">
                                        <i class="fa fa-credit-card mr-2"></i>Payer maintenant
                                    </button>
                                </form>
                            @endif
                        @endif

                        @if(in_array($payment->status, ['paid', 'completed']))
                            <a href="{{ route('client.payments.invoice', $payment) }}" class="btn btn-primary btn-block mb-2" style="border-radius:12px;">
                                <i class="fa fa-download mr-2"></i>Télécharger la facture
                            </a>
                        @endif

                        <a href="{{ route('client.appointments.show', $payment->appointment) }}" class="btn btn-outline-secondary btn-block" style="border-radius:12px;">
                            <i class="fa fa-eye mr-2"></i>Voir le rendez-vous
                        </a>
                    </div>
                </div>

                <div class="card mt-3" style="border:none;border-radius:18px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,0.06);">
                    <div class="card-body text-center" style="padding:24px;">
                        <div style="width:50px;height:50px;border-radius:14px;background:var(--primary-soft);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                            <i class="fa fa-question-circle" style="color:var(--primary);font-size:20px;"></i>
                        </div>
                        <h5 style="font-family:'Playfair Display',serif;color:var(--dark);margin-bottom:6px;">Besoin d'aide ?</h5>
                        <p style="color:#8E8E8E;font-size:13px;margin-bottom:10px;">Pour toute question sur votre paiement</p>
                        <p style="font-size:18px;font-weight:700;color:var(--primary);margin:0;">+223 XX XX XX XX</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.detail-item { text-align: center; }
.detail-icon {
    width: 46px; height: 46px; border-radius: 14px;
    background: var(--primary-soft); display: flex; align-items: center; justify-content: center;
    margin: 0 auto 10px; color: var(--primary); font-size: 18px;
}
.detail-label { display: block; font-size: 12px; color: #8E8E8E; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.detail-value { display: block; font-size: 15px; color: var(--dark); }
.dark-theme .detail-value { color: #E8E8E8; }
.dark-theme .detail-icon { background: #2a2a4a; }
</style>
@endsection
