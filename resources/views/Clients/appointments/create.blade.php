{{--
    Vue : Création d'un nouveau rendez-vous client
    Description : Formulaire en 3 étapes : choix du service, sélection de la date et du créneau horaire (chargement AJAX), notes optionnelles. Inclut un récapitulatif latéral et une barre de recherche de services.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-calendar-plus-o"></i></div>
                <div>
                    <h2 class="beauty-page-title">Prendre un rendez-vous </h2>
                    <p class="beauty-page-subtitle">Choisissez votre soin et votre créneau idéal</p>
                </div>
            </div>
            <a href="{{ route('client.appointments.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        @include('partials.error')

        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('client.appointments.store') }}" method="POST" id="bookingForm">
                    @csrf

                    {{-- STEP 1: Service --}}
                    <div class="booking-step" id="step1">
                        <div class="step-header">
                            <div class="step-number active">1</div>
                            <div>
                                <h4 class="step-title">Choisissez votre soin</h4>
                                <p class="step-subtitle">Quel service souhaitez-vous ?</p>
                            </div>
                        </div>
                        <div class="step-body">
                            <input type="hidden" name="service_id" id="service_id" value="{{ old('service_id') ?? $selectedServiceId }}">
                            @error('service_id')
                                <div class="alert alert-danger mb-3" style="font-size:13px;">{{ $message }}</div>
                            @enderror

                            {{-- Search bar --}}
                            <div class="service-search-box mb-3">
                                <i class="fa fa-search"></i>
                                <input type="text" id="serviceSearch" class="form-control" placeholder="Rechercher un service...">
                            </div>

                            {{-- Service cards grid --}}
                            <div class="services-grid" id="servicesGrid">
                                @foreach($services as $service)
                                <div class="service-card {{ (old('service_id') ?? $selectedServiceId) == $service->id ? 'selected' : '' }}"
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
                                        @if($service->description)
                                            <p class="service-card-desc">{{ Str::limit($service->description, 60) }}</p>
                                        @endif
                                        <div class="service-card-meta">
                                            <span class="service-price">{{ number_format($service->getCurrentPrice(), 0, ',', ' ') }} FCFA</span>
                                            <span class="service-duration"><i class="fa fa-clock-o"></i> {{ $service->duration }} min</span>
                                        </div>
                                    </div>
                                    <div class="service-card-check"><i class="fa fa-check"></i></div>
                                </div>
                                @endforeach
                            </div>

                            @if($selectedService)
                            <div class="selected-service-preview mt-3" id="servicePreview">
                                <div class="ssp-icon"></div>
                                <div class="ssp-info">
                                    <strong>{{ $selectedService->name }}</strong>
                                    <span>{{ number_format($selectedService->getCurrentPrice(), 0, ',', ' ') }} FCFA · {{ $selectedService->duration }} min</span>
                                </div>
                            </div>
                            @else
                            <div class="selected-service-preview mt-3" id="servicePreview" style="display:none;">
                                <div class="ssp-icon"></div>
                                <div class="ssp-info">
                                    <strong id="previewName"></strong>
                                    <span id="previewMeta"></span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- STEP 2: Date & Time --}}
                    <div class="booking-step" id="step3">
                        <div class="step-header">
                            <div class="step-number" id="step3num">2</div>
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
                                           value="{{ old('date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-7 mb-3">
                                    <label><i class="fa fa-clock-o mr-1" style="color:var(--primary);"></i> Créneau</label>
                                    <input type="hidden" name="time" id="selected_time" value="{{ old('time') }}">
                                    <div id="slots-container" class="slots-wrapper">
                                        <div class="slots-placeholder">
                                            <i class="fa fa-hand-pointer-o"></i>
                                            <p>Sélectionnez un service et une date</p>
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
                    <div class="booking-step" id="step4">
                        <div class="step-header">
                            <div class="step-number" id="step4num">3</div>
                            <div>
                                <h4 class="step-title">Notes & demandes</h4>
                                <p class="step-subtitle">Optionnel — allergies, préférences...</p>
                            </div>
                        </div>
                        <div class="step-body">
                            <textarea name="notes" id="notes" class="form-control" rows="3"
                                      placeholder="Ex: Je suis allergique aux produits contenant du latex, coupe courte souhaitée...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="text-center mt-4 mb-5 d-lg-none">
                        <button type="submit" class="beauty-btn-primary" style="width:100%;justify-content:center;padding:16px 28px;font-size:16px;">
                            <i class="fa fa-calendar-check-o mr-2"></i>Confirmer mon rendez-vous
                        </button>
                    </div>
                </form>
            </div>

            {{-- SUMMARY SIDEBAR --}}
            <div class="col-lg-4">
                <div class="summary-card" id="summaryCard">
                    <div class="summary-header">
                        <i class="fa fa-shopping-bag"></i>
                        <h4>Récapitulatif</h4>
                    </div>
                    <div class="summary-body" id="summary">
                        <div class="summary-empty">
                            <div class="se-icon"></div>
                                                         <p>Sélectionnez un service pour commencer</p>
                        </div>
                    </div>
                    <div class="summary-footer" id="summaryFooter" style="display:none;">
                        <button type="submit" form="bookingForm" class="summary-cta">
                            <i class="fa fa-calendar-check-o mr-2"></i>Confirmer le RDV
                        </button>
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
/* Booking steps */
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
    background: var(--primary-soft); color: var(--primary);
    transition: all 0.3s;
}
.step-number.active {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white; box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}
.step-number.done {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}
.step-title { font-family: 'Playfair Display', serif; font-size: 18px; margin: 0; color: var(--dark); }
.step-subtitle { font-size: 13px; color: #8E8E8E; margin: 2px 0 0; }
.step-body { padding: 20px 24px; }

/* ===== Service search ===== */
.service-search-box {
    position: relative;
}
.service-search-box i {
    position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
    color: var(--primary); font-size: 14px;
}
.service-search-box input {
    padding-left: 42px !important;
}

/* ===== Service cards grid ===== */
.services-grid {
    display: grid; grid-template-columns: 1fr; gap: 10px;
    max-height: 380px; overflow-y: auto; padding-right: 4px;
}
.services-grid::-webkit-scrollbar { width: 5px; }
.services-grid::-webkit-scrollbar-track { background: transparent; }
.services-grid::-webkit-scrollbar-thumb { background: var(--primary-light); border-radius: 10px; }

.service-card {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 18px; border-radius: 14px;
    border: 2px solid rgba(0,0,0,0.06); background: white;
    cursor: pointer; transition: all 0.25s; position: relative;
}
.service-card:hover {
    border-color: var(--primary-light); background: var(--primary-soft);
    transform: translateX(4px);
}
.service-card.selected {
    border-color: var(--primary); background: var(--primary-soft);
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
}
.service-card-icon {
    width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--primary-soft), rgba(255,255,255,0.8));
    color: var(--primary); font-size: 18px;
    transition: all 0.25s;
}
.service-card.selected .service-card-icon,
.service-card:hover .service-card-icon {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    color: white;
}
.service-card-info { flex: 1; min-width: 0; }
.service-card-info h6 {
    margin: 0; font-size: 14px; font-weight: 600; color: var(--dark);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.service-card-desc {
    margin: 2px 0 0; font-size: 12px; color: #8E8E8E;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.service-card-meta {
    display: flex; align-items: center; gap: 12px; margin-top: 6px;
}
.service-price {
    font-size: 14px; font-weight: 700; color: var(--primary);
}
.service-duration {
    font-size: 12px; color: #8E8E8E; display: flex; align-items: center; gap: 4px;
}
.service-card-check {
    width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid rgba(0,0,0,0.1); color: transparent;
    font-size: 12px; transition: all 0.25s;
}
.service-card.selected .service-card-check {
    background: linear-gradient(135deg, var(--primary), var(--dark));
    border-color: var(--primary); color: white;
}

/* Selected service preview */
.selected-service-preview {
    display: flex; align-items: center; gap: 14px;
    padding: 14px 18px; border-radius: 14px;
    background: linear-gradient(135deg, var(--primary-soft), rgba(255,255,255,0.5));
    border: 1px solid var(--primary-light);
}
.ssp-icon { font-size: 28px; }
.ssp-info strong { display: block; color: var(--dark); font-size: 15px; }
.ssp-info span { font-size: 13px; color: var(--primary); font-weight: 500; }

/* Slots */
.slots-wrapper { min-height: 80px; }
.slots-placeholder {
    text-align: center; padding: 20px; color: #8E8E8E;
}
.slots-placeholder i { font-size: 28px; display: block; margin-bottom: 8px; opacity: 0.3; }
.slots-placeholder p { margin: 0; font-size: 13px; }

.slots-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.slot-btn {
    min-width: 90px; text-align: center; padding: 10px 14px;
    border-radius: 12px; cursor: pointer; transition: all 0.25s;
    font-weight: 600; font-size: 14px;
    border: 2px solid var(--primary-light); background: white; color: var(--dark);
}
.slot-btn:hover {
    transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.12);
    border-color: var(--primary); background: var(--primary-soft);
}
.slot-btn.selected {
    background: linear-gradient(135deg, var(--primary), var(--dark)) !important;
    border-color: var(--primary) !important; color: white !important;
    box-shadow: 0 4px 18px rgba(0,0,0,0.2);
}
.slot-btn .slot-sub {
    font-size: 10px; display: block; margin-top: 2px; opacity: 0.7; font-weight: 400;
}

