{{--
    Vue : Heures de travail de l'employé
    Description : Configuration des heures de travail quotidiennes de l'employé (heure de début et de fin par jour).
--}}
@extends('layouts.employee-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-clock-o"></i></div>
                <div>
                    <h2 class="beauty-page-title">Mes Horaires de Travail ⏰</h2>
                    <p class="beauty-page-subtitle">Consultez vos horaires hebdomadaires</p>
                </div>
            </div>
            <a href="{{ route('employee.schedules.index') }}" class="btn btn-secondary"><i class="fa fa-arrow-left mr-2"></i>Retour</a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-clock-o mr-2"></i>Horaires hebdomadaires
                        </h4>
                    </div>
                    <div class="card-body">
                        @php
                            $days = [
                                1 => 'Lundi',
                                2 => 'Mardi',
                                3 => 'Mercredi',
                                4 => 'Jeudi',
                                5 => 'Vendredi',
                                6 => 'Samedi',
                                0 => 'Dimanche',
                            ];
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-primary">
                                    <tr>
                                        <th style="width: 20%;">Jour</th>
                                        <th style="width: 25%;">Début</th>
                                        <th style="width: 25%;">Fin</th>
                                        <th style="width: 30%;">Pause</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($days as $dayNum => $dayName)
                                        @php
                                            $schedule = $schedules->get($dayNum);
                                        @endphp
                                        <tr class="{{ !$schedule || !$schedule->is_working ? 'table-secondary' : '' }}">
                                            <td>
                                                <strong>{{ $dayName }}</strong>
                                            </td>
                                            @if($schedule && $schedule->is_working)
                                                <td>
                                                    <i class="fa fa-sign-in text-success mr-1"></i>
                                                    {{ substr($schedule->start_time, 0, 5) }}
                                                </td>
                                                <td>
                                                    <i class="fa fa-sign-out text-danger mr-1"></i>
                                                    {{ substr($schedule->end_time, 0, 5) }}
                                                </td>
                                                <td>
                                                    @if($schedule->break_start && $schedule->break_end)
                                                        <i class="fa fa-coffee text-warning mr-1"></i>
                                                        {{ substr($schedule->break_start, 0, 5) }} - {{ substr($schedule->break_end, 0, 5) }}
                                                    @else
                                                        <span class="text-muted">Pas de pause</span>
                                                    @endif
                                                </td>
                                            @else
                                                <td colspan="3" class="text-center">
                                                    <span class="badge badge-secondary">
                                                        <i class="fa fa-bed mr-1"></i> Jour de repos
                                                    </span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fa fa-info-circle mr-2"></i>
                            Vos horaires sont gérés par l'administration. Pour toute modification,
                            veuillez contacter votre responsable.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-info-circle mr-2"></i>Résumé
                        </h4>
                    </div>
                    <div class="card-body">
                        @php
                            $workingDays = $schedules->filter(fn($s) => $s->is_working)->count();
                            $totalHours = 0;
                            foreach($schedules as $schedule) {
                                if ($schedule->is_working && $schedule->start_time && $schedule->end_time) {
                                    $start = \Carbon\Carbon::parse($schedule->start_time);
                                    $end = \Carbon\Carbon::parse($schedule->end_time);
                                    $hours = $end->diffInMinutes($start) / 60;

                                    if ($schedule->break_start && $schedule->break_end) {
                                        $breakStart = \Carbon\Carbon::parse($schedule->break_start);
                                        $breakEnd = \Carbon\Carbon::parse($schedule->break_end);
                                        $hours -= $breakEnd->diffInMinutes($breakStart) / 60;
                                    }

                                    $totalHours += $hours;
                                }
                            }
                        @endphp

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-calendar-check-o text-success mr-2"></i>Jours travaillés</span>
                                <span class="badge badge-primary badge-pill">{{ $workingDays }} jours/sem</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-clock-o text-info mr-2"></i>Total heures</span>
                                <span class="badge badge-info badge-pill">{{ number_format($totalHours, 1) }} h/sem</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="fa fa-link mr-2"></i>Liens rapides
                        </h4>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('employee.schedules.index') }}" class="btn btn-outline-primary btn-block mb-2">
                            <i class="fa fa-calendar mr-2"></i>Voir le calendrier
                        </a>
                        <a href="{{ route('employee.schedules.days-off') }}" class="btn btn-outline-warning btn-block mb-2">
                            <i class="fa fa-calendar-times-o mr-2"></i>Mes congés
                        </a>
                        <a href="{{ route('employee.leaves.create') }}" class="btn btn-outline-success btn-block">
                            <i class="fa fa-plus mr-2"></i>Demander un congé
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
