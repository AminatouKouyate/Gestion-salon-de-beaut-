{{--
    Vue : Modification d'un rendez-vous existant
    Description : Formulaire de modification en 3 étapes identique à la création, pré-rempli avec les données du rendez-vous actuel. Permet de changer le service, la date, le créneau et les notes.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-edit"></i></div>
                <div>
                    <h2 class="beauty-page-title">Modifier le rendez-vous</h2>
                    <p class="beauty-page-subtitle">Modifiez les détails de votre rendez-vous</p>
                </div>
            </div>
            <a href="{{ route('client.appointments.index') }}" class="btn-beauty-outline"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        @include('partials.error')

        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('client.appointments.update', $appointment) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')

                    {{-- STEP 1: Service --}}
                    <div class="booking-step">
                        <div class="step-header">
                            <div class="step-number active">1</div>
                            <div>
                                <h4 class="step-title">Service sélectionné</h4>
                                <p class="step-subtitle">Modifiez votre soin si nécessaire</p>
                            </div>
                        </div>
                        <div class="step-body">
                            <input type="hidden" name="service_id" id="service_id" value="{{ old('service_id', $appointment->service_id) }}">
                            <div class="service-search-box mb-3">
                                <i class="fa fa-search"></i>
                                <input type="text" id="serviceSearch" class="form-control" placeholder="Rechercher un service...">
                            </div>
                            <div class="services-grid" id="servicesGrid">
                                @foreach($services as $service)
                                <div class="service-card {{ (old('service_id', $appointment->service_id)) == $service->id ? 'selected' : '' }}"
                                     data-id="{{ $service->id }}"
                                     data-price="{{ $service->getCurrentPrice() }}"
                                     data-duration="{{ $service->duration }}"
                                     data-name="{{ $service->name }}">
                                    <div class="service-card-icon">
                                        @php
                                            $icons = ['fa-scissors','fa-magic','fa-heart','fa-star','fa-diamond','fa-sun-o','fa-leaf','fa-tint'];
                                            $icon = $icons[$loop->index % count($icons)];
                                        @endphp
                                        <i class="fa {{ $icon }}"></i>
                                    </div>
                                    <div class="service-card-info">
                                        <h6>{{ $service->name }}</h6>
                                        <div class="service-card-meta">
                                            <span class="service-price">{{ number_format($service->getCurrentPrice(), 0, ',', ' ') }} FCFA</span>
                                            <span class="service-duration"><i class="fa fa-clock-o"></i> {{ $service->duration }} min</span>
                                        </div>
                                    </div>
                                    <div class="service-card-check"><i class="fa fa-check"></i></div>
                                </div>
                                @endforeach
                            </div>
                            @error('service_id')
                                <div class="text-danger mt-2" style="font-size:13px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- STEP 2: Date & Time --}}
                    <div class="booking-step">
                        <div class="step-header">
                            <div class="step-number active">2</div>
                            <div>
                                <h4 class="step-title">Date & Créneau horaire</h4>
                                <p class="step-subtitle">Quand souhaitez-vous venir ?</p>
                            </div>
                        </div>
                        <div class="step-body">
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label for="date"><i class="fa fa-calendar mr-1" style="color:var(--primary);"></i> Date</label>
                                    <input type="date" name="date" id="date"
                                           class="form-control @error('date') is-invalid @enderror"
                                           value="{{ old('date', $appointment->date->format('Y-m-d')) }}"
                                           min="{{ date('Y-m-d') }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-7 mb-3">
                                    <label><i class="fa fa-clock-o mr-1" style="color:var(--primary);"></i> Créneau</label>
                                    <input type="hidden" name="time" id="selected_time" value="{{ old('time', $appointment->time) }}">
                                    <div id="slots-container" class="slots-wrapper">
                                        <div class="slots-placeholder">
                                            <i class="fa fa-clock-o"></i>
                                            <p>Chargement des créneaux...</p>
                                        </div>
                                    </div>
                                    <div id="slots-loading" style="display: none;" class="text-center py-3">
                                        <span class="spinner-border spinner-border-sm mr-2" style="color:var(--primary);"></span>
                                        <span style="color:var(--primary);font-weight:500;">Recherche des créneaux...</span>
                                    </div>
                                    @error('time')
                                        <div class="text-danger mt-2" style="font-size:13px;">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 3: Notes --}}
                    <div class="booking-step">
                        <div class="step-header">
                            <div class="step-number active">3</div>
                            <div>
                                <h4 class="step-title">Notes & demandes</h4>
                                <p class="step-subtitle">Optionnel — allergies, préférences...</p>
                            </div>
                        </div>
                        <div class="step-body">
                            <textarea name="notes" id="notes" class="form-control" rows="3"
                                      placeholder="Ex: Je suis allergique aux produits contenant du latex...">{{ old('notes', $appointment->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="text-center mt-4 mb-5 d-lg-none">
                        <button type="submit" class="beauty-btn-primary" style="width:100%;justify-content:center;padding:16px 28px;font-size:16px;">
                            <i class="fa fa-save mr-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

            {{-- SUMMARY SIDEBAR --}}
            <div class="col-lg-4">
                <div class="summary-card">
                    <div class="summary-header">
                        <i class="fa fa-shopping-bag"></i>
                        <h4>Récapitulatif</h4>
                    </div>
                    <div class="summary-body" id="summary">
                        <div class="summary-empty">
                            <div class="se-icon"></div>
                            <p>Chargement...</p>
                        </div>
                    </div>
                    <div class="summary-footer" id="summaryFooter">
                        <button type="submit" form="editForm" class="summary-cta">
                            <i class="fa fa-save mr-2"></i>Enregistrer
                        </button>
                    </div>
                </div>

                <div class="info-card mt-3">
                    <div class="info-card-header">
                        <div class="info-card-icon"><i class="fa fa-info-circle"></i></div>
                        <h5>Statut actuel</h5>
                    </div>
                    <div class="info-card-rows">
                        <div class="info-card-row">
                            <span class="info-card-label">Statut</span>
                            <span>{!! $appointment->status_badge !!}</span>
                        </div>
                        <div class="info-card-row">
                            <span class="info-card-label">Créé le</span>
                            <span class="info-card-value">{{ $appointment->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="info-card mt-3">
                    <div class="info-card-icon"><i class="fa fa-info-circle"></i></div>
                    <h5>Informations</h5>
                    <ul>
                        <li><i class="fa fa-clock-o"></i> Ouvert 9h — 18h</li>
                        <li><i class="fa fa-calendar"></i> Lundi — Samedi</li>
                        <li><i class="fa fa-phone"></i> +223 XX XX XX XX</li>
                        <li><i class="fa fa-map-marker"></i> Bamako, Mali</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.booking-step {
    background: white; border-radius: 18px; margin-bottom: 20px;
    border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    overflow: hidden; transition: all 0.3s;
}
.booking-step:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
.step-header {
    display: flex; align-items: center; gap: 16px;
    padding: 20px 24px; border-bottom: 1px solid rgba(0,0,0,0.04);
}
.step-number {
    width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 700; flex-shrink: 0;
    background: var(--primary-soft); color: var(--primary); transition: all 0.3s;
}
.step-number.active {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.step-title { font-family: 'Playfair Display', serif; font-size: 18px; margin: 0; color: var(--dark); }
.step-subtitle { font-size: 13px; color: #8E8E8E; margin: 2px 0 0; }
.step-body { padding: 20px 24px; }

/* ===== Service search ===== */
.service-search-box { position: relative; }
.service-search-box i {
    position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
    color: var(--primary); font-size: 14px;
}
.service-search-box input { padding-left: 42px !important; }

.services-grid {
    display: grid; grid-template-columns: 1fr; gap: 10px;
    max-height: 300px; overflow-y: auto; padding-right: 4px;
}
.services-grid::-webkit-scrollbar { width: 5px; }
.services-grid::-webkit-scrollbar-thumb { background: var(--primary-light); border-radius: 10px; }

.service-card {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 18px; border-radius: 14px;
    border: 2px solid rgba(0,0,0,0.06); background: white;
    cursor: pointer; transition: all 0.25s; position: relative;
}
.service-card:hover { border-color: var(--primary-light); background: var(--primary-soft); transform: translateX(4px); }
.service-card.selected { border-color: var(--primary); background: var(--primary-soft); box-shadow: 0 4px 18px rgba(0,0,0,0.08); }
.service-card-icon {
    width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--primary-soft), rgba(255,255,255,0.8));
    color: var(--primary); font-size: 18px; transition: all 0.25s;
}
.service-card.selected .service-card-icon, .service-card:hover .service-card-icon {
    background: linear-gradient(135deg, var(--primary), var(--dark)); color: white;
}
.service-card-info { flex: 1; min-width: 0; }
.service-card-info h6 { margin: 0; font-size: 14px; font-weight: 600; color: var(--dark); }
.service-card-meta { display: flex; align-items: center; gap: 12px; margin-top: 6px; }
.service-price { font-size: 14px; font-weight: 700; color: var(--primary); }
.service-duration { font-size: 12px; color: #8E8E8E; display: flex; align-items: center; gap: 4px; }
.service-card-check {
    width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid rgba(0,0,0,0.1); color: transparent; font-size: 12px; transition: all 0.25s;
}
.service-card.selected .service-card-check {
    background: linear-gradient(135deg, var(--primary), var(--dark)); border-color: var(--primary); color: white;
}

.slots-wrapper { min-height: 80px; }
.slots-placeholder { text-align: center; padding: 20px; color: #8E8E8E; }
.slots-placeholder i { font-size: 28px; display: block; margin-bottom: 8px; opacity: 0.3; }
.slots-placeholder p { margin: 0; font-size: 13px; }

.slots-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.slot-btn {
    min-width: 90px; text-align: center; padding: 10px 14px;
    border-radius: 12px; cursor: pointer; transition: all 0.25s;
    font-weight: 600; font-size: 14px;
    border: 2px solid var(--primary-light); background: white; color: var(--dark);
}
.slot-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.12); border-color: var(--primary); background: var(--primary-soft); }
.slot-btn.selected {
    background: linear-gradient(135deg, var(--primary), var(--dark)) !important;
    border-color: var(--primary) !important; color: white !important;
    box-shadow: 0 4px 18px rgba(0,0,0,0.2);
}
.slot-btn.current-slot { border-color: #10b981 !important; position: relative; }
.slot-btn.current-slot::after {
    content: '✓'; position: absolute; top: -8px; right: -8px;
    background: linear-gradient(135deg, #10b981, #059669); color: white;
    border-radius: 50%; width: 20px; height: 20px; font-size: 11px; line-height: 20px; text-align: center;
}
.slot-btn .slot-sub { font-size: 10px; display: block; margin-top: 2px; opacity: 0.7; font-weight: 400; }

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

/* Info card */
.info-card {
    background: white; border-radius: 18px; padding: 24px;
    border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    text-align: center;
}
.info-card-header {
    display: flex; align-items: center; gap: 12px; margin-bottom: 14px; text-align: left;
}
.info-card-icon { font-size: 28px; color: var(--primary); margin-bottom: 12px; }
.info-card-header .info-card-icon { margin-bottom: 0; font-size: 18px;
    width: 42px; height: 42px; border-radius: 12px;
    background: linear-gradient(135deg, var(--primary-soft), white);
    display: flex; align-items: center; justify-content: center;
}
.info-card h5 { font-family: 'Playfair Display', serif; margin-bottom: 16px; color: var(--dark); }
.info-card-header h5 { margin-bottom: 0; }
.info-card ul { list-style: none; padding: 0; margin: 0; text-align: left; }
.info-card li { padding: 8px 0; font-size: 14px; color: #555; display: flex; align-items: center; gap: 10px; }
.info-card li i { color: var(--primary); width: 18px; text-align: center; }
.info-card-rows { display: flex; flex-direction: column; gap: 10px; }
.info-card-row { display: flex; justify-content: space-between; align-items: center; }
.info-card-label { font-size: 13px; color: #8E8E8E; }
.info-card-value { font-size: 14px; font-weight: 600; color: var(--dark); }

/* Button fallback */
.btn-beauty-outline {
    display: inline-flex; align-items: center; padding: 12px 28px;
    background: transparent; color: var(--primary); border: 2px solid var(--primary);
    border-radius: 14px; font-size: 14px; font-weight: 600;
    text-decoration: none !important; transition: all 0.3s;
}
.btn-beauty-outline:hover { background: var(--primary); color: white; transform: translateY(-2px); text-decoration: none; }

/* Dark mode */
.dark-theme .booking-step { background: #252540; border-color: #333355; }
.dark-theme .step-header { border-bottom-color: #333355; }
.dark-theme .step-title { color: #E8E8E8; }
.dark-theme .service-card { background: #2a2a4a; border-color: #333355; }
.dark-theme .service-card:hover { background: #333360; }
.dark-theme .service-card.selected { background: #333360; }
.dark-theme .service-card-info h6 { color: #E8E8E8; }
.dark-theme .slot-btn { background: #2a2a4a; border-color: #444; color: #E8E8E8; }
.dark-theme .slot-btn:hover { background: #333360; }
.dark-theme .summary-card { background: #252540; border-color: #333355; }
.dark-theme .summary-item { border-bottom-color: #333355; }
.dark-theme .summary-item-value, .dark-theme .summary-total-label { color: #E8E8E8; }
.dark-theme .info-card { background: #252540; border-color: #333355; }
.dark-theme .info-card h5 { color: #E8E8E8; }
.dark-theme .info-card li { color: #ccc; }
.dark-theme .info-card-value { color: #E8E8E8; }
.dark-theme .info-card-header .info-card-icon { background: linear-gradient(135deg, #2a2a4a, #333355); }
.dark-theme .btn-beauty-outline { color: var(--primary-light); border-color: var(--primary-light); }
.dark-theme .btn-beauty-outline:hover { background: var(--primary); color: white; }

@media (min-width: 576px) { .services-grid { grid-template-columns: 1fr 1fr; } }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var serviceInput = document.getElementById('service_id');
    var dateInput = document.getElementById('date');
    var selectedTimeInput = document.getElementById('selected_time');
    var slotsContainer = document.getElementById('slots-container');
    var slotsLoading = document.getElementById('slots-loading');
    var summary = document.getElementById('summary');

    var currentTime = "{{ $appointment->time }}";
    var currentDate = "{{ $appointment->date->format('Y-m-d') }}";
    var currentService = { id: null, name: '', price: 0, duration: 0 };

    // Init from preselected card
    var preselected = document.querySelector('.service-card.selected');
    if (preselected) {
        currentService.id = preselected.dataset.id;
        currentService.name = preselected.dataset.name;
        currentService.price = preselected.dataset.price;
        currentService.duration = preselected.dataset.duration;
    }

    // Service search filter
    document.getElementById('serviceSearch').addEventListener('input', function() {
        var query = this.value.toLowerCase();
        document.querySelectorAll('.service-card').forEach(function(card) {
            var name = card.dataset.name.toLowerCase();
            card.style.display = name.indexOf(query) !== -1 ? '' : 'none';
        });
    });

    // Service card click
    document.querySelectorAll('.service-card').forEach(function(card) {
        card.addEventListener('click', function() {
            document.querySelectorAll('.service-card').forEach(function(c) { c.classList.remove('selected'); });
            this.classList.add('selected');
            currentService.id = this.dataset.id;
            currentService.name = this.dataset.name;
            currentService.price = this.dataset.price;
            currentService.duration = this.dataset.duration;
            serviceInput.value = this.dataset.id;
            loadAvailableSlots();
            updateSummary();
        });
    });

    function loadAvailableSlots() {
        var serviceId = serviceInput.value;
        var date = dateInput.value;

        if (!serviceId || !date) {
            slotsContainer.innerHTML = '<div class="slots-placeholder"><i class="fa fa-hand-pointer-o"></i><p>Sélectionnez un service et une date</p></div>';
            return;
        }

        slotsContainer.innerHTML = '';
        slotsLoading.style.display = 'block';

        fetch('{{ route("client.appointments.available-slots") }}?service_id=' + serviceId + '&date=' + date)
            .then(function(r) { return r.json(); })
            .then(function(data) {
                slotsLoading.style.display = 'none';

                if (!data.slots || data.slots.length === 0) {
                    slotsContainer.innerHTML = '<div class="alert alert-warning mb-0" style="border-radius:12px;border:none;"><i class="fa fa-exclamation-circle mr-2"></i>Aucun créneau disponible pour cette date</div>';
                    return;
                }

                var html = '<div class="slots-grid">';
                data.slots.forEach(function(slot) {
                    var time = slot.time;
                    var isCurrentSlot = (time === currentTime && date === currentDate);
                    var isSelected = selectedTimeInput.value === time;
                    var cls = 'slot-btn';
                    if (isCurrentSlot) cls += ' current-slot';
                    if (isSelected) cls += ' selected';

                    var subText = '';
                    if (slot.employee_count) subText = '<span class="slot-sub">' + slot.employee_count + ' dispo.</span>';

                    html += '<button type="button" class="' + cls + '" data-time="' + time + '">' + time + subText + '</button>';
                });
                html += '</div>';

                if (date === currentDate) {
                    html += '<p class="mt-2" style="font-size:12px;color:#10b981;"><i class="fa fa-check-circle mr-1"></i>Le créneau actuel (' + currentTime + ') est marqué en vert</p>';
                }

                slotsContainer.innerHTML = html;

                document.querySelectorAll('.slot-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.slot-btn').forEach(function(b) { b.classList.remove('selected'); });
                        this.classList.add('selected');
                        selectedTimeInput.value = this.dataset.time;
                        updateSummary();
                    });
                });
            })
            .catch(function() {
                slotsLoading.style.display = 'none';
                slotsContainer.innerHTML = '<div class="alert alert-danger mb-0" style="border-radius:12px;border:none;"><i class="fa fa-exclamation-triangle mr-2"></i>Erreur lors du chargement</div>';
            });
    }

    function updateSummary() {
        var date = dateInput.value;
        var time = selectedTimeInput.value;

        if (currentService.id) {
            var html = '';
            html += '<div class="summary-item"><span class="summary-item-label"><i class="fa fa-scissors"></i> Service</span><span class="summary-item-value">' + currentService.name + '</span></div>';
            html += '<div class="summary-item"><span class="summary-item-label"><i class="fa fa-clock-o"></i> Durée</span><span class="summary-item-value">' + currentService.duration + ' min</span></div>';
            if (date) {
                var fd = new Date(date).toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'long' });
                html += '<div class="summary-item"><span class="summary-item-label"><i class="fa fa-calendar"></i> Date</span><span class="summary-item-value">' + fd + '</span></div>';
            }
            if (time) {
                html += '<div class="summary-item"><span class="summary-item-label"><i class="fa fa-clock-o"></i> Heure</span><span class="summary-item-value">' + time + '</span></div>';
            }
            html += '<div class="summary-total"><span class="summary-total-label">Total</span><span class="summary-total-value">' + Number(currentService.price).toLocaleString('fr-FR') + ' FCFA</span></div>';
            summary.innerHTML = html;
        }
    }

    dateInput.addEventListener('change', function() {
        loadAvailableSlots();
        updateSummary();
    });

    // Initial load
    updateSummary();
    if (serviceInput.value && dateInput.value) {
        loadAvailableSlots();
    }
});
</script>
@endpush
@endsection
