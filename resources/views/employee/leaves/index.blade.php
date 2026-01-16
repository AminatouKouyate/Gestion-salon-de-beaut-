@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Mes Demandes de Congé</h4>
                    <p class="text-muted">Historique de vos demandes de congé</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Congés</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Liste de mes demandes de congé</h4>
                        <a href="{{ route('employee.leaves.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Nouvelle demande
                        </a>
                    </div>
                    <div class="card-body">
                        @if($leaveRequests->isEmpty())
                            <div class="text-center py-4">
                                <i class="fa fa-calendar-o fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune demande de congé trouvée</p>
                                <a href="{{ route('employee.leaves.create') }}" class="btn btn-primary">
                                    Faire une demande
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date de début</th>
                                            <th>Date de fin</th>
                                            <th>Raison</th>
                                            <th>Statut</th>
                                            <th>Date de demande</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaveRequests as $leave)
                                        <tr>
                                            <td>{{ $leave->start_date->format('d/m/Y') }}</td>
                                            <td>{{ $leave->end_date->format('d/m/Y') }}</td>
                                            <td>{{ Str::limit($leave->reason, 50) }}</td>
                                            <td>
                                                @if($leave->status == 'pending')
                                                    <span class="badge badge-warning">En attente</span>
                                                @elseif($leave->status == 'approved')
                                                    <span class="badge badge-success">Approuvé</span>
                                                @elseif($leave->status == 'rejected')
                                                    <span class="badge badge-danger">Rejeté</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $leave->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $leave->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                {{ $leaveRequests->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
