@extends('admin.layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Liste des clients</h1>
    <a href="{{ route('admin.clients.create') }}" class="btn btn-primary">Ajouter un client</a>
</div>

@include('partials.success')

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->phone ?? 'N/A' }}</td>
                    <td class="text-end">
                        @include('admin.clients.partials.actions', ['client' => $client])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun client trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($clients->hasPages())
        <div class="card-footer">
            {{ $clients->links() }}
        </div>
    @endif
</div>
@endsection
