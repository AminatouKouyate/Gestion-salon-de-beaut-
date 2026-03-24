{{--
    Vue : Réinitialisation du mot de passe - Client
    Description : Formulaire de création d'un nouveau mot de passe pour les clients après validation du lien de réinitialisation.
--}}
@extends('auth.layout')

@section('title','Nouveau mot de passe - KAARJA Beauté')

@section('left')
    <div class="left-content">
        <div class="form-logo"><i class="fa fa-lock"></i></div>
        <h2>Nouveau mot de passe</h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Créez un nouveau mot de passe sécurisé</p>
    </div>
@endsection

@section('right')
    @if ($errors->any())
        <div class="alert-luxury alert-danger" role="alert">
            <ul class="mb-0 pl-3" style="list-style:none;">
                @foreach ($errors->all() as $error)
                    <li><i class="fa fa-exclamation-circle mr-1"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('client.password.update') }}" autocomplete="off">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label for="email"><i class="fa fa-envelope"></i> Adresse email</label>
            <input type="email" class="input-luxury @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $email ?? '') }}" placeholder="votre@email.com" required autofocus>
        </div>

        <div class="form-group">
            <label for="password"><i class="fa fa-lock"></i> Nouveau mot de passe</label>
            <input type="password" class="input-luxury @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
            <small class="password-hint">Minimum 8 caractères</small>
        </div>

        <div class="form-group">
            <label for="password_confirmation"><i class="fa fa-lock"></i> Confirmer le mot de passe</label>
            <input type="password" class="input-luxury" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-luxury"><i class="fa fa-save mr-2"></i>Enregistrer le mot de passe</button>

        <div class="text-center mt-3">
            <a href="{{ route('client.login') }}" class="back-link"><i class="fa fa-arrow-left mr-1"></i> Retour à la connexion</a>
        </div>
    </form>
@endsection
