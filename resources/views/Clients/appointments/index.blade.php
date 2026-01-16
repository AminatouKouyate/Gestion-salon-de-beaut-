@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Mes Rendez-vous</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('client.appointments.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus mr-2"></i>Nouveau rendez-vous
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Rendez-vous à venir</h4>
                    </div>
                    <div class="card-body">
                        @if($appointments->isEmpty())
                            <div class="text-center py-5">
                                <i class="fa fa-calendar-o fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun rendez-vous à venir</p>
                                <a href="{{ route('client.appointments.create') }}" class="btn btn-primary">
                                    Prendre un rendez-vous
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Heure</th>
                                            <th>Service</th>
                                            <th>Employé</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->date->format('d/m/Y') }}</td>
                                            <td>{{ $appointment->time }}</td>
                                            <td>
                                                <strong>{{ $appointment->service->name ?? 'N/A' }}</strong>
                                                <br><small class="text-muted">{{ $appointment->service->price ?? 0 }} FCFA</small>
                                            </td>
                                            <td>{{ $appointment->employee->name ?? 'Non assigné' }}</td>
                                            <td>
                                                @switch($appointment->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning">En attente</span>
                                                        @break
                                                    @case('confirmed')
                                                        <span class="badge badge-success">Confirmé</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge badge-info">Terminé</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge badge-danger">Annulé</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('client.appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if(!in_array($appointment->status, ['completed', 'cancelled']))
                                                    <a href="{{ route('client.appointments.edit', $appointment) }}" class="btn btn-sm btn-warning">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('client.appointments.destroy', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('Annuler ce rendez-vous ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $appointments->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <a href="{{ route('client.appointments.history') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-history mr-2"></i>Voir l'historique complet
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
