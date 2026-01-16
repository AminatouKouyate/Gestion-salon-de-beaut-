@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- Titre + bouton --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Liste des rendez-vous</h1>
            <a href="{{ route('admin.appointments.create') }}" class="btn btn-primary">
                Ajouter un rendez-vous
            </a>
        </div>

        {{-- Message succès --}}
        @include('partials.success')

        {{-- Card --}}
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->id }}</td>

                                <td>{{ $appointment->client->name ?? '—' }}</td>

                                <td>{{ $appointment->service->name ?? '—' }}</td>

                                <td>{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>

                                <td>
                                    <span class="badge bg-{{ $appointment->status === 'confirmed' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>

                                <td class="text-end">
                                    @include('partials.appointment-actions', [
                                        'appointment' => $appointment
                                    ])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Aucun rendez-vous trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($appointments->hasPages())
                <div class="card-footer">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
