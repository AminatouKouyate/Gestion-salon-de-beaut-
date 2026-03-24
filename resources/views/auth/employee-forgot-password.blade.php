{{--
    Vue : Mot de passe oublié - Employé
    Description : Formulaire de demande de réinitialisation de mot de passe pour les employés.
--}}
@extends('auth.layout')

@section('title','Mot de passe oublié - KAARJA Beauté')

@section('left')
    <div class="left-content">
        <div class="left-icon">
            <i class="fa fa-lock"></i>
        </div>
        <h2>Récupération</h2>
        <div class="gold-line"></div>
        <p class="left-subtitle">Réinitialisez votre mot de passe en toute sécurité</p>
    </div>
@endsection

@section('right')
    <div class="form-logo"><i class="fa fa-lock"></i></div>
    <h3>Mot de passe oublié</h3>

    @if (session('status'))
        <div class="alert-luxury alert-success" role="alert">
            <i class="fa fa-check-circle"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

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

    <form method="POST" action="{{ route('employee.password.email') }}" autocomplete="off">
        @csrf
        <div class="form-group-luxury">
            <label for="email"><i class="fa fa-envelope"></i> Adresse email</label>
            <input type="email" class="input-luxury @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="employe@kaarja.com" required autofocus>
        </div>
        <button type="submit" class="btn-luxury"><i class="fa fa-paper-plane"></i> Envoyer le lien</button>
    </form>

    <a href="{{ route('employee.login') }}" class="back-link"><i class="fa fa-arrow-left"></i> Retour à la connexion</a>
@endsection
