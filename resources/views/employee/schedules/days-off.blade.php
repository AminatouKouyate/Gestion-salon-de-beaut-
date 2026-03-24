{{--
    Vue : Jours de repos de l'employé
    Description : Gestion des jours de repos et d'indisponibilité de l'employé.
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-calendar-times-o"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mes Congés</h2>
                    <p class="beauty-page-subtitle">Visualisez vos congés approuvés</p>
                </div>
            </div>
            <div>
                <a href="{{ route('employee.schedules.index') }}" class="btn btn-secondary mr-2"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
                <a href="{{ route('employee.leaves.create') }}" class="beauty-btn-primary"><i class="fa fa-plus mr-2"></i>Demander un congé</a>
            </div>
        </div>

        <div class="row">
            <!-- Congés à venir -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-calendar-plus-o mr-2"></i>Congés à venir
                        </h4>
                    </div>
                    <div class="card-body">
                        @if($upcomingLeaves->isEmpty())
                            <div class="alert alert-info mb-0">
                                <i class="fa fa-info-circle mr-2"></i>
                                Aucun congé à venir.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Période</th>
                                            <th>Durée</th>
                                            <th>Motif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingLeaves as $leave)
                                            @php
                                                $days = $leave->start_date->diffInDays($leave->end_date) + 1;
                                                $isOngoing = $leave->start_date->lte(now()) && $leave->end_date->gte(now());
                                            @endphp
                                            <tr class="{{ $isOngoing ? 'table-warning' : '' }}">
                                                <td>
                                                    @if($isOngoing)
                                                        <span class="badge badge-warning mb-1">En cours</span><br>
                                                    @endif
                                                    <strong>{{ $leave->start_date->format('d/m/Y') }}</strong>
                                                    @if($leave->start_date->ne($leave->end_date))
                                                        <br><small class="text-muted">au {{ $leave->end_date->format('d/m/Y') }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary">
                                                        {{ $days }} jour{{ $days > 1 ? 's' : '' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $leave->reason ?? '-' }}
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

            <!-- Historique -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-history mr-2"></i>Historique des congés
                        </h4>
                    </div>
                    <div class="card-body">
                        @if($pastLeaves->isEmpty())
                            <div class="alert alert-secondary mb-0">
                                <i class="fa fa-info-circle mr-2"></i>
                                Aucun congé passé.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>Période</th>
                                            <th>Durée</th>
                                            <th>Motif</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pastLeaves as $leave)
                                            @php
                                                $days = $leave->start_date->diffInDays($leave->end_date) + 1;
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ $leave->start_date->format('d/m/Y') }}
                                                    @if($leave->start_date->ne($leave->end_date))
                                                        <br><small class="text-muted">au {{ $leave->end_date->format('d/m/Y') }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary">
                                                        {{ $days }} jour{{ $days > 1 ? 's' : '' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small>{{ Str::limit($leave->reason, 30) ?? '-' }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-muted">
                                <i class="fa fa-info-circle mr-1"></i>
                                Affichage des 10 derniers congés
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-bar-chart mr-2"></i>Résumé de l'année
                        </h4>
                    </div>
                    <div class="card-body">
                        @php
                            $yearStart = now()->startOfYear();
                            $yearEnd = now()->endOfYear();

                            $yearLeaves = $employee->leaveRequests()
                                ->where('status', 'approved')
                                ->where('start_date', '>=', $yearStart)
                                ->where('start_date', '<=', $yearEnd)
                                ->get();

                            $totalDays = 0;
                            foreach($yearLeaves as $leave) {
                                $totalDays += $leave->start_date->diffInDays($leave->end_date) + 1;
                            }
                        @endphp

                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <h2 class="text-primary mb-0">{{ $yearLeaves->count() }}</h2>
                                    <small class="text-muted">Demandes approuvées en {{ now()->year }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <h2 class="text-success mb-0">{{ $totalDays }}</h2>
                                    <small class="text-muted">Jours de congé pris en {{ now()->year }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <h2 class="text-warning mb-0">{{ $upcomingLeaves->count() }}</h2>
                                    <small class="text-muted">Congés à venir</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liens rapides -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap">
                            <a href="{{ route('employee.leaves.index') }}" class="btn btn-outline-primary mr-2 mb-2">
                                <i class="fa fa-list mr-1"></i> Toutes mes demandes
                            </a>
                            <a href="{{ route('employee.leaves.create') }}" class="btn btn-success mr-2 mb-2">
                                <i class="fa fa-plus mr-1"></i> Nouvelle demande
                            </a>
                            <a href="{{ route('employee.schedules.working-hours') }}" class="btn btn-outline-info mr-2 mb-2">
                                <i class="fa fa-clock-o mr-1"></i> Mes horaires
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
