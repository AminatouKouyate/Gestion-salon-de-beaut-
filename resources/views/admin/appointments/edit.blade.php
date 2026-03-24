{{--
    Vue : Modification d'un rendez-vous
    Route : admin.appointments.edit
    Contrôleur : AppointmentController@edit
    Description : Formulaire de modification d'un rendez-vous existant avec possibilité
                  de réaffecter l'employé via une modale de réaffectation dynamique.
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
                    <h2 class="beauty-page-title">Modifier le rendez-vous </h2>
                    <p class="beauty-page-subtitle">Mettre à jour les informations</p>
                </div>
            </div>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        {{-- Section : Messages de succès et d'erreur --}}
        @include('partials.success')
        @include('partials.error')

        {{-- Section : Formulaire de modification --}}
        <div class="beauty-card">
            <div class="beauty-card-header d-flex justify-content-between align-items-center">
                <span>Informations du rendez-vous</span>
                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#reassignModal">
                    <i class="fa fa-exchange-alt"></i> Réaffecter l'employé
                </button>
            </div>
            <div class="beauty-card-body">
                <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Champ : Sélection du client --}}
                    <div class="mb-3">
                        <label class="form-label">Client</label>
                        <select name="client_id" class="form-select" required>
                            <option value="">-- Choisir un client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id', $appointment->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Champ : Sélection du service --}}
                    <div class="mb-3">
                        <label class="form-label">Service</label>
                        <select name="service_id" class="form-select" required>
                            <option value="">-- Choisir un service --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}"
                                    {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Champ : Sélection de l'employé --}}
                    <div class="mb-3">
                        <label class="form-label">Employé</label>
                        <select name="employee_id" class="form-select" required>
                            <option value="">-- Choisir un employé --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ old('employee_id', $appointment->employee_id) == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Champ : Date du rendez-vous --}}
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date"
                               name="date"
                               class="form-control"
                               value="{{ old('date', $appointment->scheduled_at->format('Y-m-d')) }}"
                               required>
                        @error('date') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Champ : Heure du rendez-vous --}}
                    <div class="mb-3">
                        <label class="form-label">Heure</label>
                        <input type="time"
                               name="time"
                               class="form-control"
                               value="{{ old('time', $appointment->scheduled_at->format('H:i')) }}"
                               required>
                        @error('time') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Champ : Statut du rendez-vous --}}
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select">
                            @foreach(App\Enums\AppointmentStatus::cases() as $status)
                                <option value="{{ $status->value }}" {{ old('status', $appointment->status->value) == $status->value ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                         @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Champ : Notes complémentaires --}}
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                        @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>


                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-light">
                        Annuler
                    </a>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- Section : Modale de réaffectation de l'employé --}}
<div class="modal fade" id="reassignModal" tabindex="-1" role="dialog" aria-labelledby="reassignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reassignModalLabel">Réaffecter l'employé</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="reassignForm" action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="client_id" value="{{ $appointment->client_id }}">
                <input type="hidden" name="service_id" value="{{ $appointment->service_id }}">
                <input type="hidden" name="date" value="{{ $appointment->scheduled_at->format('Y-m-d') }}">
                <input type="hidden" name="time" value="{{ $appointment->scheduled_at->format('H:i') }}">
                <input type="hidden" name="status" value="{{ $appointment->status->value }}">
                <input type="hidden" name="notes" value="{{ $appointment->notes }}">

                <div class="modal-body">
                    <div class="mb-3">
                        <p><strong>Service:</strong> {{ $appointment->service->name ?? 'N/A' }}</p>
                        <p><strong>Date:</strong> {{ $appointment->scheduled_at->format('d/m/Y') }}</p>
                        <p><strong>Heure:</strong> {{ $appointment->scheduled_at->format('H:i') }}</p>
                        <p><strong>Employé actuel:</strong> <span class="badge badge-info">{{ $appointment->employee->name ?? 'Non assigné' }}</span></p>
                    </div>

                    <hr>

                    <div id="employeesLoadingSpinner" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Chargement...</span>
                        </div>
                        <p class="mt-2">Vérification des disponibilités...</p>
                    </div>

                    <div id="employeesListContainer" style="display: none;">
                        <label class="form-label"><strong>Sélectionner un nouvel employé:</strong></label>
                        <div id="employeesList" class="list-group">
                            <!-- Les employés seront chargés ici dynamiquement -->
                        </div>
                    </div>

                    <div id="employeesError" class="alert alert-danger" style="display: none;">
                        Une erreur est survenue lors du chargement des employés.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" id="reassignSubmitBtn" class="btn btn-warning" disabled>
                        <i class="fa fa-exchange-alt"></i> Confirmer la réaffectation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Section : Scripts JavaScript --}}
