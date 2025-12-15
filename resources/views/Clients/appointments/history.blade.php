@extends('master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Historique des rendez-vous</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tous vos rendez-vous</h4>
                    </div>
                    <div class="card-body">
                        @if($appointments->isEmpty())
                            <div class="text-center py-5">
                                <i class="fa fa-history fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun historique de rendez-vous</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Service</th>
                                            <th>Employé</th>
                                            <th>Prix</th>
                                            <th>Status</th>
                                            <th>Paiement</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appointments as $appointment)
                                        <tr>
                                            <td>
                                                {{ $appointment->date->format('d/m/Y') }}<br>
                                                <small class="text-muted">{{ $appointment->time }}</small>
                                            </td>
                                            <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->employee->name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->service->price ?? 0 }}€</td>
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
                                                @if($appointment->payment)
                                                    <span class="badge badge-{{ $appointment->payment->status == 'completed' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($appointment->payment->status) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if($appointment->payment && $appointment->payment->status == 'completed')
                                                    <a href="{{ route('payments.invoice', $appointment->payment) }}" class="btn btn-sm btn-secondary">
                                                        <i class="fa fa-file-pdf-o"></i>
                                                    </a>
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
    </div>
</div>
@endsection
