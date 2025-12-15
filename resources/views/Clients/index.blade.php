@extends('master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Gestion des Clients</h4>
                    <p class="mb-0">Liste de tous les clients enregistrés.</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                {{-- Optionnel: Ajouter un bouton pour créer un nouveau client --}}
                {{-- <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus mr-2"></i>Nouveau Client
                </a> --}}
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Date d'inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->phone ?? 'N/A' }}</td>
                                <td>{{ $client->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.clients.show', $client->id) }}" class="btn btn-info btn-sm">Voir</a>
                                    <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucun client trouvé.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $clients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
