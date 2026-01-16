@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
<h2>Ajouter un paiement</h2>

<form action="{{ route('admin.payments.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Client</label>
        <select name="client_id" class="form-control" required>
            <option value="">-- Sélectionner un client --</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Rendez-vous</label>
        <select name="appointment_id" class="form-control" required>
            <option value="">-- Sélectionner un rendez-vous --</option>
            @foreach($appointments as $appointment)
                <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                    {{ $appointment->client->name ?? 'N/A' }} - {{ $appointment->service->name ?? 'N/A' }} ({{ $appointment->scheduled_at->format('d/m/Y H:i') }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Montant (FCFA)</label>
        <input type="number" name="amount" step="1" class="form-control" value="{{ old('amount') }}" required>
    </div>

    <div class="mb-3">
        <label>Statut</label>
        <select name="status" class="form-control" required>
            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>En attente</option>
            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Payé</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Ajouter</button>
</form>
    </div>
</div>
@endsection

