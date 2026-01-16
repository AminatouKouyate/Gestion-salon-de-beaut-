@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Ajouter un rendez-vous</h1>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-secondary">
                Retour
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.appointments.store') }}" method="POST">
                    @csrf

                    {{-- Client --}}
                    <div class="mb-3">
                        <label class="form-label">Client</label>
                        <select name="client_id" class="form-select" required>
                            <option value="">-- Choisir un client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                    {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Service --}}
                    <div class="mb-3">
                        <label class="form-label">Service</label>
                        <select name="service_id" class="form-select" required>
                            <option value="">-- Choisir un service --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}"
                                    {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Date --}}
                    <div class="mb-3">
                        <label class="form-label">Date du rendez-vous</label>
                        <input type="datetime-local"
                               name="scheduled_at"
                               class="form-control"
                               value="{{ old('scheduled_at') }}"
                               required>
                        @error('scheduled_at') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Statut --}}
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select">
                            <option value="pending">En attente</option>
                            <option value="confirmed">Confirmé</option>
                            <option value="cancelled">Annulé</option>
                        </select>
                    </div>

                    <button class="btn btn-success">Enregistrer</button>
                    <a href="{{ route('admin.appointments.index') }}" class="btn btn-light">
                        Annuler
                    </a>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
