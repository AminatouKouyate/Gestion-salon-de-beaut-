{{-- Vue partielle : Boutons d'actions pour un rendez-vous (voir, modifier, supprimer) --}}
{{-- Variable attendue : $appointment (objet rendez-vous avec son id) --}}
<div class="btn-group">
    {{-- Bouton pour voir les détails du rendez-vous --}}
    <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-sm btn-info text-white" title="Voir">
        <i class="fa fa-eye"></i>
    </a>
    {{-- Bouton pour modifier le rendez-vous --}}
    <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="btn btn-sm btn-primary" title="Modifier">
        <i class="fa fa-pencil"></i>
    </a>
    {{-- Formulaire de suppression du rendez-vous avec confirmation --}}
    <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" class="d-inline confirm-delete" data-confirm-message="Voulez-vous vraiment supprimer ce rendez-vous ?">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
            <i class="fa fa-trash"></i>
        </button>
    </form>
</div>
