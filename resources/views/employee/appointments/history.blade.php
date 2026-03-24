{{--
    Vue : Historique des rendez-vous employé
    Description : Liste paginée des rendez-vous passés de l'employé avec détails et statuts.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-history"></i></div>
                <div>
                    <h2 class="beauty-page-title">Historique des Rendez-vous</h2>
                    <p class="beauty-page-subtitle">Vos rendez-vous passés</p>
                </div>
            </div>
            <a href="{{ route('employee.appointments.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        @include('partials.success')

    <div class="row">
        <div class="col-lg-12">
            <div class="beauty-card">
                <div class="beauty-card-header">
                    <h4><i class="fa fa-history mr-2" style="color:var(--primary);"></i>Historique de mes Rendez-vous</h4>
                </div>
                <div class="beauty-card-body">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Client</th>
                                        <th>Service</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->scheduled_at->format('d/m/Y') }}</td>
                                            <td>{{ $appointment->scheduled_at->format('H:i') }}</td>
                                            <td>{{ $appointment->client->name }}</td>
                                            <td>{{ $appointment->service->name }}</td>
                                            <td>{!! $appointment->status_badge !!}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('employee.appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Voir
                                                    </a>
                                                    @if($appointment->status !== \App\Enums\AppointmentStatus::Completed && $appointment->status !== \App\Enums\AppointmentStatus::Canceled)
                                                        <form action="{{ route('employee.appointments.updateStatus', $appointment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="btn btn-sm btn-success" title="Marquer comme terminé">
                                                                <i class="fa fa-check"></i> Terminé
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if($appointment->status === \App\Enums\AppointmentStatus::Completed && !$appointment->payment)
                                                        <a href="{{ route('employee.payments.create', ['appointment' => $appointment->id]) }}" class="btn btn-sm btn-warning">
                                                            <i class="fa fa-money"></i> Encaisser
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $appointments->links() }}
                        </div>
                    @else
                        <div class="beauty-empty">
                            <i class="fa fa-calendar-times-o"></i>
                            <h5>Aucun rendez-vous passé</h5>
                            <p>Votre historique est vide pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
