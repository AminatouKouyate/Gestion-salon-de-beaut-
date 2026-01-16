@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Prendre un rendez-vous</h4>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <a href="{{ route('client.appointments.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Détails du rendez-vous</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('client.appointments.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="service_id">Service <span class="text-danger">*</span></label>
                                <select name="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}"
                                                data-price="{{ $service->price }}"
                                                data-duration="{{ $service->duration }}"
                                                {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} - {{ $service->price }} FCFA ({{ $service->duration }} min)
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group" id="employee-group" style="display: none;">
                                <label for="employee_id">Employé (optionnel)</label>
                                <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror">
                                    <option value="">Pas de préférence</option>
                                </select>
                                <small class="text-muted" id="employee-loading" style="display: none;">Chargement des employés...</small>
                                <small class="text-muted" id="no-employees" style="display: none;">Aucun employé disponible pour ce service</small>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Date <span class="text-danger">*</span></label>
                                        <input type="date" name="date" id="date"
                                               class="form-control @error('date') is-invalid @enderror"
                                               value="{{ old('date') }}"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                        @error('date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="time">Heure <span class="text-danger">*</span></label>
                                        <select name="time" id="time" class="form-control @error('time') is-invalid @enderror" required>
                                            <option value="">Sélectionner une heure</option>
                                            @for($hour = 9; $hour < 18; $hour++)
                                                <option value="{{ sprintf('%02d:00', $hour) }}" {{ old('time') == sprintf('%02d:00', $hour) ? 'selected' : '' }}>
                                                    {{ sprintf('%02d:00', $hour) }}
                                                </option>
                                                <option value="{{ sprintf('%02d:30', $hour) }}" {{ old('time') == sprintf('%02d:30', $hour) ? 'selected' : '' }}>
                                                    {{ sprintf('%02d:30', $hour) }}
                                                </option>
                                            @endfor
                                        </select>
                                        @error('time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes (optionnel)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3"
                                          placeholder="Demandes spéciales, allergies, etc.">{{ old('notes') }}</textarea>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-calendar-check-o mr-2"></i>Confirmer le rendez-vous
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-info">
                        <h4 class="card-title text-white mb-0">Récapitulatif</h4>
                    </div>
                    <div class="card-body">
                        <div id="summary">
                            <p class="text-muted">Sélectionnez un service pour voir le récapitulatif</p>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h5><i class="fa fa-info-circle mr-2"></i>Informations</h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fa fa-clock-o text-primary mr-2"></i>Ouvert 9h - 18h</li>
                            <li class="mb-2"><i class="fa fa-calendar text-primary mr-2"></i>Lun - Sam</li>
                            <li><i class="fa fa-phone text-primary mr-2"></i>01 23 45 67 89</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const employeeSelect = document.getElementById('employee_id');
    const employeeGroup = document.getElementById('employee-group');
    const employeeLoading = document.getElementById('employee-loading');
    const noEmployees = document.getElementById('no-employees');
    const dateInput = document.getElementById('date');
    const timeSelect = document.getElementById('time');
    const summary = document.getElementById('summary');

    function loadEmployeesForService(serviceId) {
        employeeLoading.style.display = 'block';
        noEmployees.style.display = 'none';
        employeeSelect.innerHTML = '<option value="">Pas de préférence</option>';

        if (!serviceId) {
            employeeGroup.style.display = 'none';
            employeeLoading.style.display = 'none';
            return;
        }

        fetch(`{{ route('client.appointments.employees') }}?service_id=${serviceId}`)
            .then(response => response.json())
            .then(employees => {
                employeeLoading.style.display = 'none';
                employeeGroup.style.display = 'block';

                if (employees.length === 0) {
                    noEmployees.style.display = 'block';
                } else {
                    employees.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id;
                        option.textContent = employee.name;
                        employeeSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading employees:', error);
                employeeLoading.style.display = 'none';
                employeeGroup.style.display = 'block';
                noEmployees.style.display = 'block';
            });
    }

    function updateSummary() {
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const date = dateInput.value;
        const time = timeSelect.value;

        if (serviceSelect.value) {
            const serviceName = selectedOption.text.split(' - ')[0];
            const price = selectedOption.dataset.price;
            const duration = selectedOption.dataset.duration;

            let html = `
                <p><strong>Service:</strong> ${serviceName}</p>
                <p><strong>Durée:</strong> ${duration} minutes</p>
                <p><strong>Prix:</strong> <span class="text-primary font-weight-bold">${price} FCFA</span></p>
            `;

            if (date) {
                const formattedDate = new Date(date).toLocaleDateString('fr-FR', {
                    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                });
                html += `<p><strong>Date:</strong> ${formattedDate}</p>`;
            }

            if (time) {
                html += `<p><strong>Heure:</strong> ${time}</p>`;
            }

            summary.innerHTML = html;
        } else {
            summary.innerHTML = '<p class="text-muted">Sélectionnez un service pour voir le récapitulatif</p>';
        }
    }

    serviceSelect.addEventListener('change', function() {
        loadEmployeesForService(this.value);
        updateSummary();
    });
    dateInput.addEventListener('change', updateSummary);
    timeSelect.addEventListener('change', updateSummary);

    // Load employees if service is pre-selected (e.g., from old() value)
    if (serviceSelect.value) {
        loadEmployeesForService(serviceSelect.value);
    }
});
</script>
@endsection
