{{--
    Vue : Détails d'un rendez-vous
    Route : admin.appointments.show
    Contrôleur : AppointmentController@show
    Description : Affiche les informations complètes d'un rendez-vous (client, service,
                  date, statut, date de création) avec liens de modification.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- Section : En-tête de page --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-calendar-check-o"></i></div>
                <div>
                    <h2 class="beauty-page-title">Détails du rendez-vous </h2>
                    <p class="beauty-page-subtitle">Informations complètes</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                   class="btn btn-warning">
                    Modifier
                </a>
                <a href="{{ route('admin.appointments.index') }}"
                   class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        {{-- Section : Messages de succès et d'erreur --}}
        @include('partials.success')
        @include('partials.error')

        {{-- Section : Informations détaillées du rendez-vous --}}
        <div class="beauty-card">
            <div class="beauty-card-body">

                <table class="table table-bordered">
                    <tr>
                        <th>Client</th>
                        <td>{{ $appointment->client->name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th>Service</th>
                        <td>{{ $appointment->service->name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th>Date</th>
                        <td>{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                    </tr>

                    <tr>
                        <th>Statut</th>
                        <td>{!! $appointment->status_badge !!}</td>
                    </tr>

                    <tr>
                        <th>Créé le</th>
                        <td>{{ $appointment->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

            </div>
        </div>

    </div>
</div>
@endsection
