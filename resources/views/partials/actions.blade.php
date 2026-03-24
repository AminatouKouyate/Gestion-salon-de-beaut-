{{--
    Vue : Boutons d'actions (partial)
    Description : Composant réutilisable pour les boutons d'actions standards (voir, modifier, supprimer) dans les tableaux.
--}}
@php
    if (isset($employee)) {
        $item = $employee;
        $routePrefix = 'admin.employees';
        $label = 'cet employé';
    } elseif (isset($client)) {
        $item = $client;
        $routePrefix = 'admin.clients';
        $label = 'ce client';
    } elseif (isset($service)) {
        $item = $service;
        $routePrefix = 'admin.services';
        $label = 'ce service';
    }
@endphp

@if(isset($item))
<a href="{{ route($routePrefix . '.edit', $item) }}" class="btn btn-sm btn-primary" title="Modifier">
    <i class="fa fa-pencil"></i>
</a>

<form action="{{ route($routePrefix . '.destroy', $item) }}" method="POST" style="display:inline-block;" class="confirm-delete" data-confirm-message="Êtes-vous sûr de vouloir supprimer {{ $label }} ?">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
        <i class="fa fa-trash"></i>
    </button>
</form>
@endif
