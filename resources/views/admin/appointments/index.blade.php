{{--
    Vue : Liste des rendez-vous
    Route : admin.appointments.index
    Contrôleur : AppointmentController@index
    Description : Affiche la liste paginée de tous les rendez-vous du salon
                  avec les informations client, service, date, statut et actions.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- Section : En-tête de page --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-calendar"></i></div>
                <div>
                    <h2 class="beauty-page-title">Rendez-vous </h2>
                    <p class="beauty-page-subtitle">Gérez tous les rendez-vous du salon</p>
                </div>
            </div>

        </div>

        {{-- Section : Messages de succès et d'erreur --}}
        @include('partials.success')
        @include('partials.error')

        {{-- Section : Tableau des données --}}
        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-list mr-2" style="color:var(--primary);"></i>Tous les rendez-vous</h4>
            </div>
            <div class="beauty-card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($appointments as $appointment)
                            <tr>
                                <td><strong>{{ $appointment->client->name ?? '—' }}</strong></td>
                                <td>{{ $appointment->service->name ?? '—' }}</td>
                                <td><i class="fa fa-clock-o mr-1" style="color:var(--primary);"></i>{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                                <td>{!! $appointment->status_badge !!}</td>
                                {{-- Section : Actions (voir, modifier, supprimer) --}}
                                <td class="text-right">@include('partials.appointment-actions', ['appointment' => $appointment])</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="beauty-empty">
                                        <div class="beauty-empty-icon"><i class="fa fa-calendar-o"></i></div>
                                        <p>Aucun rendez-vous trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Section : Pagination --}}
            @if($appointments->hasPages())
            <div class="beauty-card-footer">{{ $appointments->links() }}</div>
            @endif
        </div>

    </div>
</div>
@endsection
