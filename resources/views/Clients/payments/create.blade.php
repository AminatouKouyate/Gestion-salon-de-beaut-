{{--
    Vue : Création d'un paiement client
    Description : Formulaire de paiement en 2 étapes : sélection du rendez-vous à payer et choix du mode de paiement (Stripe, PayPal, Orange Money, Wave, au salon). Récapitulatif latéral avec montant.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-credit-card"></i></div>
                <div>
                    <h2 class="beauty-page-title">Effectuer un paiement</h2>
                    <p class="beauty-page-subtitle">Sélectionnez un rendez-vous et payez en ligne</p>
                </div>
            </div>
            <a href="{{ route('client.payments.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        @include('partials.error')

        <div class="row">
            <div class="col-lg-8">
                @if($unpaidAppointments->isEmpty() && !$appointment)
                    <div class="card" style="border:none;border-radius:18px;box-shadow:0 2px 16px rgba(0,0,0,0.06);">
                        <div class="card-body text-center" style="padding:60px 30px;">
                            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#D1FAE5,#A7F3D0);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                                <i class="fa fa-check-circle" style="font-size:36px;color:#10B981;"></i>
                            </div>
                            <h4 style="font-family:'Playfair Display',serif;color:var(--dark);margin-bottom:10px;">Tous vos rendez-vous sont payés !</h4>
                            <p style="color:#8E8E8E;margin-bottom:24px;">Aucun paiement en attente pour le moment.</p>
                            <a href="{{ route('client.appointments.index') }}" class="btn btn-primary" style="border-radius:12px;padding:12px 28px;">
                                <i class="fa fa-calendar mr-2"></i>Voir mes rendez-vous
                            </a>
                        </div>
                    </div>
                @else
                <form action="{{ route('client.payments.store') }}" method="POST" id="paymentForm">
                    @csrf

                    {{-- Step 1: Select appointment --}}
                    <div class="pay-step">
                        <div class="pay-step-header">
                            <div class="pay-step-num">1</div>
                            <div>
                                <h4>Rendez-vous à payer</h4>
                                <p>Sélectionnez le rendez-vous concerné</p>
                            </div>
                        </div>
                        <div class="pay-step-body">
                            <select name="appointment_id" id="appointment_id" class="form-control" required>
                                @if($appointment)
                                    <option value="{{ $appointment->id }}"
                                            data-price="{{ $appointment->service->getCurrentPrice() }}"
                                            data-service="{{ $appointment->service->name }}"
                                            data-date="{{ $appointment->date->format('d/m/Y') }}" selected>
                                        {{ $appointment->service->name }} — {{ $appointment->date->format('d/m/Y') }} — {{ number_format($appointment->service->getCurrentPrice(), 0, ',', ' ') }} FCFA
                                    </option>
                                @else
                                    <option value="">Sélectionner un rendez-vous...</option>
                                    @foreach($unpaidAppointments as $apt)
                                        <option value="{{ $apt->id }}"
                                                data-price="{{ $apt->service->getCurrentPrice() }}"
                                                data-service="{{ $apt->service->name }}"
                                                data-date="{{ $apt->date->format('d/m/Y') }}">
                                            {{ $apt->service->name }} — {{ $apt->date->format('d/m/Y') }} — {{ number_format($apt->service->getCurrentPrice(), 0, ',', ' ') }} FCFA
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Step 2: Payment method --}}
                    <div class="pay-step">
                        <div class="pay-step-header">
                            <div class="pay-step-num">2</div>
                            <div>
                                <h4>Mode de paiement</h4>
                                <p>Comment souhaitez-vous payer ?</p>
                            </div>
                        </div>
                        <div class="pay-step-body">
                            <div class="pay-methods-grid">
                                <label class="pay-method-card" data-method="stripe">
                                    <input type="radio" name="method" value="stripe">
                                    <div class="pay-method-icon" style="background:linear-gradient(135deg,#E0E7FF,#C7D2FE);"><i class="fa fa-cc-stripe" style="color:#6366F1;font-size:24px;"></i></div>
                                    <h6>Carte bancaire</h6>
                                    <span>Visa, Mastercard</span>
                                    <div class="pay-method-check"><i class="fa fa-check"></i></div>
                                </label>
                                <label class="pay-method-card" data-method="paypal">
                                    <input type="radio" name="method" value="paypal">
                                    <div class="pay-method-icon" style="background:linear-gradient(135deg,#DBEAFE,#BFDBFE);"><i class="fa fa-paypal" style="color:#3B82F6;font-size:24px;"></i></div>
                                    <h6>PayPal</h6>
                                    <span>Paiement sécurisé</span>
                                    <div class="pay-method-check"><i class="fa fa-check"></i></div>
                                </label>
                                <label class="pay-method-card" data-method="orange_money">
                                    <input type="radio" name="method" value="orange_money">
                                    <div class="pay-method-icon" style="background:linear-gradient(135deg,#FEF3C7,#FDE68A);"><i class="fa fa-mobile" style="color:#F59E0B;font-size:28px;"></i></div>
                                    <h6>Orange Money</h6>
                                    <span>Paiement mobile</span>
                                    <div class="pay-method-check"><i class="fa fa-check"></i></div>
                                </label>
                                <label class="pay-method-card" data-method="wave">
                                    <input type="radio" name="method" value="wave">
                                    <div class="pay-method-icon" style="background:linear-gradient(135deg,#CFFAFE,#A5F3FC);"><i class="fa fa-mobile" style="color:#06B6D4;font-size:28px;"></i></div>
                                    <h6>Wave</h6>
                                    <span>Paiement mobile</span>
                                    <div class="pay-method-check"><i class="fa fa-check"></i></div>
                                </label>
                                <label class="pay-method-card" data-method="salon">
                                    <input type="radio" name="method" value="salon">
                                    <div class="pay-method-icon" style="background:linear-gradient(135deg,var(--primary-soft),rgba(255,255,255,0.5));"><i class="fa fa-building" style="color:var(--primary);font-size:22px;"></i></div>
                                    <h6>Au salon</h6>
                                    <span>Payer sur place</span>
                                    <div class="pay-method-check"><i class="fa fa-check"></i></div>
                                </label>
                            </div>

                            <div id="phone-number-group" style="display:none;margin-top:18px;">
                                <label for="phone_number"><i class="fa fa-phone mr-1" style="color:var(--primary);"></i> Numéro de téléphone <span class="text-danger">*</span></label>
                                <input type="text" name="phone_number" id="phone_number"
                                       class="form-control @error('phone_number') is-invalid @enderror"
                                       placeholder="Ex: +223 XX XX XX XX"
                                       value="{{ old('phone_number', $client->phone ?? '') }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted mt-1 d-block">Le numéro associé à votre compte mobile money</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4 mb-5 d-lg-none">
                        <button type="submit" class="btn btn-primary btn-block" style="border-radius:14px;padding:16px;font-size:16px;font-weight:600;">
                            <i class="fa fa-lock mr-2"></i>Procéder au paiement
                        </button>
                    </div>
                </form>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="summary-card">
                    <div class="summary-header">
                        <i class="fa fa-shopping-bag"></i>
                        <h4>Récapitulatif</h4>
                    </div>
                    <div class="summary-body" id="payment-summary">
                        <div class="summary-empty">
                            <div class="se-icon"></div>
                                                         <p>Sélectionnez un rendez-vous</p>
                        </div>
                    </div>
                    <div class="summary-footer" id="summaryFooter" style="display:none;">
                        <button type="submit" form="paymentForm" class="summary-cta">
                            <i class="fa fa-lock mr-2"></i>Payer maintenant
                        </button>
                    </div>
                </div>

                <div class="card mt-3" style="border:none;border-radius:18px;box-shadow:0 2px 12px rgba(0,0,0,0.04);">
                    <div class="card-body" style="padding:24px;">
                        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                            <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#D1FAE5,#A7F3D0);display:flex;align-items:center;justify-content:center;">
                                <i class="fa fa-shield" style="color:#10B981;font-size:18px;"></i>
                            </div>
                            <h5 style="margin:0;font-family:'Playfair Display',serif;color:var(--dark);">Paiement sécurisé</h5>
                        </div>
                        <p style="color:#8E8E8E;font-size:14px;margin:0;line-height:1.6;">Vos données sont protégées par un cryptage SSL 256 bits.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pay-step {
    background: white; border-radius: 18px; margin-bottom: 20px;
    border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    overflow: hidden; transition: all 0.3s;
}
.pay-step:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
.pay-step-header {
    display: flex; align-items: center; gap: 16px;
    padding: 20px 24px; border-bottom: 1px solid rgba(0,0,0,0.04);
}
.pay-step-num {
    width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 700;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.pay-step-header h4 { font-family: 'Playfair Display', serif; font-size: 18px; margin: 0; color: var(--dark); }
.pay-step-header p { font-size: 13px; color: #8E8E8E; margin: 2px 0 0; }
.pay-step-body { padding: 20px 24px; }

.pay-methods-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
.pay-method-card {
    position: relative; display: flex; flex-direction: column; align-items: center;
    text-align: center; padding: 20px 14px; border-radius: 16px;
    border: 2px solid rgba(0,0,0,0.06); background: white;
    cursor: pointer; transition: all 0.25s; margin: 0;
}
.pay-method-card input { display: none; }
.pay-method-card:hover { border-color: var(--primary-light); transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
.pay-method-card.selected { border-color: var(--primary); background: var(--primary-soft); box-shadow: 0 6px 22px rgba(0,0,0,0.1); }
.pay-method-icon {
    width: 56px; height: 56px; border-radius: 16px;
    display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
}
.pay-method-card h6 { margin: 0 0 2px; font-size: 14px; font-weight: 600; color: var(--dark); }
.pay-method-card span { font-size: 11px; color: #8E8E8E; }
.pay-method-check {
    position: absolute; top: 10px; right: 10px;
    width: 24px; height: 24px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; display: none; align-items: center; justify-content: center; font-size: 11px;
}
.pay-method-card.selected .pay-method-check { display: flex; }

.summary-card {
    background: white; border-radius: 18px; overflow: hidden;
    border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 2px 16px rgba(0,0,0,0.06);
}
.summary-header {
    padding: 18px 24px; background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; display: flex; align-items: center; gap: 10px;
}
.summary-header i { font-size: 20px; }
.summary-header h4 { font-family: 'Playfair Display', serif; font-size: 18px; margin: 0; color: white; }
.summary-body { padding: 24px; }
.summary-empty { text-align: center; padding: 20px 0; }
.summary-empty .se-icon { font-size: 40px; margin-bottom: 10px; }
.summary-empty p { color: #8E8E8E; font-size: 13px; margin: 0; }
.summary-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid rgba(0,0,0,0.04); }
.summary-item:last-child { border-bottom: none; }
.summary-item-label { font-size: 13px; color: #8E8E8E; display: flex; align-items: center; gap: 8px; }
.summary-item-label i { color: var(--primary); width: 16px; text-align: center; }
.summary-item-value { font-size: 14px; font-weight: 600; color: var(--dark); }
.summary-total { display: flex; justify-content: space-between; align-items: center; padding: 16px 0 0; border-top: 2px solid var(--primary-soft); margin-top: 8px; }
.summary-total-label { font-size: 15px; font-weight: 600; color: var(--dark); }
.summary-total-value { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--primary); }
.summary-footer { padding: 0 24px 24px; }
.summary-cta {
    display: flex; align-items: center; justify-content: center;
    width: 100%; padding: 14px; border: none; border-radius: 14px;
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; font-size: 15px; font-weight: 600; cursor: pointer;
    box-shadow: 0 4px 18px rgba(0,0,0,0.15); transition: all 0.3s;
}
.summary-cta:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(0,0,0,0.2); }

.dark-theme .pay-step { background: #252540; border-color: #333355; }
.dark-theme .pay-step-header { border-bottom-color: #333355; }
.dark-theme .pay-step-header h4 { color: #E8E8E8; }
.dark-theme .pay-method-card { background: #2a2a4a; border-color: #333355; }
.dark-theme .pay-method-card:hover { background: #333360; }
.dark-theme .pay-method-card.selected { background: #333360; }
.dark-theme .pay-method-card h6 { color: #E8E8E8; }
.dark-theme .summary-card { background: #252540; border-color: #333355; }
.dark-theme .summary-item { border-bottom-color: #333355; }
.dark-theme .summary-item-value, .dark-theme .summary-total-label { color: #E8E8E8; }

@media (max-width: 767px) { .pay-methods-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 400px) { .pay-methods-grid { grid-template-columns: 1fr; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var methodCards = document.querySelectorAll('.pay-method-card');
    var phoneGroup = document.getElementById('phone-number-group');
    var phoneInput = document.getElementById('phone_number');

    methodCards.forEach(function(card) {
        card.addEventListener('click', function() {
            methodCards.forEach(function(c) { c.classList.remove('selected'); });
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
            var method = this.dataset.method;
            if (method === 'orange_money' || method === 'wave') {
                phoneGroup.style.display = 'block';
                if (phoneInput) phoneInput.required = true;
            } else {
                phoneGroup.style.display = 'none';
                if (phoneInput) phoneInput.required = false;
            }
        });
    });

    var aptSelect = document.getElementById('appointment_id');
    var summary = document.getElementById('payment-summary');
    var footer = document.getElementById('summaryFooter');

    function updateSummary() {
        if (!aptSelect || !aptSelect.value) {
            summary.innerHTML = '<div class="summary-empty"><div class="se-icon"></div><p>Sélectionnez un rendez-vous</p></div>';
            if (footer) footer.style.display = 'none';
            return;
        }
        var opt = aptSelect.options[aptSelect.selectedIndex];
        var service = opt.dataset.service || opt.text.split(' — ')[0];
        var date = opt.dataset.date || '';
        var price = opt.dataset.price || '0';

        var html = '';
        html += '<div class="summary-item"><span class="summary-item-label"><i class="fa fa-scissors"></i> Service</span><span class="summary-item-value">' + service + '</span></div>';
        html += '<div class="summary-item"><span class="summary-item-label"><i class="fa fa-calendar"></i> Date</span><span class="summary-item-value">' + date + '</span></div>';
        html += '<div class="summary-total"><span class="summary-total-label">Total</span><span class="summary-total-value">' + Number(price).toLocaleString('fr-FR') + ' FCFA</span></div>';
        summary.innerHTML = html;
        if (footer) footer.style.display = 'block';
    }

    if (aptSelect) {
        aptSelect.addEventListener('change', updateSummary);
        if (aptSelect.value) updateSummary();
    }
});
</script>
@endsection
