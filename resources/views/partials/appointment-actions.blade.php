{{--
    Vue : Actions sur les rendez-vous (partial)
    Description : Composant des boutons d'actions spécifiques aux rendez-vous (confirmer, annuler, compléter) selon le statut.
--}}
@php
    $appointmentExists = isset($appointment) && $appointment;
@endphp

@if($appointmentExists)
    <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-sm btn-info" title="Voir">
        <i class="fa fa-eye"></i>
    </a>

    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="btn btn-sm btn-primary" title="Modifier">
        <i class="fa fa-pencil"></i>
    </a>

    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST" style="display:inline-block;" class="confirm-delete" data-confirm-message="Êtes-vous sûr de vouloir supprimer ce rendez-vous ?">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
            <i class="fa fa-trash"></i>
        </button>
    </form>
@endif
