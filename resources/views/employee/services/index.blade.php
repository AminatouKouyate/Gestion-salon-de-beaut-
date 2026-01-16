@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Services d'Aujourd'hui</h4>
                    <p class="text-muted">Liste des services à réaliser aujourd'hui</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Services</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Rendez-vous d'Aujourd'hui - {{ now()->format('d/m/Y') }}</h4>
                    </div>
                    <div class="card-body">
                        @if($todayAppointments->isEmpty())
                            <div class="text-center py-4">
                                <i class="fa fa-calendar-o fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun service prévu pour aujourd'hui</p>
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
                                            <td>{{ $appointment->client->name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($appointment->status == 'pending')
                                                    <span class="badge badge-warning">En attente</span>
                                                @elseif($appointment->status == 'confirmed')
                                                    <span class="badge badge-info">Confirmé</span>
                                                @elseif($appointment->status == 'completed')
                                                    <span class="badge badge-success">Terminé</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $appointment->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('employee.appointments.show', $appointment) }}" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye"></i> Voir
                                                    </a>
                                                    @if($appointment->status != 'completed')
                                                    <form action="{{ route('employee.appointments.updateStatus', $appointment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Marquer ce service comme terminé ?')">
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
