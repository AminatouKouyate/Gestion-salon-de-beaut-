{{-- Vue partielle : Tableau des rendez-vous récents --}}
{{-- Utilisé dans le tableau de bord pour afficher les derniers rendez-vous --}}
{{-- Variable attendue : $appointments (collection de rendez-vous avec relations client, service et statut) --}}
<table class="table table-striped">
    {{-- En-tête du tableau --}}
    <thead>
        <tr>
            <th>Client</th>
            <th>Service</th>
            <th>Date</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
        {{-- Boucle sur les rendez-vous ; affiche un message si la liste est vide --}}
        @forelse ($appointments as $appointment)
            <tr>
                {{-- Nom du client (relation $appointment->client) --}}
                <td>{{ $appointment->client->name ?? '—' }}</td>
                {{-- Nom du service (relation $appointment->service) --}}
                <td>{{ $appointment->service->name ?? '—' }}</td>
                {{-- Date et heure du rendez-vous ($appointment->scheduled_at) --}}
                <td>{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
                {{-- Badge de statut avec couleur dynamique via l'enum AppointmentStatus --}}
                <td>
                    <span class="badge badge-{{ $appointment->status->badgeClass() }}">
                        {{ $appointment->status->label() }}
                    </span>
                </td>
            </tr>
        @empty
            {{-- Message affiché quand aucun rendez-vous récent n'existe --}}
            <tr>
                <td colspan="4" class="text-center text-muted">
                    Aucun rendez-vous récent
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
