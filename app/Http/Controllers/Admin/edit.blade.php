@extends('admin.layouts.app')

@section('content')
<h2 class="mb-4">Modifier un employé</h2>
<h2 class="mb-4">Modifier un service</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
<form action="{{ route('admin.services.update', $service->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nom</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
        <label for="name" class="form-label">Nom du service</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $service->name) }}" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $employee->email) }}" required>
        <label for="category" class="form-label">Catégorie</label>
        <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $service->category) }}">
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Rôle</label>
        <select name="role" id="role" class="form-control" required>
            <option value="employee" {{ old('role', $employee->role) == 'employee' ? 'selected' : '' }}>Employé</option>
            <option value="manager" {{ old('role', $employee->role) == 'manager' ? 'selected' : '' }}>Manager</option>
            <option value="admin" {{ old('role', $employee->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
        </select>
        <label for="price" class="form-label">Prix (€)</label>
        <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" value="{{ old('price', $service->price) }}" required>
    </div>
    <div class="mb-3">
        <label for="duration" class="form-label">Durée (minutes)</label>
        <input type="number" name="duration" id="duration" class="form-control" min="1" value="{{ old('duration', $service->duration) }}" required>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', $service->active) ? 'checked' : '' }}>
        <label class="form-check-label" for="active">Actif</label>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Annuler</a>
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Annuler</a>
</form>
@endsection
