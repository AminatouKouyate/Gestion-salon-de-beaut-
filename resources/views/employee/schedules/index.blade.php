{{--
    Vue : Gestion des horaires employé
    Description : Vue d'ensemble des horaires de travail de l'employé avec possibilité de modification.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-clock-o"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mon Planning</h2>
                    <p class="beauty-page-subtitle">Visualisez vos rendez-vous, congés et indisponibilités</p>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <div class="btn-group">
                    <a href="{{ route('employee.schedules.working-hours') }}" class="btn btn-outline-primary">
                        <i class="fa fa-clock-o mr-1"></i> Mes horaires
                    </a>
                    <a href="{{ route('employee.schedules.days-off') }}" class="btn btn-outline-warning">
                        <i class="fa fa-calendar-times-o mr-1"></i> Mes congés
                    </a>
                    <a href="{{ route('employee.leaves.create') }}" class="btn btn-outline-success">
                        <i class="fa fa-plus mr-1"></i> Demander un congé
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Calendrier</h4>
                        <div class="legend d-flex align-items-center flex-wrap">
                            <span class="badge badge-warning mr-2 mb-1"><i class="fa fa-circle"></i> En attente</span>
                            <span class="badge badge-info mr-2 mb-1"><i class="fa fa-circle"></i> Confirmé</span>
                            <span class="badge badge-success mr-2 mb-1"><i class="fa fa-circle"></i> Terminé</span>
                            <span class="badge badge-danger mr-2 mb-1"><i class="fa fa-circle"></i> Annulé</span>
                            <span class="badge badge-secondary mr-2 mb-1"><i class="fa fa-circle"></i> Blocage</span>
                            <span class="badge" style="background-color:#ffc107;color:#000;"><i class="fa fa-circle"></i> Congé</span>
                        </div>
                    </div>
                    <div class="card-body">
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
            <div class="modal-body" id="eventDetailContent">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <a href="#" id="eventDetailLink" class="btn btn-primary" style="display:none;">Voir détails</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js"></script>

<style>
    #calendar {
        max-width: 100%;
    }
    .fc-event {
        cursor: pointer;
    }
    .fc-event-title {
        font-weight: 500;
    }
    .event-detail-row {
        margin-bottom: 8px;
    }
    .event-detail-label {
        font-weight: 600;
        color: #6c757d;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: '{{ $view === "month" ? "dayGridMonth" : "timeGridWeek" }}',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: '07:00:00',
        slotMaxTime: '21:00:00',
        allDaySlot: true,
        nowIndicator: true,
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        events: {
            url: '{{ route("employee.schedules.events") }}',
            failure: function() {
                alert('Erreur lors du chargement des événements.');
            }
        },
        eventClick: function(info) {
            var event = info.event;
            var props = event.extendedProps;
            var content = '';

            if (props.type === 'appointment') {
                content = `
                    <div class="event-detail-row">
                        <span class="event-detail-label">Client:</span> ${props.client}
                    </div>
                    <div class="event-detail-row">
                        <span class="event-detail-label">Service:</span> ${props.service}
                    </div>
                    <div class="event-detail-row">
                        <span class="event-detail-label">Statut:</span> ${props.status}
                    </div>
                    <div class="event-detail-row">
                        <span class="event-detail-label">Prix:</span> ${props.price} FCFA
                    </div>
                    <div class="event-detail-row">
                        <span class="event-detail-label">Heure:</span> ${event.start.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})} - ${event.end ? event.end.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'}) : ''}
                    </div>
                `;
                var aptId = event.id.replace('apt-', '');
                $('#eventDetailLink').attr('href', '{{ url("employee/appointments") }}/' + aptId).show();
                $('#eventDetailModalLabel').text('Rendez-vous');
            } else if (props.type === 'leave') {
                content = `
                    <div class="event-detail-row">
                        <span class="event-detail-label">Type:</span> Congé approuvé
                    </div>
                    <div class="event-detail-row">
                        <span class="event-detail-label">Période:</span> ${event.startStr} - ${event.endStr ? new Date(new Date(event.endStr).getTime() - 86400000).toLocaleDateString('fr-FR') : event.startStr}
                    </div>
                    ${props.reason ? `<div class="event-detail-row"><span class="event-detail-label">Motif:</span> ${props.reason}</div>` : ''}
                `;
                $('#eventDetailLink').hide();
                $('#eventDetailModalLabel').text('Congé');
            } else if (props.type === 'blocked') {
                content = `
                    <div class="event-detail-row">
                        <span class="event-detail-label">Type:</span> Créneau bloqué
                    </div>
                    ${props.reason ? `<div class="event-detail-row"><span class="event-detail-label">Raison:</span> ${props.reason}</div>` : ''}
                    <div class="event-detail-row">
                        <span class="event-detail-label">Heure:</span> ${event.start.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})} - ${event.end ? event.end.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'}) : ''}
                    </div>
                `;
                $('#eventDetailLink').hide();
                $('#eventDetailModalLabel').text('Indisponibilité');
            }

            $('#eventDetailContent').html(content);
            $('#eventDetailModal').modal('show');
        }
    });

    calendar.render();
});
</script>
@endpush
