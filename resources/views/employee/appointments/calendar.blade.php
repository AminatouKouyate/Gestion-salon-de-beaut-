{{--
    Vue : Calendrier des rendez-vous employé
    Description : Affiche un calendrier interactif avec les rendez-vous assignés à l'employé connecté.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-calendar"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mon Planning</h2>
                    <p class="beauty-page-subtitle">Visualisez votre calendrier</p>
                </div>
            </div>
        </div>

        <div id="calendar"></div>
    </div>
</div>

<!-- FullCalendar CSS/JS via CDN -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        navLinks: true,
        nowIndicator: true,
        weekNumbers: false,
        editable: false,
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        events: {
            url: '{{ route('employee.appointments.events') }}',
            method: 'GET'
        },
        eventClick: function(info) {
            var url = info.event.url || (info.event.extendedProps && info.event.extendedProps.url);
            if (url) {
                window.location.href = url;
            }
        }
    });

    calendar.render();
});
</script>

@endsection
