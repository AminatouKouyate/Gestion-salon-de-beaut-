<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Employé</th>
            <th>Service</th>
            <th>Date / Heure</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        @forelse($appointments as $appointment)
        <tr>
            <td>{{ $appointment->id }}</td>
            <td>{{ $appointment->client->name ?? 'N/A' }}</td>
            <td>{{ $appointment->employee->name ?? 'N/A' }}</td>
            <td>{{ $appointment->service->name ?? 'N/A' }}</td>
            <td>{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
            <td><span class="badge bg-{{ ['pending' => 'warning', 'confirmed' => 'primary', 'completed' => 'success', 'canceled' => 'danger'][$appointment->status] ?? 'secondary' }}">{{ ucfirst($appointment->status) }}</span></td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">Aucun rendez-vous récent.</td>
        </tr>
        @endforelse
    </tbody>
</table>
