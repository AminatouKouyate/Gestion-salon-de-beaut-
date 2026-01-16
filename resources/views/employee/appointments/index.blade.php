@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Mes Rendez-vous</h4>
                    <p class="text-muted">Gérez vos rendez-vous assignés</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">

                    <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Rendez-vous</li>
                </ol>
            </div>
        @if(session('success'))

        <!-- Filtres de vue -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="btn-group" role="group" aria-label="Filtres de vue">
                    <a href="{{ route('employee.appointments.index', ['view' => 'upcoming']) }}"
                       class="btn {{ $view == 'upcoming' ? 'btn-primary' : 'btn-outline-primary' }}">
                        À venir
                    </a>
                    <a href="{{ route('employee.appointments.index', ['view' => 'daily']) }}"
                       class="btn {{ $view == 'daily' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Aujourd'hui
                    </a>
                    <a href="{{ route('employee.appointments.index', ['view' => 'weekly']) }}"
                       class="btn {{ $view == 'weekly' ? 'btn-primary' : 'btn-outline-primary' }}">
                        Cette semaine
                    </a>
                </div>
            </div>
        </div>
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong>Succès!</strong> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            @if($view == 'daily')
                                Rendez-vous d'Aujourd'hui
                            @elseif($view == 'weekly')
                                Rendez-vous de la Semaine
                            @else
                                Liste des Rendez-vous à Venir
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        @if($appointments->isEmpty())
                            <div class="text-center py-5">
                                <i class="fa fa-calendar-o fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Aucun rendez-vous à venir</h5>
                                <p class="text-muted">Vous n'avez pas de rendez-vous planifiés pour le moment.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Heure</th>
                                            <th>Client</th>
                                            <th>Téléphone</th>
                                            <th>Service</th>
                                            <th>Durée</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->id }}</td>
                                            <td>
                                                <strong>{{ $appointment->date->format('d/m/Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $appointment->date->locale('fr')->isoFormat('dddd') }}</small>
                                            </td>
                                            <td><strong class="text-primary">{{ $appointment->time }}</strong></td>
                                            <td>
                                                {{ $appointment->client->name ?? 'N/A' }}
                                                @if($appointment->client && $appointment->client->email)
                                                    <br><small class="text-muted">{{ $appointment->client->email }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $appointment->client->phone ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $appointment->service->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>{{ $appointment->service->duration ?? 'N/A' }} min</td>
                                            <td>
                                                {!! $appointment->getStatusBadge() !!}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('employee.appointments.show', $appointment) }}"
                                                       class="btn btn-sm btn-primary"
                                                       title="Voir détails">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    @if($appointment->status != 'completed' && $appointment->status != 'canceled')
                                                    <button type="button"
                                                            class="btn btn-sm btn-success"
                                                            data-toggle="modal"
                                                            data-target="#confirmStatusModal"
                                                            data-appointment-id="{{ $appointment->id }}"
                                                            title="Marquer comme terminé">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $appointments->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation pour changer le statut -->
<div class="modal fade" id="confirmStatusModal" tabindex="-1" role="dialog" aria-labelledby="confirmStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmStatusModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir marquer ce rendez-vous comme terminé ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-success">Confirmer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#confirmStatusModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Bouton qui a déclenché le modal
        var appointmentId = button.data('appointment-id'); // Extraire l'ID du rendez-vous

        var form = document.getElementById('statusForm');
        form.action = `/employee/appointments/${appointmentId}/status`;
    });
});
</script>
@endsection
