@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Ajouter un employé</h1>
    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Retour à la liste</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.employees.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="employee" @if(old('role') == 'employee') selected @endif>Employé</option>
                    <option value="manager" @if(old('role') == 'manager') selected @endif>Manager</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</div>
    </div>
</div>
@endsection
