<div class="btn-group">
    <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-sm btn-info text-white" title="Voir">
        <i class="fa fa-eye"></i>
    </a>
    <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="btn btn-sm btn-primary" title="Modifier">
        <i class="fa fa-pencil"></i>
    </a>
    <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer ce rendez-vous ?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
            <i class="fa fa-trash"></i>
        </button>
    </form>
</div>
