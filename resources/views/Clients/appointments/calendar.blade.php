{{--
    Vue : Calendrier des rendez-vous client
    Description : Affiche un calendrier interactif (FullCalendar) avec tous les rendez-vous du client, une légende des statuts et un modal de détails au clic sur un événement.
--}}
@extends('layouts.client-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-calendar"></i></div>
                <div>
                    <h2 class="beauty-page-title">Calendrier</h2>
                    <p class="beauty-page-subtitle">Visualisez tous vos rendez-vous</p>
                </div>
            </div>
            <div class="d-flex flex-wrap" style="gap:10px;">
                <a href="{{ route('client.appointments.index') }}" class="btn btn-secondary"><i class="fa fa-list mr-1"></i> Liste des rendez-vous</a>
                <a href="{{ route('client.appointments.create') }}" class="beauty-btn-primary"><i class="fa fa-plus mr-1"></i> Nouveau rendez-vous</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Légende</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge mr-2" style="background-color: #ffc107; width: 20px; height: 20px;">&nbsp;</span>
                            <span>En attente</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge mr-2" style="background-color: #17a2b8; width: 20px; height: 20px;">&nbsp;</span>
                            <span>Confirmé</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge mr-2" style="background-color: #28a745; width: 20px; height: 20px;">&nbsp;</span>
                            <span>Terminé</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge mr-2" style="background-color: #dc3545; width: 20px; height: 20px;">&nbsp;</span>
                            <span>Annulé</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Détails -->
<div class="modal fade" id="eventDetailModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventDetailModalLabel">Détails du rendez-vous</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 40%;">Service</th>
                        <td id="modal-service"></td>
                    </tr>
                    <tr>
                        <th>Employé</th>
                        <td id="modal-employee"></td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td id="modal-date"></td>
                    </tr>
                    <tr>
                        <th>Heure</th>
                        <td id="modal-time"></td>
                    </tr>
                    <tr>
                        <th>Statut</th>
                        <td id="modal-status"></td>
                    </tr>
                    <tr>
                        <th>Prix</th>
                        <td id="modal-price"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <a href="#" id="modal-view-link" class="btn btn-info">Voir détails</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Chargement des ressources FullCalendar --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    // Initialisation du calendrier FullCalendar en français
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        // Configuration de la barre d'outils (navigation, titre, vues)
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        // Traduction des boutons en français
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste'
        },
        // Chargement des événements depuis l'API
        events: {
            url: '{{ route("client.appointments.calendar.events") }}',
            failure: function() {
                alert('Erreur lors du chargement des rendez-vous.');
            }
        },
        // Gestion du clic sur un événement : affichage du modal de détails
        eventClick: function(info) {
            var event = info.event;
            var props = event.extendedProps;
            
            var startDate = event.start;
            var dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            var timeOptions = { hour: '2-digit', minute: '2-digit' };
            
            // Remplissage des champs du modal avec les données du rendez-vous
            document.getElementById('modal-service').textContent = props.service;
            document.getElementById('modal-employee').textContent = props.employee;
            document.getElementById('modal-date').textContent = startDate.toLocaleDateString('fr-FR', dateOptions);
            document.getElementById('modal-time').textContent = startDate.toLocaleTimeString('fr-FR', timeOptions);
            
            // Correspondance des statuts en français avec badges colorés
            var statusLabels = {
                'pending': 'En attente',
                'confirmed': 'Confirmé',
                'completed': 'Terminé',
                'canceled': 'Annulé',
                'cancelled': 'Annulé'
            };
            var statusBadges = {
                'pending': 'badge-warning',
                'confirmed': 'badge-info',
                'completed': 'badge-success',
                'canceled': 'badge-danger',
                'cancelled': 'badge-danger'
            };
            
            var statusHtml = '<span class="badge ' + (statusBadges[props.status] || 'badge-secondary') + '">' + 
                             (statusLabels[props.status] || props.status) + '</span>';
            document.getElementById('modal-status').innerHTML = statusHtml;
            
            document.getElementById('modal-price').textContent = props.price + ' FCFA';
            document.getElementById('modal-view-link').href = '{{ url("client/appointments") }}/' + event.id;
            
            $('#eventDetailModal').modal('show');
        },
        // Curseur pointeur sur les événements cliquables
        eventDidMount: function(info) {
            info.el.style.cursor = 'pointer';
        }
    });
    
    // Rendu du calendrier dans le conteneur
    calendar.render();
});
</script>

{{-- Styles du calendrier --}}
<style>
#calendar {
    max-width: 100%;
}
.fc-event {
    cursor: pointer;
}
.fc-toolbar-title {
    text-transform: capitalize;
}
</style>
@endpush
