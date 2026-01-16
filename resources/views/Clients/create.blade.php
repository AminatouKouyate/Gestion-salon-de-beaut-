@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Prendre un rendez-vous</h4>
                    <p class="mb-0">Choisissez un service et un créneau qui vous convient.</p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('client.appointments.store') }}" method="POST">
                    @csrf

                    {{-- Étape 1: Choisir un service --}}
                    <div class="form-group">
                        <label for="service_id">Choisissez un service</label>
                        <select name="service_id" id="service_id" class="form-control" required>
                            <option value="">-- Sélectionner un service --</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-duration="{{ $service->duration }}">
                                    {{ $service->name }} ({{ $service->duration }} min) - {{ $service->price }} FCFA
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TODO: Étape 2: Choisir un employé (peut être chargé dynamiquement via JS) --}}

                    {{-- Étape 3: Choisir une date --}}
                    <div class="form-group">
                        <label for="date">Choisissez une date</label>
                        <input type="date" name="date" id="date" class="form-control" required>
                    </div>

                    {{-- TODO: Étape 4: Choisir un créneau horaire (chargé dynamiquement via JS) --}}
                    <div class="form-group">
                        <label for="time">Choisissez une heure (simplifié)</label>
                        <input type="time" name="time" id="time" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Demander le rendez-vous</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
