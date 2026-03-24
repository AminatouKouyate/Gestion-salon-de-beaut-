{{--
    Vue : Services de l'employé
    Description : Liste des services que l'employé est habilité à effectuer avec les détails de chaque prestation.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-scissors"></i></div>
                <div>
                    <h2 class="beauty-page-title">Services du Salon</h2>
                    <p class="beauty-page-subtitle">Liste des services proposés</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="beauty-card">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-calendar mr-2" style="color:var(--primary);"></i>Rendez-vous d'Aujourd'hui - {{ now()->format('d/m/Y') }}</h4>
                    </div>
                    <div class="beauty-card-body">
                        @if($todayAppointments->isEmpty())
                            <div class="beauty-empty">
                                <div class="beauty-empty-icon"><i class="fa fa-calendar-o"></i></div>
                                <p>Aucun service prévu pour aujourd'hui</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Heure</th>
                                            <th>Client</th>
                                            <th>Service</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todayAppointments as $appointment)
                                        <tr>
                                            <td><strong>{{ $appointment->time }}</strong></td>
                                            <td>{{ $appointment->client->name ?? '—' }}</td>
                                            <td>{{ $appointment->service->name ?? '—' }}</td>
                                            <td>{!! $appointment->status_badge !!}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('employee.appointments.show', $appointment) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye"></i> Voir
                                                    </a>
                                                    @if($appointment->status->value != 'completed')
                                                    <form action="{{ route('employee.appointments.updateStatus', $appointment) }}" method="POST" class="d-inline confirm-delete" data-confirm-message="Marquer ce service comme terminé ?">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fa fa-check"></i> Terminer
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
