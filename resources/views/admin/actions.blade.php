{{-- Vue partielle : Boutons d'actions génériques (modifier / supprimer) --}}
{{-- Réutilisable pour les employés et les clients --}}
{{-- Variables attendues : $employee (objet employé) OU $client (objet client) --}}
{{-- Variables calculées : $item (l'entité), $routePrefix (préfixe de route), $label (libellé pour la confirmation) --}}
@php
    {{-- Détermination du type d'entité pour adapter les routes et le message de confirmation --}}
    if (isset($employee)) {
        $item = $employee;
        $routePrefix = 'admin.employees';
        $label = 'cet employé';
    } elseif (isset($client)) {
        $item = $client;
        $routePrefix = 'admin.clients';
        $label = 'ce client';
    }
@endphp

{{-- Condition : afficher les actions uniquement si l'entité est définie --}}
@if(isset($item))
{{-- Bouton de modification : redirige vers le formulaire d'édition --}}
<a href="{{ route($routePrefix . '.edit', $item) }}" class="btn btn-sm btn-primary" title="Modifier">
    <i class="fa fa-pencil"></i>
</a>

{{-- Formulaire de suppression avec confirmation JavaScript --}}
<form action="{{ route($routePrefix . '.destroy', $item) }}" method="POST" style="display:inline-block;" class="confirm-delete" data-confirm-message="Êtes-vous sûr de vouloir supprimer {{ $label }} ?">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
        <i class="fa fa-trash"></i>
    </button>
</form>
@endif