/* Summary card */
.summary-card {
    background: white; border-radius: 18px; overflow: hidden;
    border: 1px solid rgba(0,0,0,0.04); box-shadow: 0 2px 16px rgba(0,0,0,0.06);
}
.summary-header {
    padding: 18px 24px;
    background: linear-gradient(135deg, var(--primary), var(--dark));
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
.info-card-icon { font-size: 28px; color: var(--primary); margin-bottom: 12px; }
.info-card h5 { font-family: 'Playfair Display', serif; margin-bottom: 16px; color: var(--dark); }
.info-card ul { list-style: none; padding: 0; margin: 0; text-align: left; }
.info-card li { padding: 8px 0; font-size: 14px; color: #555; display: flex; align-items: center; gap: 10px; }
.info-card li i { color: var(--primary); width: 18px; text-align: center; }

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
.dark-theme .selected-service-preview { background: #2a2555; border-color: var(--primary); }
.dark-theme .ssp-info strong { color: #E8E8E8; }

@media (min-width: 576px) {
    .services-grid { grid-template-columns: 1fr 1fr; }
}
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
    var summaryFooter = document.getElementById('summaryFooter');
    var servicePreview = document.getElementById('servicePreview');

    // Current selected service data
    var currentService = { id: null, name: '', price: 0, duration: 0 };

    // ===== Service card selection =====
    document.querySelectorAll('.service-card').forEach(function(card) {
        card.addEventListener('click', function() {
            document.querySelectorAll('.service-card').forEach(function(c) { c.classList.remove('selected'); });
            this.classList.add('selected');

            currentService.id = this.dataset.id;
            currentService.name = this.dataset.name;
            currentService.price = this.dataset.price;
            currentService.duration = this.dataset.duration;
            serviceInput.value = this.dataset.id;

            // Update preview
            var previewName = document.getElementById('previewName');
            var previewMeta = document.getElementById('previewMeta');
            if (previewName) previewName.textContent = currentService.name;
            if (previewMeta) previewMeta.textContent = Number(currentService.price).toLocaleString('fr-FR') + ' FCFA · ' + currentService.duration + ' min';
            servicePreview.style.display = 'flex';

            loadAvailableSlots();
            updateSummary();
            updateStepNumbers();
        });
    });

    // ===== Service search filter =====
    document.getElementById('serviceSearch').addEventListener('input', function() {
        var query = this.value.toLowerCase();
        document.querySelectorAll('.service-card').forEach(function(card) {
            var name = card.dataset.name.toLowerCase();
            card.style.display = name.indexOf(query) !== -1 ? '' : 'none';
        });
    });

    function updateStepNumbers() {
        var s3 = document.getElementById('step3num');
        var s4 = document.getElementById('step4num');
        if (serviceInput.value && dateInput.value) {
            s3.classList.add('active');
        }
        if (selectedTimeInput.value) {
            s4.classList.add('active');
            s3.classList.remove('active');
            s3.classList.add('done');
            s3.innerHTML = '<i class="fa fa-check"></i>';
        }
    }

    function loadAvailableSlots() {
        var serviceId = serviceInput.value;
        var date = dateInput.value;

        if (!serviceId || !date) {
            slotsContainer.innerHTML = '<div class="slots-placeholder"><i class="fa fa-hand-pointer-o"></i><p>Sélectionnez un service et une date</p></div>';
            return;
        }

        slotsContainer.innerHTML = '';
        slotsLoading.style.display = 'block';

        var url = '{{ route("client.appointments.available-slots") }}?service_id=' + serviceId + '&date=' + date;

        fetch(url)
            .then(function(response) { return response.json(); })
            .then(function(data) {
                slotsLoading.style.display = 'none';

                if (!data.slots || data.slots.length === 0) {
                    slotsContainer.innerHTML = '<div class="alert alert-warning mb-0"><i class="fa fa-exclamation-circle mr-2"></i>Aucun créneau disponible pour cette date. Essayez une autre date.</div>';
                    return;
                }

                var html = '<div class="slots-grid">';
                data.slots.forEach(function(slot) {
                    var time = slot.time;
                    var selectedClass = selectedTimeInput.value === time ? ' selected' : '';
                    var subText = '';

                    if (slot.employee_name) {
                        subText = '<span class="slot-sub">' + slot.employee_name + '</span>';
                    } else if (slot.employee_count) {
                        subText = '<span class="slot-sub">' + slot.employee_count + ' dispo.</span>';
                    }

                    html += '<button type="button" class="slot-btn' + selectedClass + '" data-time="' + time + '">' + time + subText + '</button>';
                });
                html += '</div>';

                slotsContainer.innerHTML = html;

                document.querySelectorAll('.slot-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.slot-btn').forEach(function(b) { b.classList.remove('selected'); });
                        this.classList.add('selected');
                        selectedTimeInput.value = this.dataset.time;
                        updateSummary();
                        updateStepNumbers();
                    });
                });
            })
            .catch(function(error) {
                console.error('Error loading slots:', error);
                slotsLoading.style.display = 'none';
                slotsContainer.innerHTML = '<div class="alert alert-danger mb-0"><i class="fa fa-exclamation-triangle mr-2"></i>Erreur lors du chargement des créneaux</div>';
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
                var formattedDate = new Date(date).toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'long' });
                html += '<div class="summary-item"><span class="summary-item-label"><i class="fa fa-calendar"></i> Date</span><span class="summary-item-value">' + formattedDate + '</span></div>';
            }
            if (time) {
                html += '<div class="summary-item"><span class="summary-item-label"><i class="fa fa-clock-o"></i> Heure</span><span class="summary-item-value">' + time + '</span></div>';
            }

            html += '<div class="summary-total"><span class="summary-total-label">Total</span><span class="summary-total-value">' + Number(currentService.price).toLocaleString('fr-FR') + ' FCFA</span></div>';

            summary.innerHTML = html;
            summaryFooter.style.display = 'block';
        } else {
            summary.innerHTML = '<div class="summary-empty"><div class="se-icon"></div><p>Sélectionnez un service pour commencer</p></div>';
            summaryFooter.style.display = 'none';
        }
    }

    dateInput.addEventListener('change', function() {
        loadAvailableSlots();
        updateSummary();
        updateStepNumbers();
    });

    // Init if service already selected
    var preselected = document.querySelector('.service-card.selected');
    if (preselected) {
        currentService.id = preselected.dataset.id;
        currentService.name = preselected.dataset.name;
        currentService.price = preselected.dataset.price;
        currentService.duration = preselected.dataset.duration;
        updateSummary();
        updateStepNumbers();
        if (dateInput.value) {
            loadAvailableSlots();
        }
    }
});
</script>
@endpush
@endsection
