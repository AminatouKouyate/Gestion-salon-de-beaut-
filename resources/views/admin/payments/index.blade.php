@extends('admin.layouts.master')

@section('content')
<h2 class="mb-4">Ajouter un paiement</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.payments.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="client_id" class="form-label">Client</label>
        <select name="client_id" id="client_id" class="form-control" required>
            <option value="">-- Sélectionner un client --</option>
            @foreach($clients as $client)

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            <option value="">-- Sélectionner un rendez-vous --</option>
            @foreach($appointments as $appointment)
                <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                    {{ $appointment->client->name ?? 'N/A' }} - {{ $appointment->service->name ?? 'N/A' }} ({{ $appointment->scheduled_at->format('d/m/Y H:i') }})
        <label for="duration" class="form-label">Durée (minutes)</label>
    <div class="mb-3">
        <label for="role" class="form-label">Rôle</label>
        <select name="role" id="role" class="form-control" required>
            <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employé</option>
            <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
        <label for="status" class="form-label">Statut</label>
        <select name="status" id="status" class="form-control" required>
            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>En attente</option>
            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Payé</option>
        </select>
    <div class="mb-3 form-check">
        <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', true) ? 'checked' : '' }}>
        <label class="form-check-label" for="active">Actif</label>
    </div>

    <button type="submit" class="btn btn-success">Ajouter</button>
    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Annuler</a>
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Annuler</a>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
