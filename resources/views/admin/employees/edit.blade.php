@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Modifier l'employé</h1>
    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Retour à la liste</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $employee->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $employee->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="employee" {{ old('role', $employee->role) == 'employee' ? 'selected' : '' }}>Employé</option>
                    <option value="manager" {{ old('role', $employee->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <p class="form-text">Laissez les champs de mot de passe vides pour ne pas le modifier.</p>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
    </div>
</div>
@endsection
