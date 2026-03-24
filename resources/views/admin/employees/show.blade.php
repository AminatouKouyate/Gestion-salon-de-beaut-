{{--
    Vue : Fiche détaillée d'un employé
    Route : admin.employees.show
    Contrôleur : EmployeeController@show
    Description : Affiche le profil complet d'un employé avec informations personnelles,
                  horaires de travail, services assignés, statistiques de performance,
                  rendez-vous du jour et prochains rendez-vous.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
        {{-- Section : En-tête de page --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-user"></i></div>
                <div>
                    <h2 class="beauty-page-title">{{ $employee->name }}</h2>
                    <p class="beauty-page-subtitle">Fiche détaillée de l'employé</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-primary">Modifier</a>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour à la liste
                </a>
            </div>
        </div>

        @include('partials.success')
        @include('partials.error')

        <div class="row">
            <div class="col-lg-4">
                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h5 class="mb-0">Informations personnelles</h5>
                    </div>
                    <div class="beauty-card-body">
                        <div class="text-center mb-3">
                            <div class="avatar avatar-xl bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </div>
                        </div>

                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>{{ $employee->email }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Téléphone</td>
                                <td>{{ $employee->phone ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Rôle</td>
                                <td>
                                    <span class="badge badge-{{ $employee->role === 'manager' ? 'warning' : 'info' }}">
                                        {{ $employee->role === 'manager' ? 'Manager' : 'Employé' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Statut</td>
                                <td>
                                    <span class="badge badge-{{ $employee->is_active ? 'success' : 'danger' }}">
                                        {{ $employee->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                            </tr>
                            @if($employee->specialties)
                            <tr>
                                <td class="text-muted">Spécialités</td>
                                <td>{{ $employee->specialties }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h5 class="mb-0">Horaires de travail</h5>
                    </div>
                    <div class="beauty-card-body">
                        @if($employee->work_start_time && $employee->work_end_time)
                            <p class="mb-2">
                                <strong>Heures:</strong>
                                {{ \Carbon\Carbon::parse($employee->work_start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($employee->work_end_time)->format('H:i') }}
                            </p>
                        @endif

                        @if($employee->work_days && count($employee->work_days) > 0)
                            <p class="mb-0"><strong>Jours:</strong></p>
                            @php
                                $dayNames = [
                                    'monday' => 'Lundi',
                                    'tuesday' => 'Mardi',
                                    'wednesday' => 'Mercredi',
                                    'thursday' => 'Jeudi',
                                    'friday' => 'Vendredi',
                                    'saturday' => 'Samedi',
                                    'sunday' => 'Dimanche',
                                ];
                            @endphp
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($employee->work_days as $day)
                                    <span class="badge badge-secondary">{{ $dayNames[$day] ?? $day }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">Horaires non définis</p>
                        @endif
                    </div>
                </div>

                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h5 class="mb-0">Services assignés</h5>
                    </div>
                    <div class="beauty-card-body">
                        @if($employee->services->count() > 0)
                            <ul class="list-unstyled mb-0">
                                @foreach($employee->services as $service)
                                    <li class="mb-2">
                                        <i class="fa fa-check-circle text-success mr-2"></i>
                                        {{ $service->name }}
                                        <span class="text-muted">({{ number_format($service->price, 0, ',', ' ') }} FCFA)</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">Aucun service assigné</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="beauty-card bg-primary text-white">
                            <div class="beauty-card-body text-center">
                                <h3 class="mb-0">{{ $performance['total_appointments'] }}</h3>
                                <small>Total RDV</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="beauty-card bg-info text-white">
                            <div class="beauty-card-body text-center">
                                <h3 class="mb-0">{{ $performance['monthly_appointments'] }}</h3>
                                <small>Ce mois</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="beauty-card bg-success text-white">
                            <div class="beauty-card-body text-center">
                                <h3 class="mb-0">{{ $performance['completed_appointments'] }}</h3>
                                <small>Complétés</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="beauty-card bg-warning text-dark">
                            <div class="beauty-card-body text-center">
                                <h3 class="mb-0">{{ number_format($performance['monthly_revenue'], 0, ',', ' ') }}</h3>
                                <small>FCFA ce mois</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="beauty-card mb-4">
                    <div class="beauty-card-header">
                        <h5 class="mb-0">Rendez-vous d'aujourd'hui</h5>
                    </div>
                    <div class="beauty-card-body">
                        @if($performance['today_appointments']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Heure</th>
                                            <th>Client</th>
                                            <th>Service</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($performance['today_appointments'] as $appointment)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}</td>
                                                <td>{{ $appointment->client->name ?? '—' }}</td>
                                                <td>{{ $appointment->service->name ?? '—' }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $appointment->status->badgeClass() }}">
                                                        {{ $appointment->status->label() }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">Aucun rendez-vous aujourd'hui</p>
                        @endif
                    </div>
                </div>

                <div class="beauty-card">
                    <div class="beauty-card-header">
                        <h5 class="mb-0">Prochains rendez-vous</h5>
                    </div>
                    <div class="beauty-card-body">
                        @if($performance['upcoming_appointments']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Heure</th>
                                            <th>Client</th>
                                            <th>Service</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($performance['upcoming_appointments'] as $appointment)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}</td>
                                                <td>{{ $appointment->client->name ?? '—' }}</td>
                                                <td>{{ $appointment->service->name ?? '—' }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $appointment->status->badgeClass() }}">
                                                        {{ $appointment->status->label() }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">Aucun rendez-vous à venir</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
