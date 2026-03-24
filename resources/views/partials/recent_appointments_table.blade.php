{{--
    Vue : Tableau des rendez-vous récents (partial)
    Description : Composant affichant un tableau des rendez-vous les plus récents pour le tableau de bord.
--}}
<table class="table table-bordered table-striped">
    <thead>
        <tr>
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
            <td>{{ $appointment->client->name ?? '�' }}</td>
            <td>{{ $appointment->employee->name ?? '�' }}</td>
            <td>{{ $appointment->service->name ?? '�' }}</td>
            <td>{{ $appointment->scheduled_at->format('d/m/Y H:i') }}</td>
            <td>{!! $appointment->status_badge !!}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">Aucun rendez-vous récent.</td>
        </tr>
        @endforelse
    </tbody>
</table>