@section('scripts')
<script>
/**
 * Script de gestion de la modale de réaffectation d'employé.
 * Charge dynamiquement la liste des employés disponibles via AJAX
 * et permet de sélectionner un nouvel employé pour le rendez-vous.
 */
$(document).ready(function() {
    // Identifiant de l'employé actuellement assigné
    var currentEmployeeId = {{ $appointment->employee_id ?? 'null' }};
    var selectedEmployeeId = null;

    // Chargement des employés disponibles à l'ouverture de la modale
    $('#reassignModal').on('show.bs.modal', function() {
        loadAvailableEmployees();
    });

    /**
     * Récupère les employés disponibles pour le créneau du rendez-vous.
     * Affiche un indicateur de chargement pendant la requête.
     */
    function loadAvailableEmployees() {
        var serviceId = {{ $appointment->service_id }};
        var date = '{{ $appointment->scheduled_at->format("Y-m-d") }}';
        var time = '{{ $appointment->scheduled_at->format("H:i") }}';

        $('#employeesLoadingSpinner').show();
        $('#employeesListContainer').hide();
        $('#employeesError').hide();
        $('#reassignSubmitBtn').prop('disabled', true);
        selectedEmployeeId = null;

        $.ajax({
            url: '/admin/appointments/available-employees',
            method: 'GET',
            data: {
                service_id: serviceId,
                date: date,
                time: time
            },
            success: function(response) {
                $('#employeesLoadingSpinner').hide();
                $('#employeesListContainer').show();
                renderEmployeesList(response.employees || response);
            },
            error: function(xhr) {
                $('#employeesLoadingSpinner').hide();
                $('#employeesError').show();
                console.error('Erreur lors du chargement des employés:', xhr);
            }
        });
    }

    /**
     * Affiche la liste des employés dans la modale.
     * Chaque employé est affiché avec son statut (actuel, disponible, indisponible).
     * Permet de sélectionner un employé disponible pour la réaffectation.
     */
    function renderEmployeesList(employees) {
        var container = $('#employeesList');
        container.empty();

        if (!employees || employees.length === 0) {
            container.html('<div class="alert alert-info">Aucun employé disponible pour ce service.</div>');
            return;
        }

        employees.forEach(function(employee) {
            var isAvailable = employee.available || employee.is_available;
            var isCurrent = employee.id == currentEmployeeId;
            var reason = employee.reason || employee.unavailable_reason || '';

            var statusBadge = '';
            var itemClass = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';

            if (isCurrent) {
                statusBadge = '<span class="badge badge-info">Actuel</span>';
                itemClass += ' list-group-item-light';
            } else if (isAvailable) {
                statusBadge = '<span class="badge badge-success">Disponible</span>';
            } else {
                statusBadge = '<span class="badge badge-secondary">Indisponible</span>';
                itemClass += ' list-group-item-secondary disabled';
            }

            var reasonText = '';
            if (!isAvailable && reason && !isCurrent) {
                reasonText = '<br><small class="text-muted"><i class="fa fa-info-circle"></i> ' + reason + '</small>';
            }

            var radioInput = '';
            if (!isCurrent && isAvailable) {
                radioInput = '<input type="radio" name="employee_id" value="' + employee.id + '" class="employee-radio" style="display:none;">';
            }

            var item = $('<label class="' + itemClass + '" style="cursor:' + (isAvailable && !isCurrent ? 'pointer' : 'not-allowed') + ';">' +
                radioInput +
                '<div>' +
                    '<strong>' + employee.name + '</strong>' + reasonText +
                '</div>' +
                statusBadge +
            '</label>');

            if (isAvailable && !isCurrent) {
                item.on('click', function() {
                    $('.list-group-item').removeClass('active');
                    $(this).addClass('active');
                    $(this).find('input[type="radio"]').prop('checked', true);
                    selectedEmployeeId = employee.id;
                    $('#reassignSubmitBtn').prop('disabled', false);
                });
            }

            container.append(item);
        });
    }

    // Validation avant soumission : vérifier qu'un employé est sélectionné
    $('#reassignForm').on('submit', function(e) {
        if (!selectedEmployeeId) {
            e.preventDefault();
            alert('Veuillez sélectionner un employé.');
            return false;
        }
    });
});
</script>
@endsection
