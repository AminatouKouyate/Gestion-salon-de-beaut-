@extends('layouts.admin-master')

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<style>
    .fc-event-appointments { background-color: var(--primary) !important; border-color: var(--primary) !important; border-radius: 6px !important; }
    .fc-event-leaves { background-color: #f59e0b !important; border-color: #f59e0b !important; border-radius: 6px !important; }
    .fc-event-blocks { background-color: #94a3b8 !important; border-color: #94a3b8 !important; border-radius: 6px !important; }
    .fc .fc-toolbar-title { font-family: 'Playfair Display', serif; font-size: 1.25rem; color: var(--dark); }
    .fc .fc-button { padding: 0.4rem 0.65rem; font-size: 0.875rem; border-radius: 8px !important; }
    .calendar-legend { display: flex; gap: 1.5rem; flex-wrap: wrap; }
    .calendar-legend-item { display: flex; align-items: center; gap: 0.5rem; font-size: 13px; color: #555; }
    .calendar-legend-color { width: 14px; height: 14px; border-radius: 6px; }
</style>
@endpush

@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- WELCOME HEADER --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-clock-o"></i></div>
                <div>
                    <h2 class="beauty-page-title">Planning </h2>
                    <p class="beauty-page-subtitle">Gérez les horaires, congés et blocages</p>
                </div>
            </div>
            <button type="button" class="beauty-btn-primary" data-toggle="modal" data-target="#blockSlotModal">
                <i class="fa fa-ban mr-2"></i>Bloquer un créneau
            </button>
        </div>

        @include('partials.success')
@include('partials.error')

        {{-- CALENDAR --}}
        <div class="row">
            <div class="col-12">
                <div class="beauty-card">
                    <div class="beauty-card-header" style="flex-wrap:wrap;gap:12px;">
                        <div class="calendar-legend">
                            <div class="calendar-legend-item">
                                <div class="calendar-legend-color" style="background-color:var(--primary);"></div>
                                <span>Rendez-vous</span>
                            </div>
                            <div class="calendar-legend-item">
                                <div class="calendar-legend-color" style="background-color:#f59e0b;"></div>
                                <span>Congés</span>
                            </div>
                            <div class="calendar-legend-item">
                                <div class="calendar-legend-color" style="background-color:#94a3b8;"></div>
                                <span>Blocages</span>
                            </div>
                        </div>
                        <div>
                            <select id="employeeFilter" class="form-control" style="min-width:200px;">
                                <option value="">Tous les employés</option>
                                @foreach($employees ?? [] as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="beauty-card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal détails événement -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailModalLabel">Détails</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="eventDetailBody"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@include('admin.schedules._modal_block')

@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var employeeFilter = document.getElementById('employeeFilter');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour'
        },
        slotMinTime: '07:00:00',
        slotMaxTime: '21:00:00',
        allDaySlot: false,
        nowIndicator: true,
        events: function(info, successCallback, failureCallback) {
            var employeeId = employeeFilter.value;
            var url = '{{ route("admin.schedules.events") }}';
            var params = new URLSearchParams({
                start: info.startStr,
                end: info.endStr
            });
            if (employeeId) {
                params.append('employee_id', employeeId);
            }

            fetch(url + '?' + params.toString())
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => failureCallback(error));
        },
        eventClassNames: function(arg) {
            var type = arg.event.extendedProps.type || 'appointments';
            return ['fc-event-' + type];
        },
        eventClick: function(info) {
            var event = info.event;
            var props = event.extendedProps;
            var html = '<table class="table table-sm table-borderless mb-0">';
            html += '<tr><th>Titre</th><td>' + event.title + '</td></tr>';
            html += '<tr><th>Début</th><td>' + event.start.toLocaleString('fr-FR') + '</td></tr>';
            if (event.end) {
                html += '<tr><th>Fin</th><td>' + event.end.toLocaleString('fr-FR') + '</td></tr>';
            }
            if (props.employee_name) html += '<tr><th>Employé</th><td>' + props.employee_name + '</td></tr>';
            if (props.client_name) html += '<tr><th>Client</th><td>' + props.client_name + '</td></tr>';
            if (props.service_name) html += '<tr><th>Service</th><td>' + props.service_name + '</td></tr>';
            if (props.reason) html += '<tr><th>Raison</th><td>' + props.reason + '</td></tr>';
            if (props.status) {
                var statusLabels = { 'pending': 'En attente', 'confirmed': 'Confirmé', 'completed': 'Terminé', 'canceled': 'Annulé', 'cancelled': 'Annulé', 'no-show': 'Absent', 'no_show': 'Absent' };
                html += '<tr><th>Statut</th><td>' + (statusLabels[props.status] || props.status) + '</td></tr>';
            }
            html += '</table>';

            document.getElementById('eventDetailBody').innerHTML = html;
            var typeLabels = { appointments: 'Rendez-vous', leaves: 'Congé', blocks: 'Blocage' };
            document.getElementById('eventDetailModalLabel').textContent = typeLabels[props.type] || 'Détails';
            $('#eventDetailModal').modal('show');
        }
    });

    calendar.render();

    employeeFilter.addEventListener('change', function() {
        calendar.refetchEvents();
    });
});
</script>
@endpush
