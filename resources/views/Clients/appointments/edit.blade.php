@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Modifier le rendez-vous</h4>
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
                        <h4 class="card-title">Modifier les détails</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('client.appointments.update', $appointment) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="service_id">Service <span class="text-danger">*</span></label>
                                <select name="service_id" id="service_id" class="form-control @error('service_id') is-invalid @enderror" required>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}"
                                                {{ $appointment->service_id == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} - {{ $service->price }} FCFA ({{ $service->duration }} min)
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="employee_id">Employé</label>
                                <select name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror">
                                    <option value="">Pas de préférence</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                                {{ $appointment->employee_id == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                               value="{{ $appointment->date->format('Y-m-d') }}"
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
                                            @for($hour = 9; $hour < 18; $hour++)
                                                <option value="{{ sprintf('%02d:00', $hour) }}"
                                                        {{ $appointment->time == sprintf('%02d:00:00', $hour) ? 'selected' : '' }}>
                                                    {{ sprintf('%02d:00', $hour) }}
                                                </option>
                                                <option value="{{ sprintf('%02d:30', $hour) }}"
                                                        {{ $appointment->time == sprintf('%02d:30:00', $hour) ? 'selected' : '' }}>
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
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3">{{ $appointment->notes }}</textarea>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save mr-2"></i>Enregistrer les modifications
                                </button>
                                <a href="{{ route('client.appointments.index') }}" class="btn btn-secondary ml-2">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-warning">
                    <div class="card-header bg-warning">
                        <h4 class="card-title text-white mb-0">Status actuel</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Status:</strong>
                            <span class="badge badge-{{ $appointment->status == 'confirmed' ? 'success' : 'warning' }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </p>
                        <p><strong>Créé le:</strong> {{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
