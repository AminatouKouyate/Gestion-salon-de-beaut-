@extends('layouts.dashboard') {{-- Assurez-vous d'avoir un layout adapté --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Historique de mes Rendez-vous</h4>
                    <a href="{{ route('employee.appointments.index') }}" class="btn btn-primary btn-sm">Voir les RDV à venir</a>
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
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
                                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</td>
                                            <td>{{ $appointment->client->name }}</td>
                                            <td>{{ $appointment->service->name }}</td>
                                            <td>
                                                <span class="badge badge-{{ $appointment->status_badge }}">
                                                    {{ $appointment->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('employee.appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
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
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5>Aucun rendez-vous passé</h5>
                            <p>Votre historique est vide pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
