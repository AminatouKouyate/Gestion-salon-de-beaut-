{{--
    Vue : Enregistrement d'un paiement (employé)
    Description : Formulaire permettant à l'employé d'enregistrer un paiement pour un rendez-vous terminé.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-credit-card"></i></div>
                <div>
                    <h2 class="beauty-page-title">Encaisser un paiement</h2>
                    <p class="beauty-page-subtitle">Enregistrer un paiement en espèces ou carte</p>
                </div>
            </div>
            <a href="{{ route('employee.payments.index') }}" class="beauty-btn-primary" style="background:var(--primary-soft);color:var(--primary);box-shadow:none;">
                <i class="fa fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" style="border-radius:14px;">
                <i class="fa fa-exclamation-circle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="border-radius:14px;">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($unpaidAppointments->isEmpty() && !$appointment)
            <div class="enc-empty">
                <div class="enc-empty-icon"></div>
                <h4>Tous les rendez-vous sont payés !</h4>
                <p>Aucun rendez-vous en attente d'encaissement</p>
                <a href="{{ route('employee.appointments.index') }}" class="beauty-btn-primary">
                    <i class="fa fa-calendar mr-2"></i>Voir mes rendez-vous
                </a>
            </div>
        @else
            <form action="{{ route('employee.payments.store') }}" method="POST" id="encaissementForm">
                @csrf
                <div class="row">
                    <div class="col-lg-8">
                        {{-- STEP 1: SELECT APPOINTMENT --}}
                        <div class="enc-card mb-4">
                            <div class="enc-card-step">
                                <span class="enc-step-num">1</span>
                                <div>
                                    <h5>Rendez-vous à encaisser</h5>
                                    <p>Sélectionnez le rendez-vous terminé</p>
                                </div>
                            </div>
                            <div class="enc-card-body">
                                @if($appointment)
                                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                                    <div class="enc-selected-rdv">
                                        <div class="enc-rdv-avatar">
                                            {{ strtoupper(substr($appointment->client->name, 0, 1)) }}
                                        </div>
                                        <div class="enc-rdv-info">
                                            <h6>{{ $appointment->client->name }}</h6>
                                            <span class="enc-rdv-service">{{ $appointment->service->name }}</span>
                                            <span class="enc-rdv-date"><i class="fa fa-calendar-o mr-1"></i>{{ $appointment->scheduled_at->format('d/m/Y à H:i') }}</span>
                                        </div>
                                        <div class="enc-rdv-price">
                                            {{ number_format($appointment->service->getCurrentPrice(), 0, ',', ' ') }}
                                            <small>FCFA</small>
                                        </div>
                                    </div>
                                @else
                                    <select name="appointment_id" id="appointment_id" class="form-control" required>
                                        <option value="">— Choisir un rendez-vous —</option>
                                        @foreach($unpaidAppointments as $apt)
                                            <option value="{{ $apt->id }}"
                                                    data-price="{{ $apt->service->getCurrentPrice() }}"
                                                    data-client="{{ $apt->client->name }}"
                                                    data-service="{{ $apt->service->name }}"
                                                    data-date="{{ $apt->scheduled_at->format('d/m/Y') }}"
                                                    data-initial="{{ strtoupper(substr($apt->client->name, 0, 1)) }}">
                                                {{ $apt->client->name }} — {{ $apt->service->name }} — {{ number_format($apt->service->getCurrentPrice(), 0, ',', ' ') }} FCFA ({{ $apt->scheduled_at->format('d/m/Y') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div id="selectedRdvPreview" style="display:none;" class="enc-selected-rdv mt-3">
                                        <div class="enc-rdv-avatar" id="previewInitial"></div>
                                        <div class="enc-rdv-info">
                                            <h6 id="previewClient"></h6>
                                            <span class="enc-rdv-service" id="previewService"></span>
                                            <span class="enc-rdv-date" id="previewDate"></span>
                                        </div>
                                        <div class="enc-rdv-price" id="previewPrice"></div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- STEP 2: PAYMENT METHOD --}}
                        <div class="enc-card mb-4">
                            <div class="enc-card-step">
                                <span class="enc-step-num">2</span>
                                <div>
                                    <h5>Mode de paiement</h5>
                                    <p>Comment le client souhaite-t-il payer ?</p>
                                </div>
                            </div>
                            <div class="enc-card-body">
                                <div class="enc-methods">
                                    <label class="enc-method-card" for="method_cash">
                                        <input type="radio" name="method" value="cash" id="method_cash" required>
                                        <div class="enc-method-inner">
                                            <div class="enc-method-icon enc-method-cash">
                                                <i class="fa fa-money"></i>
                                            </div>
                                            <h6>Espèces</h6>
                                            <span>Paiement en liquide</span>
                                            <div class="enc-method-check"><i class="fa fa-check"></i></div>
                                        </div>
                                    </label>
                                    <label class="enc-method-card" for="method_card">
                                        <input type="radio" name="method" value="card" id="method_card">
                                        <div class="enc-method-inner">
                                            <div class="enc-method-icon enc-method-card-icon">
                                                <i class="fa fa-credit-card"></i>
                                            </div>
                                            <h6>Carte bancaire</h6>
                                            <span>Terminal de paiement</span>
                                            <div class="enc-method-check"><i class="fa fa-check"></i></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- SUBMIT --}}
                        <button type="submit" class="enc-submit-btn" id="submitBtn">
                            <i class="fa fa-check-circle mr-2"></i>Confirmer l'encaissement
                        </button>
                    </div>

                    {{-- SIDEBAR --}}
                    <div class="col-lg-4">
                        <div class="enc-summary-card">
                            <div class="enc-summary-header">
                                <i class="fa fa-file-text mr-2"></i>Récapitulatif
                            </div>
                            <div class="enc-summary-body" id="payment-summary">
                                @if($appointment)
                                    <div class="enc-summary-row">
                                        <span>Client</span>
                                        <strong>{{ $appointment->client->name }}</strong>
                                    </div>
                                    <div class="enc-summary-row">
                                        <span>Service</span>
                                        <strong>{{ $appointment->service->name }}</strong>
                                    </div>
                                    <div class="enc-summary-row">
                                        <span>Date</span>
                                        <strong>{{ $appointment->scheduled_at->format('d/m/Y') }}</strong>
                                    </div>
                                    <div class="enc-summary-divider"></div>
                                    <div class="enc-summary-total">
                                        <span>Total à encaisser</span>
                                        <div class="enc-total-amount">
                                            {{ number_format($appointment->service->getCurrentPrice(), 0, ',', ' ') }}
                                            <small>FCFA</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="enc-summary-empty">
                                        <i class="fa fa-hand-pointer-o"></i>
                                        <p>Sélectionnez un rendez-vous pour voir le récapitulatif</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="enc-info-card">
                            <div class="enc-info-icon"><i class="fa fa-shield"></i></div>
                            <h6>Paiement sécurisé</h6>
                            <p>L'encaissement au salon est réservé aux employés autorisés. Un reçu sera généré automatiquement.</p>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>

<style>
/* Empty state */
.enc-empty {
    text-align: center; padding: 60px 20px;
    background: white; border-radius: 20px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.05);
}
.enc-empty-icon { font-size: 56px; margin-bottom: 16px; }
.enc-empty h4 { font-family: 'Playfair Display', serif; color: var(--dark); margin-bottom: 8px; }
.enc-empty p { color: #8E8E8E; font-size: 15px; margin-bottom: 24px; }

/* Step cards */
.enc-card {
    background: white; border-radius: 20px; overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 2px 16px rgba(0,0,0,0.05);
}
.enc-card-step {
    display: flex; align-items: center; gap: 16px;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(0,0,0,0.06);
    background: var(--bg);
}
.enc-step-num {
    width: 40px; height: 40px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; font-size: 18px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.enc-card-step h5 {
    font-family: 'Playfair Display', serif; font-size: 17px;
    margin: 0; color: var(--dark);
}
.enc-card-step p { font-size: 13px; color: #8E8E8E; margin: 2px 0 0; }
.enc-card-body { padding: 24px; }

/* Selected RDV preview */
.enc-selected-rdv {
    display: flex; align-items: center; gap: 16px;
    padding: 18px; border-radius: 14px;
    background: var(--primary-soft);
    border: 2px solid var(--primary);
}
.enc-rdv-avatar {
    width: 50px; height: 50px; border-radius: 14px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; font-size: 20px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.enc-rdv-info { flex: 1; }
.enc-rdv-info h6 { margin: 0 0 4px; font-size: 16px; font-weight: 600; color: var(--dark); }
.enc-rdv-service {
    display: inline-block; padding: 3px 12px; border-radius: 20px; font-size: 12px;
    background: white; color: var(--primary); font-weight: 600; margin-right: 8px;
}
.enc-rdv-date { font-size: 12px; color: #8E8E8E; }
.enc-rdv-price {
    font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700;
    color: var(--dark); text-align: right; white-space: nowrap;
}
.enc-rdv-price small { font-size: 12px; font-weight: 400; color: #8E8E8E; display: block; }

/* Payment methods */
.enc-methods { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.enc-method-card { cursor: pointer; margin: 0; }
.enc-method-card input { display: none; }
.enc-method-inner {
    text-align: center; padding: 28px 20px; border-radius: 16px;
    border: 2px solid rgba(0,0,0,0.08);
    background: white; transition: all 0.3s; position: relative;
}
.enc-method-inner:hover { border-color: var(--primary-light); transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
.enc-method-icon {
    width: 64px; height: 64px; border-radius: 18px; margin: 0 auto 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px;
}
.enc-method-cash { background: #d1fae5; color: #059669; }
.enc-method-card-icon { background: #e0f2fe; color: #0284c7; }
.enc-method-inner h6 { font-family: 'Playfair Display', serif; font-size: 16px; margin: 0 0 4px; color: var(--dark); }
.enc-method-inner span { font-size: 12px; color: #8E8E8E; }
.enc-method-check {
    position: absolute; top: 12px; right: 12px;
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--primary); color: white;
    display: none; align-items: center; justify-content: center;
    font-size: 14px; box-shadow: 0 3px 10px rgba(0,0,0,0.15);
}
.enc-method-card input:checked + .enc-method-inner {
    border-color: var(--primary); background: var(--primary-soft);
    transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.enc-method-card input:checked + .enc-method-inner .enc-method-check { display: flex; }

/* Submit button */
.enc-submit-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 16px 32px; border: none; border-radius: 16px;
    font-size: 16px; font-weight: 700; cursor: pointer;
    background: linear-gradient(135deg, #059669, #047857);
    color: white; transition: all 0.3s;
    box-shadow: 0 4px 20px rgba(5,150,105,0.3);
}
.enc-submit-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(5,150,105,0.4); }

/* Summary card */
.enc-summary-card {
    background: white; border-radius: 20px; overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 2px 16px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}
.enc-summary-header {
    padding: 18px 24px; font-size: 16px; font-weight: 700;
    font-family: 'Playfair Display', serif;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white;
}
.enc-summary-body { padding: 24px; }
.enc-summary-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0; font-size: 14px;
}
.enc-summary-row span { color: #8E8E8E; }
.enc-summary-row strong { color: var(--dark); }
.enc-summary-divider { height: 1px; background: rgba(0,0,0,0.08); margin: 8px 0; }
.enc-summary-total {
    padding: 14px 0 0; text-align: center;
}
.enc-summary-total span { font-size: 13px; color: #8E8E8E; text-transform: uppercase; letter-spacing: 1px; }
.enc-total-amount {
    font-family: 'Playfair Display', serif; font-size: 36px; font-weight: 700;
    color: #059669; line-height: 1.1; margin-top: 6px;
}
.enc-total-amount small { font-size: 14px; font-weight: 400; color: #8E8E8E; }
.enc-summary-empty {
    text-align: center; padding: 20px 0; color: #8E8E8E;
}
.enc-summary-empty i { font-size: 32px; margin-bottom: 10px; display: block; opacity: 0.4; }
.enc-summary-empty p { font-size: 13px; margin: 0; }

/* Info card */
.enc-info-card {
    padding: 24px; border-radius: 16px; text-align: center;
    background: var(--primary-soft); border: 1px solid rgba(0,0,0,0.04);
}
.enc-info-icon {
    width: 48px; height: 48px; border-radius: 14px; margin: 0 auto 12px;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; font-size: 20px;
    display: flex; align-items: center; justify-content: center;
}
.enc-info-card h6 { font-family: 'Playfair Display', serif; color: var(--dark); margin-bottom: 6px; }
.enc-info-card p { font-size: 13px; color: #8E8E8E; margin: 0; line-height: 1.6; }

/* ===================== DARK MODE ===================== */
.dark-theme .enc-card,
.dark-theme .enc-summary-card,
.dark-theme .enc-empty { background: #252540; border-color: #333355; }
.dark-theme .enc-card-step { background: #1e1e30; border-bottom-color: #333355; }
.dark-theme .enc-card-step h5,
.dark-theme .enc-rdv-info h6,
.dark-theme .enc-method-inner h6,
.dark-theme .enc-empty h4,
.dark-theme .enc-summary-row strong,
.dark-theme .enc-info-card h6 { color: #F0F0F0; }
.dark-theme .enc-card-step p,
.dark-theme .enc-method-inner span,
.dark-theme .enc-rdv-date,
.dark-theme .enc-summary-row span,
.dark-theme .enc-info-card p,
.dark-theme .enc-summary-empty { color: #B0B0B0; }
.dark-theme .enc-method-inner { background: #1e1e30; border-color: #333355; }
.dark-theme .enc-method-inner:hover { border-color: var(--primary-light); }
.dark-theme .enc-method-card input:checked + .enc-method-inner { background: #2a2555; }
.dark-theme .enc-selected-rdv { background: #2a2555; border-color: var(--primary); }
.dark-theme .enc-rdv-service { background: #1e1e30; }
.dark-theme .enc-rdv-price { color: #F0F0F0; }
.dark-theme .enc-info-card { background: #2a2555; border-color: #333355; }
.dark-theme .enc-summary-divider { background: #333355; }

@media (max-width: 767px) {
    .enc-methods { grid-template-columns: 1fr; }
    .enc-selected-rdv { flex-wrap: wrap; }
    .enc-rdv-price { width: 100%; text-align: left; margin-top: 8px; }
    .enc-total-amount { font-size: 28px; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select appointment → update preview + summary
    var sel = document.getElementById('appointment_id');
    var summary = document.getElementById('payment-summary');
    var preview = document.getElementById('selectedRdvPreview');

    if (sel) {
        sel.addEventListener('change', function() {
            var opt = this.options[this.selectedIndex];
            if (this.value && opt) {
                var client = opt.getAttribute('data-client');
                var service = opt.getAttribute('data-service');
                var price = parseInt(opt.getAttribute('data-price')).toLocaleString('fr-FR');
                var date = opt.getAttribute('data-date');
                var initial = opt.getAttribute('data-initial');

                // Preview card
                if (preview) {
                    document.getElementById('previewInitial').textContent = initial;
                    document.getElementById('previewClient').textContent = client;
                    document.getElementById('previewService').textContent = service;
                    document.getElementById('previewDate').innerHTML = '<i class="fa fa-calendar-o mr-1"></i>' + date;
                    document.getElementById('previewPrice').innerHTML = price + '<small>FCFA</small>';
                    preview.style.display = 'flex';
                }

                // Summary
                if (summary) {
                    summary.innerHTML =
                        '<div class="enc-summary-row"><span>Client</span><strong>' + client + '</strong></div>' +
                        '<div class="enc-summary-row"><span>Service</span><strong>' + service + '</strong></div>' +
                        '<div class="enc-summary-row"><span>Date</span><strong>' + date + '</strong></div>' +
                        '<div class="enc-summary-divider"></div>' +
                        '<div class="enc-summary-total"><span>Total à encaisser</span>' +
                        '<div class="enc-total-amount">' + price + '<small>FCFA</small></div></div>';
                }
            } else {
                if (preview) preview.style.display = 'none';
                if (summary) {
                    summary.innerHTML =
                        '<div class="enc-summary-empty"><i class="fa fa-hand-pointer-o"></i>' +
                        '<p>Sélectionnez un rendez-vous pour voir le récapitulatif</p></div>';
                }
            }
        });
    }
});
</script>
@endsection
