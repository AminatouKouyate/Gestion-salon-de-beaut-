<table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Service</th>
            <th>Date</th>
            <th>Statut</th>
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
                    <span class="badge bg-secondary">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">
                    Aucun rendez-vous récent
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
