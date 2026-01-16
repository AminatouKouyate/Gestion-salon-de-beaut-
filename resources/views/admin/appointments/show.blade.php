@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Détails du rendez-vous</h1>
            <div>
                <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                   class="btn btn-warning">
                    Modifier
                </a>
                <a href="{{ route('admin.appointments.index') }}"
                   class="btn btn-secondary">
                    Retour
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $appointment->id }}</td>
                    </tr>

                    <tr>
                        <th>Client</th>
                        <td>{{ $appointment->client->name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th>Service</th>
                        <td>{{ $appointment->service->name ?? '—' }}</td>
                    </tr>

                    <tr>
                        <th>Date</th>
                        <td>{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                    </tr>

                    <tr>
                        <th>Statut</th>
                        <td>
                            <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : 'secondary' }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <th>Créé le</th>
                        <td>{{ $appointment->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

            </div>
        </div>

    </div>
</div>
@endsection
