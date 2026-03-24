{{--
    Vue : Liste des congés employé
    Description : Affiche l'historique des demandes de congé de l'employé avec statuts (en attente, approuvé, refusé).
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-plane"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mes Demandes de Congé</h2>
                    <p class="beauty-page-subtitle">Historique de vos demandes de congé</p>
                </div>
            </div>
            <a href="{{ route('employee.leaves.create') }}" class="beauty-btn-primary"><i class="fa fa-plus mr-2"></i>Nouvelle demande</a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="beauty-card">
                    <div class="beauty-card-header">
                        <h4><i class="fa fa-plane mr-2" style="color:var(--primary);"></i>Liste de mes demandes de congé</h4>
                    </div>
                    <div class="beauty-card-body">
                        @if($leaveRequests->isEmpty())
                            <div class="beauty-empty">
                                <i class="fa fa-calendar-o"></i>
                                <h5>Aucune demande de congé</h5>
                                <p>Aucune demande de congé trouvée</p>
                                <a href="{{ route('employee.leaves.create') }}" class="beauty-btn-primary">
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
                                            <th>Réponse de l'admin</th>
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
                                            <td>
                                                @if($leave->admin_response)
                                                    <span title="{{ $leave->admin_response }}">{{ Str::limit($leave->admin_response, 20) }}</span>
                                                @else
                                                    -
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
