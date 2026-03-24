{{--
    Vue : Liste des clients
    Route : admin.clients.index
    Contrôleur : ClientController@index
    Description : Affiche la liste paginée de tous les clients du salon avec
                  actions de modification, activation/désactivation et suppression.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">

        {{-- Section : En-tête de page --}}
        <div class="beauty-page-header">
            <div class="beauty-page-header-left">
                <div class="beauty-page-icon"><i class="fa fa-heart"></i></div>
                <div>
                    <h2 class="beauty-page-title">Clients </h2>
                    <p class="beauty-page-subtitle">Gérez la clientèle du salon</p>
                </div>
            </div>
            <a href="{{ route('admin.clients.create') }}" class="beauty-btn-primary">
                <i class="fa fa-plus mr-2"></i>Ajouter un client
            </a>
        </div>

        {{-- Section : Messages de succès et d'erreur --}}
        @include('partials.success')
        @include('partials.error')

        {{-- Section : Tableau des données --}}
        <div class="beauty-card mb-4">
            <div class="beauty-card-header">
                <h4><i class="fa fa-users mr-2" style="color:var(--primary);"></i>Tous les clients</h4>
            </div>
            <div class="beauty-card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Statut</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                            <tr>
                                <td><strong>{{ $client->name }}</strong></td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->phone ?? '—' }}</td>
                                <td>
                                    @if($client->active)
                                        <span class="badge badge-success"><i class="fa fa-check mr-1"></i>Actif</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fa fa-times mr-1"></i>Inactif</span>
                                    @endif
                                </td>
                                {{-- Section : Actions (modifier, activer/désactiver, supprimer) --}}
                                <td class="text-right">
                                    <a href="{{ route('admin.clients.edit', $client) }}" class="btn btn-sm btn-primary" title="Modifier"><i class="fa fa-pencil"></i></a>
                                    @if($client->active)
                                        <form action="{{ route('admin.clients.deactivate', $client) }}" method="POST" style="display:inline-block;" class="confirm-toggle" data-confirm-message="Êtes-vous sûr de vouloir désactiver ce client ?" data-confirm-title="Désactiver le client" data-confirm-btn="btn-warning" data-confirm-btn-text="Désactiver">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-warning" title="Désactiver"><i class="fa fa-times-circle"></i></button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.clients.reactivate', $client) }}" method="POST" style="display:inline-block;" class="confirm-toggle" data-confirm-message="Êtes-vous sûr de vouloir réactiver ce client ?" data-confirm-title="Réactiver le client" data-confirm-btn="btn-success" data-confirm-btn-text="Réactiver">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Réactiver"><i class="fa fa-check-circle"></i></button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" style="display:inline-block;" class="confirm-delete" data-confirm-message="Êtes-vous sûr de vouloir supprimer ce client ?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="beauty-empty">
                                        <div class="beauty-empty-icon"><i class="fa fa-heart"></i></div>
                                        <p>Aucun client trouvé</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- Section : Pagination --}}
            @if($clients->hasPages())
            <div class="beauty-card-footer">{{ $clients->links() }}</div>
            @endif
        </div>

    </div>
</div>
@endsection
