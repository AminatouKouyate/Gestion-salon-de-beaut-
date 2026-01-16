@extends('layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Ajouter un service</h1>
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Retour à la liste</a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.services.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nom du service</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Catégorie</label>
                <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" value="{{ old('category') }}">
                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Prix (FCFA)</label>
                    <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required step="1" min="0">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="duration" class="form-label">Durée (minutes)</label>
                    <input type="number" name="duration" id="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration') }}" required min="1">
                    @error('duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" id="active" class="form-check-input" value="1" @if(old('active', true)) checked @endif>
                <label for="active" class="form-check-label">Service actif</label>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</div>
    </div>
</div>
@endsection
