@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Liste des services</h1>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Ajouter un service</a>
</div>

@include('partials.success')

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Durée (min)</th>
                    <th>Actif</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                <tr>
                    <td>{{ $service->id }}</td>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->category ?? 'N/A' }}</td>
                    <td>{{ number_format($service->price, 2, ',', ' ') }} €</td>
                    <td>{{ $service->duration }}</td>
                    <td><span class="badge bg-{{ $service->active ? 'success' : 'secondary' }}">{{ $service->active ? 'Oui' : 'Non' }}</span></td>
                    <td class="text-end">
                        @include('admin.services.partials.actions', ['service' => $service])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Aucun service trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($services->hasPages())
        <div class="card-footer">
            {{ $services->links() }}
        </div>
    @endif
</div>
@endsection
