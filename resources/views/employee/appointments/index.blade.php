{{--
    Vue : Liste des rendez-vous employé
    Description : Page principale des rendez-vous de l'employé : liste des rendez-vous à venir et en cours avec actions disponibles.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-calendar"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mes Rendez-vous</h2>
                    <p class="beauty-page-subtitle">Gérez vos rendez-vous assignés</p>
                </div>
            </div>
        </div>

        @include('partials.success')
        @include('partials.error')

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

        <div class="row">
            <div class="col-12">
                <div class="beauty-card">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-calendar mr-2" style="color:var(--primary);"></i>
                            @if($view == 'daily')
                                Rendez-vous d'Aujourd'hui
                            @elseif($view == 'weekly')
                                Rendez-vous de la Semaine
                            @else
                                Liste des Rendez-vous à Venir
                            @endif
                        </h4>
                    </div>
                    <div class="beauty-card-body">
                        @if($appointments->isEmpty())
                            <div class="beauty-empty">
                                <i class="fa fa-calendar-o"></i>
                                <h5>Aucun rendez-vous</h5>
                                <p>Vous n'avez pas de rendez-vous planifiés pour le moment.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
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
                                            <td>
                                                <strong>{{ $appointment->scheduled_at->format('d/m/Y') }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $appointment->scheduled_at->locale('fr')->isoFormat('dddd') }}</small>
                                            </td>
                                            <td><strong class="text-primary">{{ $appointment->scheduled_at->format('H:i') }}</strong></td>
                                            <td>
                                                {{ $appointment->client->name ?? '�' }}
                                                @if($appointment->client && $appointment->client->email)
                                                    <br><small class="text-muted">{{ $appointment->client->email }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $appointment->client->phone ?? '�' }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $appointment->service->name ?? '�' }}</span>
                                            </td>
                                            <td>{{ $appointment->service->duration ?? '�' }} min</td>
                                            <td>
                                                {!! $appointment->status_badge !!}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('employee.appointments.show', $appointment) }}"
                                                       class="btn btn-sm btn-primary"
                                                       title="Voir détails">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    @if($appointment->status->value != 'completed' && $appointment->status->value != 'canceled')
                                                    <button type="button"
                                                            class="btn btn-sm btn-success"
                                                            data-toggle="modal"
                                                            data-target="#confirmStatusModal"
                                                            data-appointment-id="{{ $appointment->id }}"
                                                            title="Marquer comme terminé">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    @endif

                                                    @if($appointment->status->value == 'completed' && !$appointment->payment)
                                                    <a href="{{ route('employee.payments.create', ['appointment' => $appointment->id]) }}"
                                                       class="btn btn-sm btn-warning"
                                                       title="Encaisser le paiement">
                                                        <i class="fa fa-money"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
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
        var button = $(event.relatedTarget);
        var appointmentId = button.data('appointment-id');
        var form = document.getElementById('statusForm');
        form.action = `/employee/appointments/${appointmentId}/status`;
    });
});
</script>
@endsection
