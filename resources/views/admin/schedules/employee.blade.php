@extends('layouts.admin-master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Planning de {{ $employee->name }}</h4>
                    <p class="text-muted">Horaires, congés et blocages</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour au planning
                </a>
            </div>
        </div>

        @include('partials.success')
@include('partials.error')

        <!-- Horaires de travail hebdomadaires -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fa fa-clock-o mr-2"></i>Horaires de travail hebdomadaires</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.schedules.updateWorkingHours', $employee->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            @php
                                $days = [
                                    'monday' => 'Lundi',
                                    'tuesday' => 'Mardi',
                                    'wednesday' => 'Mercredi',
                                    'thursday' => 'Jeudi',
                                    'friday' => 'Vendredi',
                                    'saturday' => 'Samedi',
                                    'sunday' => 'Dimanche'
                                ];
                            @endphp

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 150px;">Jour</th>
                                            <th style="width: 100px;">Travaille</th>
                                            <th>Heure début</th>
                                            <th>Heure fin</th>
                                            <th>Pause début</th>
                                            <th>Pause fin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($days as $dayKey => $dayName)
                                            @php
                                                $schedule = $workingHours[$dayKey] ?? null;
                                            @endphp
                                            <tr>
                                                <td class="align-middle font-weight-bold">{{ $dayName }}</td>
                                                <td class="align-middle text-center">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" 
                                                               class="custom-control-input day-toggle" 
                                                               id="works_{{ $dayKey }}" 
                                                               name="days[{{ $dayKey }}][works]" 
                                                               value="1"
                                                               data-day="{{ $dayKey }}"
                                                               {{ old("days.$dayKey.works", $schedule->works ?? false) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="works_{{ $dayKey }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="time" 
                                                           class="form-control time-input-{{ $dayKey }}" 
                                                           name="days[{{ $dayKey }}][start_time]" 
                                                           value="{{ old("days.$dayKey.start_time", $schedule->start_time ?? '09:00') }}"
                                                           {{ old("days.$dayKey.works", $schedule->works ?? false) ? '' : 'disabled' }}>
                                                </td>
                                                <td>
                                                    <input type="time" 
                                                           class="form-control time-input-{{ $dayKey }}" 
                                                           name="days[{{ $dayKey }}][end_time]" 
                                                           value="{{ old("days.$dayKey.end_time", $schedule->end_time ?? '18:00') }}"
                                                           {{ old("days.$dayKey.works", $schedule->works ?? false) ? '' : 'disabled' }}>
                                                </td>
                                                <td>
                                                    <input type="time" 
                                                           class="form-control time-input-{{ $dayKey }}" 
                                                           name="days[{{ $dayKey }}][break_start]" 
                                                           value="{{ old("days.$dayKey.break_start", $schedule->break_start ?? '12:00') }}"
                                                           {{ old("days.$dayKey.works", $schedule->works ?? false) ? '' : 'disabled' }}>
                                                </td>
                                                <td>
                                                    <input type="time" 
                                                           class="form-control time-input-{{ $dayKey }}" 
                                                           name="days[{{ $dayKey }}][break_end]" 
                                                           value="{{ old("days.$dayKey.break_end", $schedule->break_end ?? '13:00') }}"
                                                           {{ old("days.$dayKey.works", $schedule->works ?? false) ? '' : 'disabled' }}>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save mr-2"></i>Sauvegarder les horaires
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Congés approuvés -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fa fa-calendar-check-o mr-2"></i>Congés approuvés</h4>
                    </div>
                    <div class="card-body">
                        @if(isset($leaves) && $leaves->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date début</th>
                                            <th>Date fin</th>
                                            <th>Type</th>
                                            <th>Raison</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaves as $leave)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('d/m/Y') }}</td>
                                                <td>{{ $leave->type ?? 'Congé' }}</td>
                                                <td>{{ $leave->reason ?? '-' }}</td>
                                                <td>
                                                    <span class="badge badge-success">Approuvé</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa fa-calendar-o fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun congé approuvé à venir</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Créneaux bloqués -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><i class="fa fa-ban mr-2"></i>Créneaux bloqués</h4>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addBlockModal">
                            <i class="fa fa-plus mr-1"></i>Ajouter un blocage
                        </button>
                    </div>
                    <div class="card-body">
                        @if(isset($blocks) && $blocks->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date/Heure début</th>
                                            <th>Date/Heure fin</th>
                                            <th>Raison</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($blocks as $block)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($block->start_datetime)->format('d/m/Y H:i') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($block->end_datetime)->format('d/m/Y H:i') }}</td>
                                                <td>{{ $block->reason ?? '-' }}</td>
                                                <td class="text-right">
                                                    <form action="{{ route('admin.schedules.destroyBlock', $block->id) }}" 
                                                          method="POST" 
                                                          class="d-inline confirm-delete"
                                                          data-confirm-message="Êtes-vous sûr de vouloir supprimer ce blocage ?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fa fa-check-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun créneau bloqué</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal ajouter blocage pour cet employé -->
<div class="modal fade" id="addBlockModal" tabindex="-1" role="dialog" aria-labelledby="addBlockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.schedules.storeBlock') }}" method="POST">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="addBlockModalLabel">
                        <i class="fa fa-ban mr-2"></i>Bloquer un créneau
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="block_start_datetime">Date/Heure début <span class="text-danger">*</span></label>
                        <input type="datetime-local" 
                               class="form-control" 
                               id="block_start_datetime" 
                               name="start_datetime" 
                               required>
                    </div>
                    <div class="form-group">
                        <label for="block_end_datetime">Date/Heure fin <span class="text-danger">*</span></label>
                        <input type="datetime-local" 
                               class="form-control" 
                               id="block_end_datetime" 
                               name="end_datetime" 
                               required>
                    </div>
                    <div class="form-group">
                        <label for="block_reason">Raison (optionnel)</label>
                        <textarea class="form-control" 
                                  id="block_reason" 
                                  name="reason" 
                                  rows="3" 
                                  placeholder="Ex: Formation, réunion, indisponibilité..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save mr-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.day-toggle').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var day = this.dataset.day;
            var inputs = document.querySelectorAll('.time-input-' + day);
            inputs.forEach(function(input) {
                input.disabled = !checkbox.checked;
            });
        });
    });
});
</script>
@endpush
