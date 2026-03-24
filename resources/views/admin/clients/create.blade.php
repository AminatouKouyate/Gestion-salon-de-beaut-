{{--
    Vue : Création d'un client
    Route : admin.clients.create
    Contrôleur : ClientController@create
    Description : Formulaire d'enregistrement d'un nouveau client avec les champs
                  nom, email, téléphone et mot de passe.
--}}
@extends('layouts.admin-master')

{{-- Section : Contenu principal --}}
@section('content')
<div class="content-body">
    <div class="container-fluid">
<div class="beauty-page-header">
    <div class="beauty-page-header-left">
        <div class="beauty-page-icon"><i class="fa fa-heart"></i></div>
        <div>
            <h2 class="beauty-page-title">Ajouter un client </h2>
            <p class="beauty-page-subtitle">Enregistrer un nouveau client</p>
        </div>
    </div>
    <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left mr-2"></i>Retour à la liste
    </a>
</div>

{{-- Section : Messages de succès et d'erreur --}}
@include('partials.success')
@include('partials.error')

{{-- Section : Formulaire de création --}}
<div class="beauty-card">
    <div class="beauty-card-body">
        <form action="{{ route('admin.clients.store') }}" method="POST">
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
                <label for="phone" class="form-label">Téléphone</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">🇲🇱 +223</span></div>
                    <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="XX XX XX XX">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
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
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</div>
    </div>
</div>
@endsection
