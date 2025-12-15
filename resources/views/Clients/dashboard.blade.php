@extends('master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Bienvenue, {{ Auth::guard('clients')->user()->name ?? 'Client' }} !</h4>
                    <p class="text-muted">Gérez vos rendez-vous et votre compte</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card gradient-1">
                    <div class="card-body">
                        <h3 class="card-title text-white">Points Fidélité</h3>
                        <div class="d-inline-block">
                            <h2 class="text-white">{{ $client->loyalty_points ?? 0 }}</h2>
                            <p class="text-white mb-0">Points accumulés</p>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-star"></i></span>
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
                            <h2 class="text-white">{{ $client->total_appointments ?? 0 }}</h2>
                            <p class="text-white mb-0">Rendez-vous effectués</p>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-check-circle"></i></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card gradient-4">
                    <div class="card-body">
                        <h3 class="card-title text-white">Statut</h3>
                        <div class="d-inline-block">
                            <h2 class="text-white">{{ $client->active ? 'Actif' : 'Inactif' }}</h2>
                            <p class="text-white mb-0">Compte client</p>
                        </div>
                        <span class="float-right display-5 opacity-5"><i class="fa fa-user"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Actions Rapides</h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('client.appointments.create') }}" class="btn btn-primary btn-block mb-2">
                            <i class="fa fa-calendar-plus-o mr-2"></i>Prendre un rendez-vous
                        </a>
                        <a href="{{ route('client.appointments.index') }}" class="btn btn-info btn-block mb-2">
                            <i class="fa fa-calendar mr-2"></i>Mes rendez-vous
                        </a>
                        <a href="{{ route('client.payments.index') }}" class="btn btn-success btn-block mb-2">
                            <i class="fa fa-credit-card mr-2"></i>Mes paiements
                        </a>
                        <a href="{{ route('client.profile') }}" class="btn btn-warning btn-block mb-2">
                            <i class="fa fa-user mr-2"></i>Mon profil
                        </a>
                        <a href="{{ route('client.chatbot.index') }}" class="btn btn-secondary btn-block">
                            <i class="fa fa-comments mr-2"></i>Chatbot Assistant
                        </a>
                    </div>
                </div>

                @if(($client->loyalty_points ?? 0) >= 100)
                <div class="card mt-3 border-success">
                    <div class="card-body text-center">
                        <i class="fa fa-gift fa-2x text-success mb-2"></i>
                        <h5 class="text-success">Réduction disponible !</h5>
                        <p class="text-muted mb-0">Vous avez assez de points pour une réduction de 10%</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Prochains Rendez-vous</h4>
                    </div>
                    <div class="card-body">
                        @if($upcomingAppointments->isEmpty())
                            <div class="text-center py-4">
                                <i class="fa fa-calendar-o fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun rendez-vous à venir</p>
                                <a href="{{ route('client.appointments.create') }}" class="btn btn-primary">
                                    Prendre un rendez-vous
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Heure</th>
                                            <th>Service</th>
                                            <th>Employé</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($upcomingAppointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->date->format('d/m/Y') }}</td>
                                            <td>{{ $appointment->time }}</td>
                                            <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                                            <td>{{ $appointment->employee->name ?? 'Non assigné' }}</td>
                                            <td>
                                                @if($appointment->status == 'pending')
                                                    <span class="badge badge-warning">En attente</span>
                                                @else
                                                    <span class="badge badge-success">Confirmé</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ route('client.appointments.index') }}" class="btn btn-outline-primary btn-sm">
                                Voir tous les rendez-vous
                            </a>
                        @endif
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header bg-info">
                        <h4 class="card-title text-white mb-0">
                            <i class="fa fa-lightbulb-o mr-2"></i>Besoin d'aide ?
                        </h4>
                    </div>
                    <div class="card-body">
                        <p>Notre assistant virtuel est disponible 24h/24 pour répondre à vos questions !</p>
                        <a href="{{ route('client.chatbot.index') }}" class="btn btn-info">
                            <i class="fa fa-comments mr-2"></i>Discuter avec l'assistant
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
