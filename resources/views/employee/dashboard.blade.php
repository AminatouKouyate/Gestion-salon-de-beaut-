@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Bienvenue, {{ $employee->name }} !</h4>
                    <p class="text-muted">Tableau de bord employé</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('employee.dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card gradient-1">
                    <div class="card-body">
                        <h3 class="card-title text-white">RDV Aujourd'hui</h3>
                        <div class="d-inline-block">
                            <h2 class="text-white">{{ $todayAppointments->count() }}</h2>
                            <p class="text-white mb-0">Rendez-vous du jour</p>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-calendar-check-o"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card gradient-2">
                    <div class="card-body">
                        <h3 class="card-title text-white">RDV à Venir</h3>
                        <div class="d-inline-block">
                            <h2 class="text-white">{{ $upcomingAppointments->count() }}</h2>
                            <p class="text-white mb-0">Rendez-vous planifiés</p>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card gradient-3">
                    <div class="card-body">
                        <h3 class="card-title text-white">Total RDV</h3>
                        <div class="d-inline-block">
                            <h2 class="text-white">{{ $totalAppointments }}</h2>
                            <p class="text-white mb-0">Services effectués</p>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-check-circle"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card gradient-4">
                    <div class="card-body">
                        <h3 class="card-title text-white">Notifications</h3>
                        <div class="d-inline-block">
                            <h2 class="text-white">{{ $unreadCount }}</h2>
                            <p class="text-white mb-0">Messages non lus</p>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-bell"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Actions Rapides -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Actions Rapides</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('employee.appointments.index') }}" class="btn btn-primary btn-block mb-2">
                            <i class="fa fa-calendar mr-2"></i>Mes Rendez-vous
                        </a>
                        <a href="{{ route('employee.services.index') }}" class="btn btn-info btn-block mb-2">
                            <i class="fa fa-scissors mr-2"></i>Services
                        </a>
                        <a href="{{ route('employee.leaves.create') }}" class="btn btn-warning btn-block mb-2">
                            <i class="fa fa-plane mr-2"></i>Demander un Congé
                        </a>
                        <a href="{{ route('employee.notifications.index') }}" class="btn btn-secondary btn-block">
                            <i class="fa fa-bell mr-2"></i>Notifications
                            @if($unreadCount > 0)
                                <span class="badge badge-danger">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                @if($pendingLeaves > 0)
                <div class="card mt-3 border-warning">
                    <div class="card-body text-center">
                        <i class="fa fa-clock-o fa-2x text-warning mb-2"></i>
                        <h5 class="text-warning">Demandes en attente</h5>
                        <p class="text-muted mb-0">Vous avez {{ $pendingLeaves }} demande(s) de congé en attente</p>
                        <a href="{{ route('employee.leaves.index') }}" class="btn btn-sm btn-warning mt-2">Voir les demandes</a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Rendez-vous du Jour -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Rendez-vous d'Aujourd'hui</h4>
                    </div>
                    <div class="card-body">
                        @if($todayAppointments->isEmpty())
                            <div class="text-center py-4">
                                <i class="fa fa-calendar-o fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun rendez-vous aujourd'hui</p>
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
                                                <a href="{{ route('employee.appointments.show', $appointment) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Notifications Récentes -->
                @if($unreadNotifications->isNotEmpty())
                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <h4 class="card-title text-white mb-0">
                            <i class="fa fa-bell mr-2"></i>Notifications Récentes
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($unreadNotifications as $notification)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $notification->title }}</h6>
                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $notification->message }}</p>
                            </div>
                            @endforeach
                        </div>
                        <a href="{{ route('employee.notifications.index') }}" class="btn btn-outline-info btn-sm mt-3">
                            Voir toutes les notifications
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Prochains Rendez-vous -->
        @if($upcomingAppointments->isNotEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Prochains Rendez-vous</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                    @foreach($upcomingAppointments as $appointment)
                                    <tr>
                                        <td>{{ $appointment->date->format('d/m/Y') }}</td>
                                        <td>{{ $appointment->time }}</td>
                                        <td>{{ $appointment->client->name ?? 'N/A' }}</td>
                                        <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($appointment->status == 'pending')
                                                <span class="badge badge-warning">En attente</span>
                                            @elseif($appointment->status == 'confirmed')
                                                <span class="badge badge-info">Confirmé</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $appointment->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('employee.appointments.show', $appointment) }}" class="btn btn-sm btn-primary">
                                                Voir détails
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('employee.appointments.index') }}" class="btn btn-outline-primary btn-sm">
                            Voir tous les rendez-vous
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
