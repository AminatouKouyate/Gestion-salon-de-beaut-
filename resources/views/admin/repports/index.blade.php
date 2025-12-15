@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Liste des paiements</h2>
    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">Ajouter un paiement</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Filtrer les paiements</h5>
        <form method="GET" action="{{ route('admin.payments.index') }}">
            <div class="row">
                <div class="col-md-5">
                    <label for="client_id">Client</label>
                    <select name="client_id" id="client_id" class="form-control">
                        <option value="">Tous les clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="status">Statut du paiement</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payé</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Rendez-vous</th>
            <th>Montant (€)</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($payments as $payment)
        <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->client->name ?? 'Client supprimé' }}</td>
            <td><a href="{{ route('admin.appointments.edit', $payment->appointment_id) }}">{{ $payment->appointment->scheduled_at->format('d/m/Y H:i') }}</a></td>
            <td>{{ number_format($payment->amount, 2) }}</td>
            <td>{{ $payment->created_at->format('d/m/Y') }}</td>
            <td>{{ $payment->status }}</td>
            <td>
                <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce paiement ?')">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $payments->appends(request()->query())->links() }}
@endsection
