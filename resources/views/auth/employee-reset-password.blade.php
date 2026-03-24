{{--
    Vue : Réinitialisation du mot de passe - Employé
    Description : Formulaire de création d'un nouveau mot de passe pour les employés après validation du lien.
--}}
@extends('auth.layout')

@section('title','Réinitialisation - KAARJA Beauté')

@section('left')
    <div class="left-content">
        <div class="left-icon">
            <i class="fa fa-key"></i>
        </div>
        <h2>Nouveau mot de passe</h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Créez un nouveau mot de passe sécurisé</p>
    </div>
@endsection

@section('right')
    <div class="form-logo"><i class="fa fa-key"></i></div>
    <h3>Nouveau mot de passe</h3>

    @if ($errors->any())
        <div class="alert-luxury alert-danger" role="alert">
            <i class="fa fa-exclamation-circle"></i>
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('employee.password.update') }}" autocomplete="off">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group-luxury">
            <label for="email"><i class="fa fa-envelope"></i> Adresse email</label>
            <input type="email" class="input-luxury @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $email ?? '') }}" placeholder="employe@kaarja.com" required autofocus>
        </div>

        <div class="form-group-luxury">
            <label for="password"><i class="fa fa-lock"></i> Nouveau mot de passe</label>
            <input type="password" class="input-luxury @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••" required>
        </div>

        <div class="form-group-luxury">
            <label for="password_confirmation"><i class="fa fa-lock"></i> Confirmer le mot de passe</label>
            <input type="password" class="input-luxury" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-luxury"><i class="fa fa-save"></i> Enregistrer le mot de passe</button>
    </form>

    <a href="{{ route('employee.login') }}" class="back-link"><i class="fa fa-arrow-left"></i> Retour à la connexion</a>
@endsection
