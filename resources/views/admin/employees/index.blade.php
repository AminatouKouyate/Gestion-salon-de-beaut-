@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Liste des employés</h1>
    <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">Ajouter un employé</a>
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
                    <th>Rôle</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td>{{ $employee->id }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td><span class="badge bg-info">{{ ucfirst($employee->role) }}</span></td>
                    <td class="text-end">
                        @include('admin.employees.partials.actions', ['employee' => $employee])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Aucun employé trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($employees->hasPages())
        <div class="card-footer">
            {{ $employees->links() }}
        </div>
    @endif
</div>
@endsection
