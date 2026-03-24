{{--
    Vue : Création d'un rendez-vous
    Route : admin.appointments.create
    Contrôleur : AppointmentController@create
    Description : Formulaire de création d'un rendez-vous avec sélection du client,
                  du service, de la date/heure et assignation automatique des employés
                  disponibles via requête AJAX.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-calendar-plus-o"></i></div>
                <div>
                    <h2 class="beauty-page-title">Nouveau rendez-vous </h2>
                    <p class="beauty-page-subtitle">Planifier un rendez-vous</p>
                </div>
            </div>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

        {{-- Section : Messages de succès et d'erreur --}}
        @include('partials.success')
        @include('partials.error')

        {{-- Section : Formulaire de création --}}
        <div class="beauty-card">
            <div class="beauty-card-body">
                <form action="{{ route('admin.appointments.store') }}" method="POST" id="appointmentForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Client <span class="text-danger">*</span></label>
                            <select name="client_id" id="client_id" class="form-control" required>
                                <option value="">-- Choisir un client --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->phone ?? $client->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Service <span class="text-danger">*</span></label>
                            <select name="service_id" id="service_id" class="form-control" required>
                                <option value="">-- Choisir un service --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}"
                                            data-duration="{{ $service->duration }}"
                                            data-category="{{ $service->category }}"
                                            data-gender="{{ $service->gender }}"
                                            {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }}
                                        ({{ ucfirst($service->category ?? 'Autre') }} - {{ $service->duration }} min - {{ number_format($service->getCurrentPrice(), 0, ',', ' ') }} FCFA)
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" id="date" class="form-control"
                                   value="{{ old('date', date('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}" required>
                            @error('date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Heure <span class="text-danger">*</span></label>
                            <input type="time" name="time" id="time" class="form-control"
                                   value="{{ old('time', '09:00') }}" required>
                            @error('time') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Employé <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-control" required>
                            <option value="">-- Sélectionnez d'abord un service et un créneau --</option>
                        </select>
                        <small class="text-muted" id="employee_help">
                            <i class="fa fa-info-circle"></i> Les employés disponibles seront affichés selon le service et le créneau choisis
                        </small>
                        <div id="employee_loading" style="display:none;">
                            <i class="fa fa-spinner fa-spin"></i> Recherche des employés disponibles...
                        </div>
                        @error('employee_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Statut</label>
                            <select name="status" class="form-control">
                                @foreach(App\Enums\AppointmentStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ old('status', 'pending') == $status->value ? 'selected' : '' }}>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                        @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-save mr-1"></i> Enregistrer
                        </button>
                        <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
/**
 * Script de chargement dynamique des employés disponibles.
 * Envoie une requête AJAX au serveur à chaque changement de service, date ou heure
 * pour récupérer la liste des employés qualifiés et disponibles sur le créneau choisi.
 */
document.addEventListener('DOMContentLoaded', function() {
    // Références aux éléments du formulaire
    const serviceSelect = document.getElementById('service_id');
    const dateInput = document.getElementById('date');
    const timeInput = document.getElementById('time');
    const employeeSelect = document.getElementById('employee_id');
    const employeeLoading = document.getElementById('employee_loading');
    const employeeHelp = document.getElementById('employee_help');

    /**
     * Charge les employés disponibles via une requête fetch.
     * Vérifie d'abord que le service, la date et l'heure sont renseignés.
     * Met à jour la liste déroulante des employés avec les résultats.
     */
    function loadAvailableEmployees() {
        const serviceId = serviceSelect.value;
        const date = dateInput.value;
        const time = timeInput.value;

        // Vérification que tous les champs requis sont remplis
        if (!serviceId || !date || !time) {
            employeeSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un service et un créneau --</option>';
            return;
        }

        // Affichage du indicateur de chargement
        employeeLoading.style.display = 'block';
        employeeHelp.style.display = 'none';
        employeeSelect.disabled = true;

        // Appel AJAX pour récupérer les employés disponibles
        fetch(`{{ url('/admin/appointments/available-employees') }}?service_id=${serviceId}&date=${date}&time=${time}`)
            .then(response => response.json())
            .then(data => {
                employeeSelect.innerHTML = '';

                // Remplissage de la liste si des employés sont trouvés
                if (data.employees && data.employees.length > 0) {
                    employeeSelect.innerHTML = '<option value="">-- Choisir un employé --</option>';
                    data.employees.forEach(emp => {
                        const option = document.createElement('option');
                        option.value = emp.id;
                        option.textContent = emp.name + (emp.available ? ' Disponible' : ' (Occupé)');
                        option.disabled = !emp.available;
                        // Sélection automatique du premier employé disponible
                        if (emp.available && employeeSelect.options.length === 1) {
                            option.selected = true;
                        }
                        employeeSelect.appendChild(option);
                    });
                    employeeHelp.innerHTML = '<i class="fa fa-check-circle text-success"></i> ' + data.employees.filter(e => e.available).length + ' employé(s) disponible(s)';
                } else {
                    // Aucun employé disponible pour ce créneau
                    employeeSelect.innerHTML = '<option value="">Aucun employé disponible pour ce créneau</option>';
                    employeeHelp.innerHTML = '<i class="fa fa-warning text-warning"></i> Aucun employé qualifié disponible';
                }

                employeeHelp.style.display = 'block';
                employeeLoading.style.display = 'none';
                employeeSelect.disabled = false;
            })
            .catch(error => {
                // Gestion des erreurs de la requête
                console.error('Erreur:', error);
                employeeSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
                employeeLoading.style.display = 'none';
                employeeSelect.disabled = false;
            });
    }

    // Écouteurs d'événements sur les champs service, date et heure
    serviceSelect.addEventListener('change', loadAvailableEmployees);
    dateInput.addEventListener('change', loadAvailableEmployees);
    timeInput.addEventListener('change', loadAvailableEmployees);

    // Chargement initial si les valeurs sont déjà renseignées (ex: retour de validation)
    if (serviceSelect.value && dateInput.value && timeInput.value) {
        loadAvailableEmployees();
    }
});
</script>
@endpush
