{{--
    Vue : Modification d'un client
    Route : admin.clients.edit
    Contrôleur : ClientController@edit
    Description : Formulaire de modification des informations d'un client existant
                  (nom, email, téléphone). Le mot de passe n'est pas modifiable ici.
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
            <h2 class="beauty-page-title">Modifier le client </h2>
            <p class="beauty-page-subtitle">Mettre à jour les informations</p>
        </div>
    </div>
    <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left mr-2"></i>Retour à la liste
    </a>
</div>

{{-- Section : Messages de succès et d'erreur --}}
@include('partials.success')
@include('partials.error')

{{-- Section : Formulaire de modification --}}
<div class="beauty-card">
    <div class="beauty-card-body">
        <form action="{{ route('admin.clients.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $client->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $client->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Téléphone</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">🇲🇱 +223</span></div>
                    <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $client->phone) }}" placeholder="XX XX XX XX">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <p class="form-text">Laissez les champs de mot de passe vides pour ne pas le modifier.</p>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</div>
    </div>
</div>
@endsection
